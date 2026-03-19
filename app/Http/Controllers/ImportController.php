<?php

namespace App\Http\Controllers;

use App\Imports\PermitsImport;
use App\Models\ImportLog;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function showImport()
    {
        $logs = ImportLog::with('importedBy')->latest()->paginate(10);
        return view('import.index', compact('logs'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import   = new PermitsImport($filename);
            Excel::import($import, $request->file('file'));

            $msg = "Import complete — {$import->imported} permits imported, {$import->skipped} skipped.";
            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}