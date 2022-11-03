<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class CategoryService
{
  public static function getMainCategories($request): Collection
  {
    return Category::mainCategories();
  }

}
