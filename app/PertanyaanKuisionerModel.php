<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PertanyaanKuisionerModel extends Model
{
    protected $table = 'pertanyaan_kuisioner';
    protected $guarded = [];

    public function getPertanyaanByKuisionerId($id){

        $sql = DB::table('pertanyaan_kuisioner')->where('kuisioner_id', $id)->get();
        return $sql;
    }
}
