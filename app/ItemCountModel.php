<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCountModel extends Model
{
    protected $table   = 'rb_item_count';
    protected $guarded = [];
    public $timestamps = false;
}
