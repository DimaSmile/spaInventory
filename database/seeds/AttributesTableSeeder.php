<?php

use Illuminate\Database\Seeder;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = ['Числ_размеры', 'Букв_размеры'];

        foreach ($attributes as $attributeName) {
            App\Attribute::create(['attribute_name' => $attributeName]);
        }
    }
}
