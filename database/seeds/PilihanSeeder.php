<?php

use App\PilihanModel;
use Illuminate\Database\Seeder;

class PilihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            // 1
            ['pertanyaan_id' => 19, 'nomor' => 1, 'isi' => 'Memuaskan'],
            ['pertanyaan_id' => 19, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 19, 'nomor' => 3, 'isi' => 'Kurang Memuaskan'],
            ['pertanyaan_id' => 19, 'nomor' => 4, 'isi' => 'Mengecewakan'],

            // 2
            ['pertanyaan_id' => 20, 'nomor' => 1, 'isi' => 'Memuaskan'],
            ['pertanyaan_id' => 20, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 20, 'nomor' => 4, 'isi' => 'Kurang Memuaskan'],
            ['pertanyaan_id' => 20, 'nomor' => 4, 'isi' => 'Mengecewakan'],

            // 3
            ['pertanyaan_id' => 21, 'nomor' => 1, 'isi' => 'Nyaman'],
            ['pertanyaan_id' => 21, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 21, 'nomor' => 3, 'isi' => 'Kurang nyaman'],
            ['pertanyaan_id' => 21, 'nomor' => 4, 'isi' => 'Tidak nyaman'],

            // 4
            ['pertanyaan_id' => 22, 'nomor' => 1, 'isi' => 'Memuaskan'],
            ['pertanyaan_id' => 22, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 22, 'nomor' => 3, 'isi' => 'Kurang Memuaskan'],
            ['pertanyaan_id' => 22, 'nomor' => 4, 'isi' => 'Mengecewakan'],

            //5
            ['pertanyaan_id' => 23, 'nomor' => 1, 'isi' => 'Memuaskan'],
            ['pertanyaan_id' => 23, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 23, 'nomor' => 3, 'isi' => 'Kurang Nyaman'],
            ['pertanyaan_id' => 23, 'nomor' => 4, 'isi' => 'Tidak nyaman'],

            //6
            ['pertanyaan_id' => 24, 'nomor' => 1, 'isi' => 'Nyaman'],
            ['pertanyaan_id' => 24, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 24, 'nomor' => 3, 'isi' => 'Kurang nyaman'],
            ['pertanyaan_id' => 24, 'nomor' => 4, 'isi' => 'Tidak nyaman'],

            //7
            ['pertanyaan_id' => 25, 'nomor' => 1, 'isi' => 'Ya'],
            ['pertanyaan_id' => 25, 'nomor' => 2, 'isi' => 'Tidak semuanya'],
            ['pertanyaan_id' => 25, 'nomor' => 3, 'isi' => 'Tidak sesuai'],

            //8
            ['pertanyaan_id' => 26, 'nomor' => 1, 'isi' => 'Nyaman'],
            ['pertanyaan_id' => 26, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 26, 'nomor' => 3, 'isi' => 'Kurang Nyaman'],
            ['pertanyaan_id' => 26, 'nomor' => 4, 'isi' => 'Tidak nyaman'],

            //9
            ['pertanyaan_id' => 27, 'nomor' => 1, 'isi' => 'Enak'],
            ['pertanyaan_id' => 27, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 27, 'nomor' => 3, 'isi' => 'Kurang enak'],
            ['pertanyaan_id' => 27, 'nomor' => 4, 'isi' => 'Tidak enak'],

            //10
            ['pertanyaan_id' => 28, 'nomor' => 1, 'isi' => 'Cepat'],
            ['pertanyaan_id' => 28, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 28, 'nomor' => 3, 'isi' => 'Kurang cepat'],
            ['pertanyaan_id' => 28, 'nomor' => 4, 'isi' => 'Lambat'],

            //11
            ['pertanyaan_id' => 29, 'nomor' => 1, 'isi' => 'Ya'],
            ['pertanyaan_id' => 29, 'nomor' => 2, 'isi' => 'Kurang'],
            ['pertanyaan_id' => 29, 'nomor' => 3, 'isi' => 'Tidak'],

            //12
            ['pertanyaan_id' => 30, 'nomor' => 1, 'isi' => 'Memuaskan'],
            ['pertanyaan_id' => 30, 'nomor' => 2, 'isi' => 'Biasa saja'],
            ['pertanyaan_id' => 30, 'nomor' => 3, 'isi' => 'Kurang memuaskan'],
            ['pertanyaan_id' => 30, 'nomor' => 4, 'isi' => 'Mengecewakan'],

            //13
            ['pertanyaan_id' => 31, 'nomor' => 1, 'isi' => 'Ya'],
            ['pertanyaan_id' => 31, 'nomor' => 3, 'isi' => 'Kurang'],
            ['pertanyaan_id' => 31, 'nomor' => 4, 'isi' => 'Tidak'],

            //14
            ['pertanyaan_id' => 32, 'nomor' => 1, 'isi' => 'Ya'],
            ['pertanyaan_id' => 32, 'nomor' => 3, 'isi' => 'Kurang'],
            ['pertanyaan_id' => 32, 'nomor' => 4, 'isi' => 'Tidak'],

            ['pertanyaan_id' => 33, 'nomor' => 1, 'isi' => ''],

        ];

        foreach ($data as $value) {
            PilihanModel::create($value);
        }
    }
}
