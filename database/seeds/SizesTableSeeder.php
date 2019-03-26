<?php

use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = ['29', '30', '31', '32', '33', '34', '35', '36', '37', 's', 'm', 'l','xl', 'xxl'];

        foreach ($sizes as $sizeName) {
            App\Size::create(['size_name' => $sizeName]);
        }
    }
}
