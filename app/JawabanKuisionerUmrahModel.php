<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JawabanKuisionerUmrahModel extends Model
{
    protected $table = 'jawaban_kuisioner_umrah';
    protected $guarded = [];

    public function getJumlahJawabanByPilihan($pertanyaan_id)
    {
        $sql = "SELECT DISTINCT  b.nomor , b.isi as pilihan , COUNT(c.jawaban) as total_jawaban
                from pertanyaan_kuisioner as a
                join pilihan as b on a.id = b.pertanyaan_id
                left join jawaban_kuisioner_umrah as c on b.id = c.pilihan_id 
                where a.id = $pertanyaan_id
                GROUP by b.nomor , b.isi order by a.nomor asc ";
        return DB::select($sql);
    }

    public function getJumlahJawabanByPilihanByUmrahId($pilihan_id)
    {
        $sql = "SELECT b.isi, COUNT(a.jawaban) as total_jawaban 
                from jawaban_kuisioner_umrah as a 
                join pilihan as b on a.pilihan_id = b.id
                where a.pilihan_id = $pilihan_id GROUP by b.isi";
        return DB::select($sql);
    }

}
