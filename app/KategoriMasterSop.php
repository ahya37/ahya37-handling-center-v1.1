<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriMasterSop extends Model
{
    protected $table = 'kategori_master_sop';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
}
