<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Request;

class CategoryController extends Controller
{
  public function main(Request $request): AnonymousResourceCollection
  {
    return CategoryResource::collection(CategoryService::getMainCategories($request));
  }
}
