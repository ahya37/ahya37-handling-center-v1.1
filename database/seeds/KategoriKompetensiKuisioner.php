<?php

use Illuminate\Database\Seeder;
use App\KategoriKompetensiKuisionerModel;
use App\Helpers\MicroTime;

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
        ['id' => MicroTime::generate(), 'name' => 'Nilai Bimbingan', 'cby' => 2, 'mby' => null],
        ['id' => MicroTime::generate(), 'name' => 'Nilai Kompetensi Keilmuan', 'cby' => 2, 'mby' => null],
        ['id' => MicroTime::generate(), 'name' => 'Nilai Kerjasama Tim', 'cby' => 2, 'mby' => null],
        ['id' => MicroTime::generate(), 'name' => 'Nilai Harapan Jemaah', 'cby' => 2, 'mby' => null],
        ['id' => MicroTime::generate(), 'name' => 'Nilai Muthowif', 'cby' => 2, 'mby' => null],
        ['id' => MicroTime::generate(), 'name' => 'Nilai Pelayanan CS', 'cby' => 2, 'mby' => null]
       ];

       foreach ($data as $value) {
        KategoriKompetensiKuisionerModel::create($value);  
    }
    }
}
