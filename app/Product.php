<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'drop_price', 'retail_price', 'image_url', 'image_name', 'category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
        // SELECT p.prod_name, GROUP_CONCAT(s.size_name) FROM products p JOIN attributes_sizes asz ON asz.attr_id = p.attr_id JOIN sizes s ON asz.size_id = s.size_id GROUP BY p.prod_id
    }

    /* mutator - преобразовует атрибут преобразователь будет автоматически вызван, когда мы попытаемся установить значение атрибута `sku` */
    public function setSkuAttribute($value)
    {
        if ( empty($value) ) { // will check for empty string, null values
            $this->attributes['sku'] = NULL;
        } else {
            $this->attributes['sku'] = $value;
        }
    }
}