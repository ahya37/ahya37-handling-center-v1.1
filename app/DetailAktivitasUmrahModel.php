<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailAktivitasUmrahModel extends Model
{
    protected $table = 'detail_aktivitas_umrah';
    protected $guarded = [];
    public $timestamps = false;


    public function getDeleteDetailAktivitasUmrah($id)
    {
        $sql = "DELETE from detail_aktivitas_umrah where master_tugas_id in(
                select a.master_tugas_id from detail_aktivitas_umrah as a  
                join aktivitas_umrah as b on b.id = a.aktivitas_umrah_id
                where a.master_tugas_id = $id and b.status = 'active'
            )";

        return DB::delete($sql);
    }
}
