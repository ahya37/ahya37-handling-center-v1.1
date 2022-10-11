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


}
