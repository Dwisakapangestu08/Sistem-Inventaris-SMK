<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateImportBarang implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function array(): array
    {
        return [
            [
                'Nama/Jenis Barang',
                'Merk/Type',
                'Ukuran',
                'Bahan',
                'Tahun Perolehan',
                'Jumlah Barang',
                'Harga Beli Satuan',
                'Kondisi',
                'Keadaan Barang',
            ],
            [
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama/Jenis Barang',
            'Merk/Type',
            'Ukuran',
            'Bahan',
            'Tahun Perolehan',
            'Jumlah Barang',
            'Harga Beli Satuan',
            'Kondisi',
            'Keadaan Barang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'name' => 'Times New Roman'
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ],
        ];
    }
}
