<?php
// use App\Category;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $categories = App\Category::pluck('id')->toArray();
    return [
        'name' => $faker->name,
        'sku' => $faker->unique()->word,
        'drop_price' => $faker->randomNumber(2),
        'retail_price' => $faker->randomNumber(2),
        'image_url' => 'http://via.placeholder.com/600x400',
        'image_name' => $faker->name,
        'category_id' => $faker->randomElement($categories)
    ];
});
