<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductService
{
  public static function getProducts($request)
  {
    return Product::when($request->has('category_id'), function($q) use($request) {
      $q->where('category_id', $request->category_id);
    })->when($request->has('brand_id'), function($q) use($request) {
        $q->where('brand_id', $request->brand_id);
      })
      ->isActive()
      ->whereNotNull('image')
      ->paginate(60);
  }

  public static function getProductsBrands($request)
  {
    return Brand::whereHas('products', function($query) use ($request){
      $query->when($request->has('category_id'), function($q) use ($request) {
        $q->where('category_id', $request->category_id);
      })
        ->isActive()
        ->whereNotNull('image');
    })
      ->isActive()
      ->orderBy('sort', 'desc')
      ->get();
  }

  public static function importFromMcmProducts()
  {
    $categoryId = 12;

    $products = self::getFromMcmProductsPage2(1, $categoryId);
    foreach ($products as $product){
      $product->category_id = $categoryId;
      self::updateProduct($product);
    }

//    if($total = self::getFromMcmProductsTotal()){
//      for($i = 1; $i < (int) $total / 60; $i++) {
//        self::updateProduct(self::getFromMcmProductsPage($i));
//      }
//    }
  }

  private static function updateProduct($data)
  {
    $image = null;
    $category = Category::where('mcm_id', $data->category_id)->first();
    $brand = Brand::where('mcm_id', $data->brand_id)->first();

    if(!$brand){
      Brand::create(['mcm_id' => $data->brand_id, 'name' => 'новый бренд']);
      $brand = Brand::where('mcm_id', $data->brand_id)->first();
    }

    if($data->images && count($data->images)){
      $image = $data->images[0]->image;
    }

    if($category && $data->cost > 0){
      if($product = Product::where(
        [
          'article' => $data->article,
          'brand_id' => $brand->id,
        ]
      )->first()){
        // update product
        $product->update([
          'name' => $data->name,
          'cost' => $data->cost,
          'image' => $image,
          'article' => $data->article,
          'category_id' => $category->id,
          'brand_id' => $brand->id,
          'updated_at' => now(),
        ]);
        echo 'updating ';
      } else {
        // create product
        Product::create([
          'mcm_id' => $data?->id,
          'active' => 0,
          'name' => $data->name,
          'cost' => $data->cost,
          'mrc' => $data->cost,
          'image' => $image,
          'article' => $data->article,
          'category_id' => $category->id,
          'brand_id' => $brand->id,
        ]);
        echo 'creating ';
      }
      print_r($data);
    }
  }

  private static function getFromMcmProductsTotal()
  {
    $response = Http::get('https://mcmauto.ru/api/mcmauto/selfProducts', [
      'page' => 1,
    ])->object();

    return  $response->total ?? null;
  }

  private static function getFromMcmProductsPage(int $page = 1)
  {
    $response = Http::get('https://mcmauto.ru/api/mcmauto/selfProducts', [
      'page' => $page,
    ])->object();
    return  $response->products ?? null;
  }

  private static function getFromMcmProductsPage2(int $page = 1, $categoryId)
  {
    $response = Http::post('https://mcmauto.ru/api/mcmauto/products/all', [
      'page' => $page,
      'itemsPerPage' => 100,
      'category_id' => $categoryId,
      'orderBy' => 'name',
      'orderDir' => 'asc',
      'categoryParams' => [
        'actions' => 0,
        'brands' => [
          ['id' => 12],
        ],
        'filters' => [],
        'filtersNumber' => [],
        'price' => []

      ]
    ])->object();
    return  $response->products ?? null;
  }


}
