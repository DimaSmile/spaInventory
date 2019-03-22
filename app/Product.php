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

    //получаем все размеры определенного аттрибут сета
    // SELECT attributes.attribute_name, GROUP_CONCAT(sizes.size_name) FROM attributes JOIN sizes_attributes ON attributes.id = sizes_attributes.attribute_id JOIN sizes ON sizes.id =  sizes_attributes.size_id WHERE attributes.id = 1 GROUP BY attributes.id;

    //выборка всех продуктов с определенным сетом размеров, вместе с этими размерами
    // SELECT products.*, attributes.attribute_name, GROUP_CONCAT(sizes.size_name) FROM products JOIN attributes ON products.attribute_id = attributes.id JOIN sizes_attributes ON attributes.id = sizes_attributes.attribute_id JOIN sizes ON sizes.id = sizes_attributes.size_id WHERE attributes.id = 1 GROUP BY products.id


    // fail
    // INSERT INTO products_sizes (product_id, products_attribute_id,  size_id) SELECT 1, 2, 5  WHERE (SELECT products.attribute_id FROM products WHERE products.id = 1) = (SELECT sizes_attributes.attribute_id FROM sizes_attributes JOIN sizes ON sizes_attributes.size_id = sizes.id WHERE sizes.id = 5);
}