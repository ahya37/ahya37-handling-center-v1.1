<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => Str::random(30),'name' => 'Pembimbing','created_by' => 2],
            ['id' => Str::random(30),'name' => 'Muthowwif','created_by' => 2],
        ];

        foreach($data as $val){
            DB::table('kategori_master_sop')->insert($val);
        }
    }
}
