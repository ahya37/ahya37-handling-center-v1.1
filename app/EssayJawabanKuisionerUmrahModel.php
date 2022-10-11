<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EssayJawabanKuisionerUmrahModel extends Model
{
    protected $table = 'essay_jawaban_kuisioner_umrah';
    protected $guarded = [];

    public function getDataEssayByRespondenId($id)
    {
        $sql = DB::table('essay_jawaban_kuisioner_umrah as a')
                ->join('pertanyaan_kuisioner as b','b.id','=','a.pertanyaan_id')
                ->select('b.isi','a.essay')
                ->where('a.responden_kuisioner_umrah_id', $id)
                ->get();
        return $sql;
    }
}
