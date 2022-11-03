<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        Log::info($this->collection);
        return [
            'products' => ProductResource::collection($this->collection),
            'total' => $this->total(),
        ];

    }
}
