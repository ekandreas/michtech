<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $guarded=[];

    public function items() {
        return $this->hasMany('App\Item');
    }
}
