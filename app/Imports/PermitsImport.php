<?php

namespace App\Imports;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ImportLog;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PermitsImport implements ToCollection, WithHeadingRow
{
    public string $filename;
    public int $imported = 0;
    public int $skipped  = 0;
    public array $skippedDetails = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 3;

            // Skip rows missing required fields
            if (empty($row['first_name']) || empty($row['last_name'])) {
                $this->skipped++;
                $this->skippedDetails[] = "Row {$rowNum}: Missing first_name or last_name";
                continue;
            }

            if (empty($row['date_of_death'])) {
                $this->skipped++;
                $this->skippedDetails[] = "Row {$rowNum}: {$row['first_name']} {$row['last_name']} — missing date_of_death";
                continue;
            }

            // Check for duplicate deceased
            $deceased = DeceasedPerson::firstOrCreate(
                [
                    'first_name' => trim($row['first_name']),
                    'last_name'  => trim($row['last_name']),
                ],
                [
                    'middle_name'    => $row['middle_name'] ?? null,
                    'date_of_death'  => $row['date_of_death'] ?? null,
                    'nationality'    => $row['nationality'] ?? null,
                    'age'            => $row['age'] ?? null,
                    'sex'            => $row['sex'] ?? null,
                    'kind_of_burial' => $row['kind_of_burial'] ?? null,
                ]
            );

            // Check for duplicate permit
            $permitType = $row['kind_of_burial'] ?? 'cemented';
            $exists = BurialPermit::where('deceased_id', $deceased->id)
                ->where('permit_type', $permitType)
                ->exists();

            if ($exists) {
                $this->skipped++;
                $this->skippedDetails[] = "Row {$rowNum}: {$row['first_name']} {$row['last_name']} — duplicate permit already exists";
                continue;
            }

            $latest       = BurialPermit::whereYear('created_at', now()->year)->count() + 1;
            $permitNumber = 'BP-' . now()->year . '-' . str_pad($latest, 5, '0', STR_PAD_LEFT);

            BurialPermit::create([
                'permit_number'     => $permitNumber,
                'deceased_id'       => $deceased->id,
                'permit_type'       => $permitType,
                'kind_of_burial'    => $row['kind_of_burial'] ?? null,
                'applicant_name'    => $row['requesting_party'] ?? null,
                'applicant_contact' => $row['contact_no'] ?? null,
                'applicant_address' => $row['address'] ?? null,
                'status'            => 'released',
                'processed_by'      => Auth::id(),
            ]);

            $this->imported++;
        }

        // Save import log
        ImportLog::create([
            'filename'        => $this->filename,
            'total_rows'      => $this->imported + $this->skipped,
            'imported'        => $this->imported,
            'skipped'         => $this->skipped,
            'skipped_details' => $this->skippedDetails,
            'imported_by'     => Auth::id(),
        ]);
    }
}