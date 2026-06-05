<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentImportTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return [
            'Student ID',
            'Full Name',
            'Email',
            'Faculty',
            'Department',
            'Year',
            'Semester',
            'Phone',
            'Gender',
            'Password',
        ];
    }

    public function array(): array
    {
        return [
            [
                'SAL/2026/001',
                'Abebe Kebede',
                'abebe.kebede@salale.edu.et',
                'Faculty of Computing',
                'Computer Science',
                3,
                'First',
                '+251911111111',
                'male',
                '',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
