<?php

use Illuminate\Database\Seeder;
use App\KategoriPertanyaanKuisionerModel;

class KategoriPertanyaanKuisionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nama' => 'Nilai Pembimbing'],
            ['nama' => 'Nilai Muthawwif'],
            ['nama' => 'Kondisi Hotel'],
            ['nama' => 'Makanan Hotel'],
            ['nama' => 'Transportasi'],
            ['nama' => 'Index Program'],
        ];

        foreach ($data  as $value) {
            KategoriPertanyaanKuisionerModel::create($data);
        }
    }
}
