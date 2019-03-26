<?php

use App\{Product, Size};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $productIds = Product::pluck('product_id')->toArray();
        $products = DB::table('products')->select(array('product_id', 'attribute_id'))->get();
        var_dump($products);exit;
        $productAttributeIds = Product::pluck('attribute_id')->toArray();
        $sizesIds = Size::pluck('size_id')->toArray();

        $attributes_sizes = [];
        // foreach ($productIds as $productId) {
        //     $products_sizes['product_id'] = $productId;
        //     // if(){
        //     // }else{
        //     // }
        //     $products_sizes['size_id'] = $sizeId;


        //     $products_sizes[] = $products_sizes;
        // }

        DB::table('attribute_size')->insert();
    }
}
