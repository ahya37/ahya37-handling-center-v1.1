<?php

use Illuminate\Database\Seeder;
use App\KategoriKompetensiKuisionerModel;

class KategoriKompetensiKuisioner extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $data = [
        ['name' => 'Nilai Bimbingan', 'cby' => 2, 'mby' => null],
        ['name' => 'Nilai Kompetensi Keilmuan', 'cby' => 2, 'mby' => null],
        ['name' => 'Nilai Kerjasama Tim', 'cby' => 2, 'mby' => null],
        ['name' => 'Nilai Harapan Jemaah', 'cby' => 2, 'mby' => null],
        ['name' => 'Nilai Muthowif', 'cby' => 2, 'mby' => null],
        ['name' => 'Nilai Pelayanan CS', 'cby' => 2, 'mby' => null]
       ];

       foreach ($data as $value) {
        KategoriKompetensiKuisionerModel::create($value);
    }
    }
}
