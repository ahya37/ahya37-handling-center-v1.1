<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembimbingModel extends Model
{
    protected $table = 'pembimbing';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
