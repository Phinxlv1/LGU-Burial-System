<?php

namespace App\Exports;

use App\Models\BurialPermit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BurialPermitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Fetch all permits with deceased records
        return BurialPermit::with('deceased')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Permit Number',
            'Deceased Name',
            'Date of Death',
            'Age',
            'Sex',
            'Burial Type',
            'Applicant Name',
            'Applicant Contact',
            'OR Number',
            'Issued Date',
            'Expiry Date',
            'Status',
            'Processed By',
        ];
    }

    /**
    * @var BurialPermit $permit
    */
    public function map($permit): array
    {
        return [
            $permit->permit_number,
            optional($permit->deceased)->last_name . ', ' . optional($permit->deceased)->first_name,
            optional($permit->deceased)->date_of_death?->format('Y-m-d') ?? '—',
            optional($permit->deceased)->age ?? '—',
            ucfirst(optional($permit->deceased)->sex ?? '—'),
            ucwords(str_replace('_', ' ', $permit->permit_type)),
            $permit->applicant_name ?? '—',
            $permit->applicant_contact ?? '—',
            $permit->or_number ?? '—',
            $permit->issued_date?->format('Y-m-d') ?? '—',
            $permit->expiry_date?->format('Y-m-d') ?? '—',
            ucfirst($permit->status),
            optional($permit->processedBy)->name ?? 'System',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text with light gray background
            1    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F1E3D']
                ]
            ],
        ];
    }
}
