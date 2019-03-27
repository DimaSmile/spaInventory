<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AttributeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // var_dump( parent::toArray($request));exit;
        // return[
        //     // 'id' => $this->attribute_id,
        //     'name' => $this->attribute_name,
        //     'size' => $this->size_name
        // ];
    }
}
