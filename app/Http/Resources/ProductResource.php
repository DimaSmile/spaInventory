<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // echo '<pre>';
        // var_dump(parent::toArray($request));
        $imageUrl = $this->image_url ? 'storage/'. $this->image_url : 'http://via.placeholder.com/600x400';
        
        return [
                // 'id' => $this->id,
                'name' => $this->name,
                'sku' => $this->sku,
                'dropPrice' => $this->drop_price,
                'retailPrice' => $this->retail_price,
                'imageUrl' => $imageUrl,
                'sizes' => [29, 30 , 31, 32, 33, 34, 35, 36, 37],
                // 'image_name' => $this->image_name,
                // 'category_id' => $this->category_id,
                // 'created_at' => $this->created_at,
                // 'updated_at' => $this->updated_at,
            ];
        return parent::toArray($request);
    }
}
