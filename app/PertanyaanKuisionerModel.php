<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class PertanyaanKuisionerModel extends Model
{
    protected $table = 'pertanyaan_kuisioner';
    protected $guarded = [];

    public function getPertanyaanByKuisionerId($id){

        $sql = DB::table('pertanyaan_kuisioner')->where('kuisioner_id', $id)->get();
        return $sql;
    }

    public function insertPertanyaanKuisionerPembimbing($value, $umrahId){

        $sql = DB::table('pertanyaan_kuisioner_pembimbing')->insertGetId([
                'umrah_id'    => $umrahId,
                'kategori_id' => $value->kategori_id,
                'kuisioner_id' => $value->kuisioner_id,
                'kategori_kompetensi_id' => $value->kategori_kompetensi_id,
                'nomor' => $value->nomor,
                'isi' => $value->isi,
                'required' => $value->required,
                'type' => $value->type,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $sql;
    }
    
}
