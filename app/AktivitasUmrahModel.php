<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AktivitasUmrahModel extends Model
{
    protected $table = 'aktivitas_umrah';
    protected $guarded = [];

    public function getDataAktivitasUmrah()
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','b.nama as pembimbing','c.tourcode', 'a.status','c.dates','c.id as umrah_id')    
                ->get();
        return $sql;
    }

    public function getDataJadwalUmrahActive()
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','b.nama as pembimbing','c.tourcode')
                ->get();
        return $sql;
    }

    public function getNameTourcodeAndPembimbing($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('b.nama as pembimbing','c.tourcode','a.status_tugas','c.master_sop_id','c.asisten_master_sop_id','a.id')
                ->where('a.id', $id)    
                ->first();
        return $sql;
    }

    public function getCekAndPerbaruiTugas($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->rightJoin('master_sop as d','c.asisten_master_sop_id','=','d.id')
                ->select('d.id')
                ->where('a.id', $id)    
                ->first();
        return $sql;
    }

    public function getDetailActivitas($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama','a.validate')
                ->where('a.aktivitas_umrah_id', $id)
                ->orderBy('a.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getDetailActivitasStatusY($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama')
                ->where('a.aktivitas_umrah_id', $id)
                ->where('a.status', 'Y')
                ->orderBy('a.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getDetailActivitasStatusN($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama')
                ->where('a.aktivitas_umrah_id', $id)
                ->where('a.status', 'N')
                ->orderBy('a.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getDetailActivitasStatusNull($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama')
                ->where('a.aktivitas_umrah_id', $id)
                ->where('a.status', '')
                ->orderBy('a.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getListTugasByPembimbing($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('detail_aktivitas_umrah as b','b.aktivitas_umrah_id','=','a.id')
                ->join('pembimbing as d','d.id','a.pembimbing_id')
                ->select('b.id','b.nomor_tugas as nomor','b.nama_tugas as nama','b.status','b.created_at','b.validate','b.require_image')
                ->where('b.master_judul_tugas_id', $id)
                ->where('a.status','=','active')
                ->orderBy('b.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getListTugasByPembimbingByJudul($aktitivitas_umrah_id, $id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('detail_aktivitas_umrah as b','b.aktivitas_umrah_id','=','a.id')
                ->join('pembimbing as d','d.id','a.pembimbing_id')
                ->select('b.id','b.nomor_tugas as nomor','b.nama_tugas as nama','b.status','b.created_at','b.validate','b.require_image')
                ->where('b.master_judul_tugas_id', $id)
                ->where('a.id','=', $aktitivitas_umrah_id)
                ->where('a.status','=','active')
                ->orderBy('b.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getListHistoryTugasByPembimbing($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('detail_aktivitas_umrah as b','b.aktivitas_umrah_id','=','a.id')
                ->join('pembimbing as d','d.id','a.pembimbing_id')
                ->select('b.id','b.nomor_tugas as nomor','b.nama_tugas as nama','b.status','b.updated_at','b.alasan','b.validate')
                ->where('a.id', $id)
                ->where('a.status','=','finish')
                ->orderBy('b.nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getNameTourcodeByPembimbing($user_id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode','c.count_jamaah','a.jumlah_potensial_jamaah_before','a.jumlah_potensial_jamaah_after')
                ->where('b.user_id', $user_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }


    public function getNameTourcodeByPembimbingByAkunPembimbing($user_id, $aktitivitas_umrah_id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode','c.count_jamaah','a.jumlah_potensial_jamaah_before','a.jumlah_potensial_jamaah_after')
                ->where('b.user_id', $user_id)    
                ->where('a.id', $aktitivitas_umrah_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    

    public function getNameTourcodeByAktivitasUmrahId($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('umrah as b','b.id','=','a.umrah_id')
                ->select('a.id','b.tourcode')
                ->where('a.id', $id)     
                ->first();
        return $sql;
    }

    public function getHistoryNameTourcodeByPembimbing($user_id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode', DB::raw('(SELECT SUM(nilai_akhir) FROM detail_aktivitas_umrah WHERE aktivitas_umrah_id = a.id) AS nilai_akhir'))
                ->where('b.user_id', $user_id)    
                ->where('a.status','finish')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getHistoryNameTourcodeByPembimbingListJudul($user_id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
				->join('kuisioner_umrah as d','c.id','=','d.umrah_id')
                ->select('d.url','a.id','c.tourcode','c.count_jamaah',
                    DB::raw('(SELECT SUM(nilai_akhir) FROM detail_aktivitas_umrah WHERE aktivitas_umrah_id = a.id) AS nilai_akhir'),
                    DB::raw('(select count(distinct(nama)) from responden_kuisioner_umrah where kuisioner_umrah_id = d.id) as total_responden'))
                ->where('b.user_id', $user_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getAllHistory()
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode')
                ->where('a.status','finish')    
                ->get();
        return $sql;
    }

    public function getDataGrafikAktivitasUmrah($id)
    {
        $sql = "SELECT b.id as aktivitas_umrah_id, a.nama, 
                COUNT(DISTINCT IF(c.status = '',c.id,NULL)) as total_null,
                COUNT(DISTINCT IF(c.status = 'Y',c.id,NULL)) as total_Y,
                COUNT(DISTINCT IF(c.status = 'N',c.id,NULL)) as total_N,
                COUNT(DISTINCT IF(c.status != '',c.id,NULL)) as total_terisi,
                COUNT(DISTINCT(c.id)) as total_tugas
                from pembimbing as a
                join aktivitas_umrah as b on a.id = b.pembimbing_id
                join detail_aktivitas_umrah as c on b.id = c.aktivitas_umrah_id 
                where b.status = 'active' and b.umrah_id = $id
                group by a.nama, b.id order by a.nama asc
                ";
        return DB::select($sql);
    }

    public function getPembimbingByUmrahId($id)
    {
        $sql = DB::table('aktivitas_umrah as a')
                ->join('pembimbing as b','a.pembimbing_id','=','b.id')
                ->select('b.nama','a.id as aktivitas_umrah_id')
                ->where('a.umrah_id', $id)
                ->get();
        return $sql;
    }

    public function getListTugasByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('b.id','b.nama')
                ->join('master_judul_tugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_id', $id)
                ->groupBy('b.id','b.nama')
                ->get();
        return $sql;
    }

    public function getListSopByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah as a')
                ->select('a.aktivitas_umrah_id','b.id','b.nama', DB::raw('count(a.master_judul_tugas_id) as total_sop'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah where master_judul_tugas_id = b.id and  status !="" and  aktivitas_umrah_id = '.$id.') as total_terisi'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah where master_judul_tugas_id = b.id and  status = "" and  aktivitas_umrah_id = '.$id.') as total_null'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah where master_judul_tugas_id = b.id and  status = "N" and  aktivitas_umrah_id = '.$id.') as total_N'),
                    DB::raw('(select count(*) from detail_aktivitas_umrah where master_judul_tugas_id = b.id and  status = "Y" and  aktivitas_umrah_id = '.$id.') as total_Y'))
                ->join('master_judul_tugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_id', $id)
                ->groupBy('b.id','b.nama','a.aktivitas_umrah_id')
                ->get();
        return $sql;
    }

    public function getListTugasByMasterJudulId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah')
                ->where('master_judul_tugas_id', $id)
                ->select('nomor_tugas','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas','nilai_akhir')
                ->get();
        return $sql;
    }

    public function getListTugasByMasterJudulIdByAktitivitasUmrah($aktitivitas_umrah_id,$id)
    {
        $sql = DB::table('detail_aktivitas_umrah')
                ->where('master_judul_tugas_id', $id)
                ->where('aktivitas_umrah_id', $aktitivitas_umrah_id)
                ->select('id','nomor_tugas','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas','nilai_akhir','validate')
                ->orderBy('nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getListTugasByMasterJudulIdInChart($id)
    {
        // $sql = DB::table('detail_aktivitas_umrah as a')
        //         ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama');

        $sql = DB::table('detail_aktivitas_umrah')
                ->where('master_judul_tugas_id', $id)
                ->select('id','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas as nama','nomor_tugas as nomor');
        return $sql;
    }

    public function getListTugasByMasterJudulIdInChartNew($aktivitasId,$id)
    {
        // $sql = DB::table('detail_aktivitas_umrah as a')
        //         ->select('a.id','a.status','a.alasan','a.updated_at','a.nomor_tugas as nomor','a.nama_tugas as nama');

        $sql = DB::table('detail_aktivitas_umrah')
                ->where('master_judul_tugas_id', $id)
                ->where('aktivitas_umrah_id', $aktivitasId)
                ->select('id','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas as nama','nomor_tugas as nomor');
        return $sql;
    }

}
