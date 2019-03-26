<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $primaryKey = 'attribute_id';

    public function sizes()
    {
        return $this->belongsToMany('App\Size');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
