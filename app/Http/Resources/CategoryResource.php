<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // var_dump(parent::toArray($request));exit;
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
        return parent::toArray($request);
    }
}