<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    protected $table   = 'rb_item';
    protected $guarded = [];
    public $timestamps = false;
}
