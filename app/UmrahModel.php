<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UmrahModel extends Model
{
    protected $table = 'umrah';
    protected $guarded = [];

    public function getDataUmrah()
    {
        $sql = DB::table('umrah as a')
                ->leftJoin('kuisioner_umrah as b','b.umrah_id','=','a.id')
                ->where('a.isdelete',0)
                ->select('a.id','a.tourcode','a.dates','a.created_at','b.url')
                ->get();
        return $sql;
    }
}
