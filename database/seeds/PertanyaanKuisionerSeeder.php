<?php

use App\PertanyaanKuisionerModel;
use Illuminate\Database\Seeder;

class PertanyaanKuisionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['kusioner_id' => 2,'nomor' => null , 'isi' => 'Identitas Anda','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => null, 'isi' => 'Usia','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 1, 'isi' => 'Bagaimana pelayanan karyawan Percik Tours kepada anda sejak pendaftaran hingga keberangkatan ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 2, 'isi' => 'Bagaimana pelayanan panitia dan handling keberangkatan dari Bandung hingga di Bandara Soekarno Hatta Jakarta ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 3, 'isi' => 'Bagaimana kondisi Pesawat yang anda tumpangi saat perjalanan Jakarta-Jeddah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 4, 'isi' => 'Bagaimana peranan Pembimbing Ibadah sejak keberangkatan hingga tiba di kota Makkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 5, 'isi' => 'Bagaimana peranan team handling Saudi saat pengaturan kamar, pembagian kunci dan distribusi koper begitu tiba di Hotel Makkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 6, 'isi' => 'Bagaimana kondisi bus yang anda tumpangi selama di kota Mekkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 7, 'isi' => 'Apakah perjalanan program sampai saat ini sudah sesuai dengan yang disampaikan saat manasik ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 8, 'isi' => 'Bagaimana kondisi hotel kota Mekkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 9, 'isi' => 'Bagaimana menu makanan di hotel Makkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 10, 'isi' => 'Pendapat anda tentang respon dan penanganan masalah oleh team PERCIK selama di kota Mekkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 11, 'isi' => 'Bagaimana peranan Pembimbing Ibadah selama berada di kota Mekkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 12, 'isi' => 'Bagaimana peranan Muthawwif/Guide lokal selama berada di Makkah ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 13, 'isi' => 'Apakah Pembimbing Ibadah benar-benar telah membimbing dan mengarahkan anda saat pelaksanaan Ibadah Umrah (saat ihlal ihram, thawaf, sa’i, & tahallul ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => 14, 'isi' => 'Sejauh ini apakah keinginan dan harapan anda selama berada di kota Makkah sudah terpenuhi ?','required' => 'Y'],
            ['kusioner_id' => 2,'nomor' => null, 'isi' => 'Silahkan tulis di bawah ini apabila ada saran/ide/kritik yang positif:','required' => 'N'],
        ];
        
        // $data = [
        //     ['kusioner_id' => 1,'nomor' => null , 'isi' => 'Identitas Anda','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => null, 'isi' => 'Usia','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 1, 'isi' => 'Bagaimana peranan Pembimbing Ibadah sejak keberangkatan dari Mekkah hingga tiba di kota Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 2, 'isi' => 'Bagaimana peranan Muthowwif/Guide lokal sejak keberangkatan dari Mekkah hingga tiba di kota Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 3, 'isi' => 'Bagaimana kondisi bus yang anda tumpangi dari Mekkah menuju Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 4, 'isi' => 'Bagaimana peranan team handling saat pengaturan kamar, pembagian kunci dan distribusi koper begitu tiba di Hotel Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 5, 'isi' => 'Bagaimana kondisi hotel selama menginap di kota Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 6, 'isi' => 'Bagaimana menu makanan yang disajikan di Hotel Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 7, 'isi' => 'Bagaimana respon dan penanganan masalah oleh team Percikan Iman selama berada di kota Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 8, 'isi' => 'Bagaimana kondisi bus yang anda tumpangi selama berada di Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 9, 'isi' => 'Bagaimana peranan Pembimbing Ibadah selama berada di kota Madinah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 10, 'isi' => 'Pendapat anda tentang respon dan penanganan masalah oleh team PERCIK selama di kota Mekkah ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 11, 'isi' => 'Apakah keinginan dan harapan anda selama berada di Madinah sudah terpenuhi ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => 12, 'isi' => 'Bagaimana peranan Muthawwif Perempuan saat mengarahkan jamaah menuju ke Raudah ? (Khusus dijawab oleh jama’ah perempuan) ?','required' => 'Y'],
        //     ['kusioner_id' => 1,'nomor' => null, 'isi' => 'Silahkan tulis di bawah ini apabila ada saran/ide/kritik yang positif:','required' => 'N'],
        //     ['kusioner_id' => 1,'nomor' => null, 'isi' => 'Saya Merekomendasikan Saudara/Teman/Relasi saya di bawah ini untuk Umrah bersama Percikan Iman (tuliskan nama dan nomor telpon yang direkomendasikan) :','required' => 'N'],
        // ];

        foreach ($data as $value) {
            PertanyaanKuisionerModel::create($value);
        }
    }
}
