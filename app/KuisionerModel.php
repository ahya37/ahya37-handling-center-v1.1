<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class KuisionerModel extends Model
{
    protected $table = 'kuisioner';
    protected $guarded = [];

    public function getKuisionerByAktivitasUmrah($umrah_id, $aktivitas_umrah_id){

        $sql = DB::table('aktivitas_umrah as a')
                ->join('kuisioner_umrah as b','b.umrah_id','=','a.umrah_id')
                ->join('pembimbing as c','a.pembimbing_id','=','c.id')
                ->join('umrah as d','a.umrah_id','=','d.id')
                ->select('a.id as aktivitas_umrah_id', 'b.id as kuisioner_umrah_id','b.label',
                         'a.pembimbing_id', 'a.status_tugas','c.nama as pembimbing','d.tourcode','d.count_jamaah','b.jumlah_responden')
                ->where('b.umrah_id', $umrah_id)
                ->where('a.id', $aktivitas_umrah_id)
                ->first();

        return $sql;

    }

    public function getKuisionerByUmrahId($umrah_id){

        $sql = DB::table('kuisioner_umrah as a')
            ->join('umrah as b','a.umrah_id','=','b.id')
            ->join('kuisioner as c','c.id','=','a.kuisioner_id')
            ->select('b.id as umrah_id','c.nama as kuisioner','a.label','a.id as kuisioner_umrah_id','a.jumlah_responden','b.tourcode','b.count_jamaah')
            ->where('b.id', $umrah_id)
            ->first();

        return $sql;

    }

    // public function getPertanyaanByUmrahIdAndAktivitasUmrahId($kuisioner_umrah_id, $aktivitas_umrah_id){

    //     $sql = DB::table('jawaban_kuisioner_umrah as a')
    //             ->join('pertanyaan_kuisioner as b','a.pertanyaan_id','=','b.id')
    //             ->join('aktivitas_umrah as c','a.umrah_id','=','c.umrah_id')
    //             ->select('b.id','b.isi','b.nomor')
    //             ->where('a.kuisioner_umrah_id', $kuisioner_umrah_id)
    //             ->where('c.id', $aktivitas_umrah_id)
    //             ->groupBy('b.id','b.isi','b.nomor')
    //             ->orderBy('b.nomor','asc')
    //             ->get();

    //     return $sql;
    // }

    public function getPertanyaanByUmrahIdAndAktivitasUmrahId($kuisioner_umrah_id){

        $sql = DB::table('jawaban_kuisioner_umrah as a')
                ->join('pertanyaan_kuisioner as b','a.pertanyaan_id','=','b.id')
                ->select('b.id','b.isi','b.nomor')
                ->where('a.kuisioner_umrah_id', $kuisioner_umrah_id)
                ->groupBy('b.id','b.isi','b.nomor')
                ->orderBy('b.nomor','asc')
                ->get();

        return $sql;
    }

    // public function getPertanyaanEssayByUmrahIdAndAktivitasUmrahId($kuisioner_umrah_id, $aktivitas_umrah_id){

    //     $sql = DB::table('essay_jawaban_kuisioner_umrah as a')
    //             ->join('pertanyaan_kuisioner as b','a.pertanyaan_id','=','b.id')
    //             ->join('aktivitas_umrah as c','a.umrah_id','=','c.umrah_id')
    //             ->select('b.id','b.isi','b.nomor')
    //             ->where('a.kuisioner_umrah_id', $kuisioner_umrah_id)
    //             ->where('c.id', $aktivitas_umrah_id)
    //             ->groupBy('b.id','b.isi','b.nomor')
    //             ->orderBy('b.nomor','asc')
    //             ->get();

    //     return $sql;
    // }

    public function getPertanyaanEssayByUmrahIdAndAktivitasUmrahId($kuisioner_umrah_id){

        $sql = DB::table('essay_jawaban_kuisioner_umrah as a')
                ->join('pertanyaan_kuisioner as b','a.pertanyaan_id','=','b.id')
                ->select('b.id','b.isi','b.nomor')
                ->where('a.kuisioner_umrah_id', $kuisioner_umrah_id)
                ->groupBy('b.id','b.isi','b.nomor')
                ->orderBy('b.nomor','asc')
                ->get();

        return $sql;
    }

    // public function getJumlahJawaban($umrah_id, $aktivitas_umrah_id, $pertanyaan_id){

    //     $sql = DB::table('jawaban_kuisioner_umrah as a')
    //             ->select('a.umrah_id','a.pertanyaan_id','a.pilihan_id','b.isi as jawaban',
    //                 DB::raw('count(a.jawaban) as jml_jawaban'))
    //             ->join('pilihan as b','a.pilihan_id','=','b.id')
    //             ->join('aktivitas_umrah as c','a.umrah_id','=','c.umrah_id')
    //             ->where('a.umrah_id', $umrah_id)
    //             ->where('c.id', $aktivitas_umrah_id)
    //             ->where('a.pertanyaan_id', $pertanyaan_id)
    //             ->groupBy('a.umrah_id','a.pertanyaan_id','a.pilihan_id','b.isi')
    //             ->distinct()
    //             ->get();

    //     return $sql;
    // }

    public function getJumlahJawaban($umrah_id, $pertanyaan_id){

        $sql = DB::table('jawaban_kuisioner_umrah as a')
                ->select('a.umrah_id','a.pertanyaan_id','a.pilihan_id','b.isi as jawaban',
                    DB::raw('count(a.jawaban) as jml_jawaban'))
                ->join('pilihan as b','a.pilihan_id','=','b.id')
                ->where('a.umrah_id', $umrah_id)
                ->where('a.pertanyaan_id', $pertanyaan_id)
                ->groupBy('a.umrah_id','a.pertanyaan_id','a.pilihan_id','b.isi')
                ->distinct()
                ->get();

        return $sql;
    }

    // public function getJumlahJawabanEssay($umrah_id, $aktivitas_umrah_id, $pertanyaan_id){

    //     $sql = DB::table('essay_jawaban_kuisioner_umrah as a')
    //             ->join('kuisioner_umrah as b', 'b.id', '=', 'a.kuisioner_umrah_id')
    //             ->join('aktivitas_umrah as c', 'c.umrah_id','=', 'b.umrah_id')
    //             ->select('a.kuisioner_umrah_id' , 'a.umrah_id' , 'a.pertanyaan_id','c.id as aktivitas_umrah_id','a.essay')
    //             ->where('c.id', $aktivitas_umrah_id)
    //             ->where('a.pertanyaan_id', $pertanyaan_id)
    //             ->where('a.umrah_id', $umrah_id)
    //             ->whereNotNull('a.essay')
    //             ->distinct()
    //             ->get();

    //     return $sql;
    // }

    public function getJumlahJawabanEssay($umrah_id, $pertanyaan_id){

        $sql = DB::table('essay_jawaban_kuisioner_umrah as a')
                ->join('kuisioner_umrah as b', 'b.id', '=', 'a.kuisioner_umrah_id')
                ->select('a.kuisioner_umrah_id' , 'a.umrah_id' , 'a.pertanyaan_id','a.essay')
                ->where('a.pertanyaan_id', $pertanyaan_id)
                ->where('a.umrah_id', $umrah_id)
                ->whereNotNull('a.essay')
                ->distinct()
                ->get();

        return $sql;
    }
}
