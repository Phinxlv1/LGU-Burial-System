<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    /** GET /import/excel */
    public function showImport()
    {
        $logs = $this->getHistory();
        return view('import.index', compact('logs'));
    }

    public function index()
    {
        return $this->showImport();
    }

    /** POST /import/excel */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $ext          = strtolower($file->getClientOriginalExtension());
        $path         = $file->store('imports', 'local');
        $fullPath     = storage_path('app/private/' . $path);

        try {
            $rows = $ext === 'csv' ? $this->readCsv($fullPath) : $this->readXlsx($fullPath);
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Could not read file: ' . $e->getMessage()]);
        }

        if (empty($rows)) {
            return back()->withErrors(['file' => 'The file appears to be empty.']);
        }

        // ── Find header row (scan first 5 rows for "first_name") ──
        $headerRowIndex = null;
        $headerMap      = [];

        foreach (array_slice($rows, 0, 5, true) as $rowIdx => $row) {
            foreach ($row as $colIdx => $cellVal) {
                if ($this->normalise($cellVal) === 'first_name') {
                    $headerRowIndex = $rowIdx;
                    break 2;
                }
            }
        }

        if ($headerRowIndex === null) {
            return back()->withErrors([
                'file' => 'Header row not found. Make sure row 1 contains: first_name, last_name, date_of_death …',
            ]);
        }

        foreach ($rows[$headerRowIndex] as $colIdx => $cellVal) {
            $key = $this->normalise($cellVal);
            if ($key !== '') {
                $headerMap[$key] = $colIdx;
            }
        }

        // ── Process data rows ──
        $dataRows    = array_slice($rows, $headerRowIndex + 1, null, true);
        $imported    = 0;
        $skipped     = 0;
        $skipReasons = [];
        $year        = now()->year;

        foreach ($dataRows as $rowIdx => $row) {
            $nonEmpty = array_filter($row, fn($v) => $v !== null && trim((string) $v) !== '');
            if (empty($nonEmpty)) continue;

            $displayRow  = $rowIdx + 1;
            $firstName   = trim((string) $this->col($row, $headerMap, 'first_name', ''));
            $lastName    = trim((string) $this->col($row, $headerMap, 'last_name', ''));
            $dateOfDeath = $this->parseDate($this->col($row, $headerMap, 'date_of_death'));

            if ($firstName === '' || $lastName === '') {
                $skipped++;
                $skipReasons[] = "Row {$displayRow}: Missing first_name or last_name";
                continue;
            }
            if (!$dateOfDeath) {
                $skipped++;
                $skipReasons[] = "Row {$displayRow}: Missing or invalid date_of_death";
                continue;
            }

            $status = strtolower(trim((string) $this->col($row, $headerMap, 'status', 'pending')));
            if (!in_array($status, ['pending', 'approved', 'released', 'expired'])) {
                $status = 'pending';
            }

            $permitType   = strtolower(trim((string) $this->col($row, $headerMap, 'permit_type', 'cemented')));
            $kindOfBurial = trim((string) $this->col($row, $headerMap, 'kind_of_burial', ''));

            try {
                DB::transaction(function () use (
                    &$imported, $row, $headerMap,
                    $firstName, $lastName, $dateOfDeath,
                    $status, $permitType, $kindOfBurial, $year
                ) {
                    $deceased = DeceasedPerson::create([
                        'first_name'     => $firstName,
                        'last_name'      => $lastName,
                        'date_of_death'  => $dateOfDeath,
                        'nationality'    => $this->col($row, $headerMap, 'nationality', 'Filipino'),
                        'age'            => is_numeric($this->col($row, $headerMap, 'age'))
                                               ? (int) $this->col($row, $headerMap, 'age')
                                               : null,
                        'sex'            => $this->col($row, $headerMap, 'sex'),
                        'kind_of_burial' => $kindOfBurial ?: null,
                    ]);

                    $permitNumber = trim((string) $this->col($row, $headerMap, 'permit_number', ''));
                    if ($permitNumber === '' || BurialPermit::where('permit_number', $permitNumber)->exists()) {
                        $count        = BurialPermit::whereYear('created_at', $year)->count() + 1;
                        $permitNumber = 'BP-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
                    }

                    BurialPermit::create([
                        'permit_number'          => $permitNumber,
                        'deceased_id'            => $deceased->id,
                        'permit_type'            => $permitType ?: 'cemented',
                        'kind_of_burial'         => $kindOfBurial ?: null,
                        'applicant_name'         => $this->col($row, $headerMap, 'applicant_name', 'Unknown'),
                        'applicant_relationship' => $this->col($row, $headerMap, 'applicant_relationship', ''),
                        'applicant_contact'      => $this->col($row, $headerMap, 'applicant_contact', ''),
                        'applicant_address'      => $this->col($row, $headerMap, 'applicant_address'),
                        'status'                 => $status,
                        'issued_date'            => $this->parseDate($this->col($row, $headerMap, 'issued_date')),
                        'expiry_date'            => $this->parseDate($this->col($row, $headerMap, 'expiry_date')),
                        'processed_by'           => Auth::id(),
                    ]);

                    $imported++;
                });
            } catch (\Throwable $e) {
                $skipped++;
                $skipReasons[] = "Row {$displayRow}: " . $e->getMessage();
            }
        }

        try {
            ImportLog::create([
                'file_name'    => $originalName,
                'uploaded_by'  => Auth::id(),
                'total_rows'   => count($dataRows),
                'imported'     => $imported,
                'skipped'      => $skipped,
                'skip_reasons' => json_encode($skipReasons),
            ]);
        } catch (\Throwable) {}

        $msg = "{$imported} permit(s) imported successfully.";
        if ($skipped > 0) $msg .= " {$skipped} row(s) skipped.";

        return back()->with('import_success', $msg)->with('skip_reasons', $skipReasons);
    }

    // ─────────────────────────────────────────────────────────────

    private function getHistory()
    {
        try {
            return ImportLog::with('user')->latest()->paginate(20);
        } catch (\Throwable) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
    }

    private function readXlsx(string $path): array
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new \RuntimeException('Run: composer require phpoffice/phpspreadsheet');
        }
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path)->getActiveSheet();
        return array_values($sheet->toArray(null, true, true, false));
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            while (($d = fgetcsv($h)) !== false) {
                $rows[] = array_values($d);
            }
            fclose($h);
        }
        return $rows;
    }

    private function normalise(mixed $val): string
    {
        $s = strtolower(trim((string) $val));
        $s = preg_replace('/[\s\r\n\t]+/', '_', $s);
        $s = preg_replace('/[^a-z0-9_]/', '', $s);
        return trim($s, '_');
    }

    private function col(array $row, array $map, string $key, mixed $default = null): mixed
    {
        if (!isset($map[$key])) return $default;
        $val = $row[$map[$key]] ?? null;
        if ($val === null || trim((string) $val) === '') return $default;
        return $val;
    }

    private function parseDate(mixed $val): ?string
    {
        if ($val === null || trim((string) $val) === '') return null;

        if (is_numeric($val) && $val > 1000) {
            try {
                if (class_exists(\PhpOffice\PhpSpreadsheet\Shared\Date::class)) {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $val)
                        ->format('Y-m-d');
                }
                return date('Y-m-d', (int) (((float) $val - 25569) * 86400));
            } catch (\Throwable) {}
        }

        $str = trim((string) $val);
        foreach (['Y-m-d','Y/m/d','d/m/Y','m/d/Y','d-m-Y','m-d-Y','d.m.Y','Y-m-d H:i:s'] as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, $str);
            if ($dt !== false) return $dt->format('Y-m-d');
        }

        $ts = strtotime($str);
        return $ts !== false ? date('Y-m-d', $ts) : null;
    }
}