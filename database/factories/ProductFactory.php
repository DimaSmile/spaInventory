<?php
// use App\Category;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $categories = App\Category::pluck('category_id')->toArray();
    $attributes = App\Attribute::pluck('attribute_id')->toArray();
    // var_dump($attributes);exit;
    return [
        'product_name' => $faker->name,
        'sku' => $faker->unique()->word,
        'attribute_id' => $faker->randomElement($attributes),
        'drop_price' => $faker->randomNumber(2),
        'retail_price' => $faker->randomNumber(2),
        'image_url' => 'http://via.placeholder.com/600x400',
        'image_name' => $faker->name,
        'category_id' => $faker->randomElement($categories)
    ];
});
