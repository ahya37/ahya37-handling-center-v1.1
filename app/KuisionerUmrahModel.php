<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KuisionerUmrahModel extends Model
{
    protected $table = 'kuisioner_umrah';
    protected $guarded = [];

    public function getDataKuisionerByUmrahId($id)
    {
        $sql = DB::table('kuisioner_umrah as a')
                ->join('umrah as b','a.umrah_id','=','b.id')
                ->where('b.id', $id)
                ->select('a.id','a.label','a.jumlah_responden','b.tourcode','a.kuisioner_id')
                ->first();
        return $sql;
    }

    public function getRespondenByUmrahId($id)
    {
        $sql = "SELECT COUNT(a.id) as jumlah_responden_umrah from responden_kuisioner_umrah as a
                join kuisioner_umrah as b on a.kuisioner_umrah_id = b.id where umrah_id = $id";
        return collect(DB::select($sql))->first();

    }
}
