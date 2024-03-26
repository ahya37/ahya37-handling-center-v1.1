<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\KategoriMasterSop;
use DB;

class SopModel extends Model
{
    protected $table = 'master_sop';
    protected $guarded = [];

    public function kategorisop()
    {
        return $this->belongsTo(KategoriMasterSop::class,'kategori_master_sop_id');
    }

    public static function getDataSopMuthowwif()
    {
        $sql = DB::table('master_sop as a')
            ->select('a.id','a.name')
            ->join('kategori_master_sop as b','a.kategori_master_sop_id','=','b.id')
            ->where('b.name','Muthowwif')
            ->get();

        return $sql;
    } 
}
