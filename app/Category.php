<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'name', 'drop_price', 'retail_price', 'image_url', 'image_name', 'category_id'];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
