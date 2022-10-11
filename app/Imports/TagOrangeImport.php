<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\DetailTagOrangeModel;

class TagOrangeImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        $id = $this->id;

        return new DetailTagOrangeModel([
            'tag_orange_id' => $id,
            'no_urut'        =>$row['no_urut'],
            'nama_jamaah'        => strtoupper($row['nama_jamaah']),
            'telp_jamaah'        =>$row['telp_jamaah'],
            'email_jamaah'        =>$row['email_jamaah'],
            'alamat_jamaah'        =>$row['alamat_jamaah']
        ]);
    }
}
