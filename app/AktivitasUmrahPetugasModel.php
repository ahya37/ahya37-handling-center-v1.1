<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AktivitasUmrahPetugasModel extends Model
{
    protected $table = 'aktivitas_umrah_petugas';
    protected $guarded = [];

    public function getHistoryNameTourcodeByPetugasListJudul($user_id)
    {
        $sql = DB::table('aktivitas_umrah_petugas as a')
                ->join('petugas as b','b.id','=','a.petugas_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode', DB::raw('(SELECT SUM(nilai_akhir) FROM detail_aktivitas_umrah_petugas WHERE aktivitas_umrah_petugas_id = a.id) AS nilai_akhir'))
                ->where('b.user_id', $user_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getNameTourcodeByPetugasByAkunPetugas($user_id, $aktitivitas_umrah_petugas_id)
    {
        $sql = DB::table('aktivitas_umrah_petugas as a')
                ->join('petugas as b','b.id','=','a.petugas_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode','c.count_jamaah','a.jumlah_potensial_jamaah_before','a.jumlah_potensial_jamaah_after','a.catatan')
                ->where('b.user_id', $user_id)    
                ->where('a.id', $aktitivitas_umrah_petugas_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getListSopByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah_petugas as a')
                ->select('a.aktivitas_umrah_petugas_id','b.id','b.nama', DB::raw('count(a.master_judul_tugas_id) as total_sop'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah_petugas where master_judul_tugas_id = b.id and  status !="" and  aktivitas_umrah_petugas_id = '.$id.') as total_terisi'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah_petugas where master_judul_tugas_id = b.id and  status = "" and  aktivitas_umrah_petugas_id = '.$id.') as total_null'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah_petugas where master_judul_tugas_id = b.id and  status = "N" and  aktivitas_umrah_petugas_id = '.$id.') as total_N'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah_petugas where master_judul_tugas_id = b.id and  status = "Y" and  aktivitas_umrah_petugas_id = '.$id.') as total_Y'))
                ->join('master_judul_tugas_petugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_petugas_id', $id)
                ->groupBy('b.id','b.nama','a.aktivitas_umrah_petugas_id')
                ->get();
        return $sql;
    }

    public function getListTugasByPembimbingByJudul($aktitivitas_umrah_petugas_id, $id)
    {
        $sql = DB::table('aktivitas_umrah_petugas as a')
                ->join('detail_aktivitas_umrah_petugas as b','b.aktivitas_umrah_petugas_id','=','a.id')
                ->join('petugas as d','d.id','a.petugas_id')
                ->select('b.id','b.nomor_tugas as nomor','b.nama_tugas as nama','b.status','b.created_at','b.validate','b.require_image')
                ->where('b.master_judul_tugas_id', $id)
                ->where('a.id','=', $aktitivitas_umrah_petugas_id)
                ->where('a.status','=','active')
                ->orderBy('b.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getNameTourcodeAndPetugas($id)
    {
        $sql = DB::table('aktivitas_umrah_petugas as a')
                ->join('petugas as b','b.id','=','a.petugas_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('b.nama as petugas','c.tourcode','a.status_tugas','c.master_sop_id','c.master_sop_petugas_id','a.id')
                ->where('a.id', $id)    
                ->first();
        return $sql;
    }

    public function getListTugasByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah_petugas as a')
                ->select('b.id','b.nama')
                ->join('master_judul_tugas_petugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_petugas_id', $id)
                ->groupBy('b.id','b.nama')
                ->get();
        return $sql;
    }

    public function getListTugasByMasterJudulIdByAktitivitasUmrah($aktitivitas_umrah_petugas_id,$id)
    {
        $sql = DB::table('detail_aktivitas_umrah_petugas')
                ->where('master_judul_tugas_id', $id)
                ->where('aktivitas_umrah_petugas_id', $aktitivitas_umrah_petugas_id)
                ->select('id','nomor_tugas','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas','nilai_akhir','validate')
                ->orderBy('nomor_tugas','asc')
                ->get();
        return $sql;
    }

}
