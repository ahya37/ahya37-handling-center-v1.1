<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Helpers\StrRandom;

class KategoriKompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			['id' => StrRandom::generate(), 'name' => 'Nilai Kompetensi Bimbingan', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
			['id' => StrRandom::generate(), 'name' => 'Nilai Kompetensi Keilmuan', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
			['id' => StrRandom::generate(), 'name' => 'Nilai Kerjasama Tim', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
			['id' => StrRandom::generate(), 'name' => 'Nilai Harapan Jemaah', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
			['id' => StrRandom::generate(), 'name' => 'Nilai Muthowif', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
			['id' => StrRandom::generate(), 'name' => 'Nilai Pelayanan CS', 'created_by' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
		];
		
		foreach($data as $val){
			DB::table('kategori_kompetensi_kuisioner')->insert($val);
		}
    }
}
