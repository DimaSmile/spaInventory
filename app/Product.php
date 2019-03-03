<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'drop_price', 'retail_price', 'image_url', 'image_name', 'category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
