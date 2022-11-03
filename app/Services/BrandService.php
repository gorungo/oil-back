<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class BrandService
{
  public static function getProducts($request)
  {
    return Product::when($request->has('category_id'), function($q) use($request) {
      $q->where('category_id', $request->category_id);
    })
      ->with('brand')
      ->whereNotNull('image')
      ->paginate(60);
  }

}
