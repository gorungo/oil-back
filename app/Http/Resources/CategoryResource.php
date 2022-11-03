<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CategoryResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  Request  $request
   * @return array|Arrayable|JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'mcm_id' => $this->mcm_id,
      'name' => $this->name,
      'active' => $this->active,
      'slug' => $this->slug,
      'children' => $this->whenLoaded('children', CategoryResource::collection($this->children)),
    ];
  }
}
