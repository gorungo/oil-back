<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductCollection;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index(Request $request): ProductCollection
  {
    return new ProductCollection(ProductService::getProducts($request));
  }
  public function brands(Request $request)
  {
    return BrandResource::collection(ProductService::getProductsBrands($request));
  }

}
