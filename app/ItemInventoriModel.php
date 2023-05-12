<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemInventoriModel extends Model
{
    protected $table   = 'rb_item_inventory';
    protected $guarded = [];
    public $timestamps = false;
}
