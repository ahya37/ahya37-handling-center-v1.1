<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AktivitasUmrahMuthowwifModel extends Model
{
    protected $table = 'aktivitas_umrah_muthowwif';
    protected $guarded = [];
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public static function getNameTourcodeAndPembimbing($id)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->join('muthowwif as b','b.id','=','a.muthowwif_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('b.nama as muthowwif','c.tourcode','a.status_tugas','a.master_sop_id','a.id')
                ->where('a.id', $id)    
                ->first();

        return $sql;
    }

    public static function getListTugasByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah_muthowwif as a')
                ->select('b.id','b.nama')
                ->join('master_judul_tugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_id', $id)
                ->groupBy('b.id','b.nama')
                ->get();
        return $sql;
    }

    public function getListTugasByMasterJudulIdByAktitivitasUmrah($aktitivitas_umrah_id,$id)
    {
        $sql = DB::table('detail_aktivitas_umrah_muthowwif')
                ->where('master_judul_tugas_id', $id)
                ->where('aktivitas_umrah_id', $aktitivitas_umrah_id)
                ->select('id','nomor_tugas','status','alasan','file','file_doc','file_doc_name','updated_at','nama_tugas','nilai_akhir','validate')
                ->orderBy('nomor_tugas','asc')
                ->get();
        return $sql;
    }

    public function getHistoryNameTourcodeByPembimbingListJudulNew($user_id)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->select('c.id','c.tourcode','a.id as aktivitas_umrah_id')
                ->join('muthowwif as b','b.id','=','a.muthowwif_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->where('b.user_id', $user_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getNameTourcodeByPembimbingByAkunMuthowwif($user_id, $aktitivitas_umrah_id)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->join('muthowwif as b','b.id','=','a.muthowwif_id')
                ->join('umrah as c','c.id','=','a.umrah_id')
                ->select('a.id','c.tourcode','c.count_jamaah','a.jumlah_potensial_jamaah_before','a.jumlah_potensial_jamaah_after')
                ->where('b.user_id', $user_id)    
                ->where('a.id', $aktitivitas_umrah_id)    
                ->where('a.status','active')    
                ->where('a.isdelete',0)    
                ->get();
        return $sql;
    }

    public function getListSopByAktivitasUmrahId($id)
    {
        $sql = DB::table('detail_aktivitas_umrah_muthowwif as a')
                ->select('a.aktivitas_umrah_id','b.id','b.nama', DB::raw('count(a.master_judul_tugas_id) as total_sop'),
                    DB::raw("(select count(*) from detail_aktivitas_umrah_muthowwif where master_judul_tugas_id = b.id and  status !='' and  aktivitas_umrah_id = '$id') as total_terisi"),
                    DB::raw("(select count(*) from detail_aktivitas_umrah_muthowwif where master_judul_tugas_id = b.id and  status = '' and  aktivitas_umrah_id = '$id') as total_null"),
                    DB::raw("(select count(*) from detail_aktivitas_umrah_muthowwif where master_judul_tugas_id = b.id and  status = 'N' and  aktivitas_umrah_id = '$id') as total_N"),
                    DB::raw("(select count(*) from detail_aktivitas_umrah_muthowwif where master_judul_tugas_id = b.id and  status = 'Y' and  aktivitas_umrah_id = '$id') as total_Y"))
                ->join('master_judul_tugas as b', 'a.master_judul_tugas_id','=','b.id')
                ->where('a.aktivitas_umrah_id', $id)
                ->groupBy('b.id','b.nama','a.aktivitas_umrah_id')
                ->get();
        return $sql;
    }

    public function getListTugasMuthowwifByJudul($aktitivitas_umrah_id, $id)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->join('detail_aktivitas_umrah_muthowwif as b','b.aktivitas_umrah_id','=','a.id')
                ->join('muthowwif as d','d.id','=','a.muthowwif_id')
                ->select('b.id','b.nomor_tugas as nomor','b.nama_tugas as nama','b.status','b.created_at','b.validate','b.require_image')
                ->where('b.master_judul_tugas_id', $id)
                ->where('a.id','=', "$aktitivitas_umrah_id")
                ->where('a.status','=','active')
                ->orderBy('b.nomor_tugas','asc')
                ->get();
        return $sql;
    }
	
	public static function getDetailSopNByAktivitasUmrah($id)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->join('detail_aktivitas_umrah_muthowwif as b','b.aktivitas_umrah_id','=','a.id')
                ->join('master_judul_tugas as d','b.master_judul_tugas_id','=','d.id')
                ->select('a.id','d.nomor','d.nama','d.id as id_judul')
                ->where('a.id', $id)
                ->where('b.status','N')
                ->distinct()
                ->get();

        return $sql;
    }
	
	public static function getListSopByStatus($id,$status,$id_judul)
    {
        $sql = DB::table('detail_aktivitas_umrah_muthowwif as a')
                ->select('a.id','a.nomor_tugas','a.nama_tugas','a.nilai_akhir','a.updated_at','b.nilai_point','a.alasan')
                ->leftJoin('master_tugas as b','a.master_tugas_id','=','b.id')
                ->where('a.master_judul_tugas_id', $id_judul)
                ->where('a.status',$status)
                ->where('a.aktivitas_umrah_id', $id)
                ->get();

        return $sql;
    }
}
