<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportBarang implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Barang::select("name_barang", "merk_barang", "ukuran_barang", "bahan_barang", "tahun_perolehan", "jumlah_barang", "harga_barang", "kondisi_barang", "keadaan_barang")->get();
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
