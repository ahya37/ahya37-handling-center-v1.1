<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailAktivitasUmrahMuthowwifModel extends Model
{
    protected $table = 'detail_aktivitas_umrah_muthowwif';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
