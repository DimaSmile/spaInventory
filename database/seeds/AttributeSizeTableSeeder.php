<?php

use App\{Attribute, Size};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributesIds = Attribute::pluck('attribute_id')->toArray();
        $sizesIds = Size::pluck('size_id')->toArray();
        
        $sizesName = Size::pluck('size_name')->toArray();


        $attributes_sizes = [];
        foreach ($sizesIds as $sizeId) {
            $attributes_size = [];
            if(is_numeric($sizesName[$sizeId - 1])){
                $attributes_size['attribute_id'] = $attributesIds[0];
            }else{
                $attributes_size['attribute_id'] = $attributesIds[1];
            }
            $attributes_size['size_id'] = $sizeId;

            $attributes_sizes [] = $attributes_size;
            $attributes_size = null;
        }

        DB::table('attribute_size')->insert($attributes_sizes);
    }
}
