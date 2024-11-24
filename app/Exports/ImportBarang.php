<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImportBarang implements ToModel, WithHeadingRow
{

    /**
     * Import data from Excel file to Barang model.
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model(array $row)
    {

        // dd($row);
        // Create a new instance of Barang model
        return new Barang([
            // Map the column of Excel file to the attribute of Barang model
            'name_barang' => $row['namajenis_barang'],
            'merk_barang' => $row['merktype'],
            'ukuran_barang' => $row['ukuran'],
            'bahan_barang' => $row['bahan'],
            'tahun_perolehan' => $row['tahun_perolehan'],
            'jumlah_barang' => $row['jumlah_barang'],
            'harga_barang' => $row['harga_beli_satuan'],
            'kondisi_barang' => $row['kondisi'],
            'keadaan_barang' => $row['keadaan_barang'],
        ]);
    }
}
