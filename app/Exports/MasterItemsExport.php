<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MasterItem::with('kategoris')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No,Nama Kategori,Nama Item,Nama Supplier,Harga,Laba,Harga Jual'
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $this->rowNumber++;

        $kategoriNames = $row->kategoris->pluck('nama')->implode(',');
        $harga_jual = $row->harga_beli + ($row->harga_beli * $row->laba / 100);

        $rowString = implode(',', [
            $this->rowNumber,
            $kategoriNames,
            $row->nama,
            $row->supplier,
            $row->harga_beli,
            $row->laba,
            round($harga_jual)
        ]);

        return [
            $rowString
        ];
    }
}
