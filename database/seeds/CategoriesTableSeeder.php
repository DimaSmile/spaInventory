<?php

use Illuminate\Database\Seeder;
// use App;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Джинсы', 'кофты', 'костюмы', 'джинсовки_кожанки', 'брюки спортивки', 'футболки', 'куртки_зима'];

        foreach ($categories as $categoryName) {
            App\Category::create(['category_name' => $categoryName]);
        }
    }
}
