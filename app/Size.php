<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $primaryKey = 'size_id';

    public function attributes()
    {
        return $this->belongsToMany('App\Attribute');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
