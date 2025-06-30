<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PerhitunganOresteExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'NIP',
            'Jabatan',
            'Pendidikan',
            'Masa Kerja (Bulan)',
            'Usia',
            'Nilai Kinerja',
            'Ranking Kinerja',
            'Nilai Kompetensi',
            'Ranking Kompetensi',
            'Rekomendasi Lokasi',
            'Status Mutasi',
            'Tahun'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $lastRow = count($this->data) + 1;
        if ($lastRow > 1) {
            $sheet->getStyle('A2:N' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C:C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K:K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M:M')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N:N')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getRowDimension('1')->setRowHeight(30);

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama Pegawai
            'C' => 15,  // NIP
            'D' => 20,  // Jabatan
            'E' => 15,  // Pendidikan
            'F' => 18,  // Masa Kerja
            'G' => 10,  // Usia
            'H' => 15,  // Nilai Kinerja
            'I' => 15,  // Ranking Kinerja
            'J' => 18,  // Nilai Kompetensi
            'K' => 18,  // Ranking Kompetensi
            'L' => 20,  // Rekomendasi Lokasi
            'M' => 15,  // Status Mutasi
            'N' => 10,  // Tahun
        ];
    }
}
