<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
  use HasFactory;

  protected $table = 'categories';
  protected $guarded = [];
  public $timestamps = false;

  public static function mainCategories()
  {
    return self::whereNull('parent_id')->where('active', 1)->get();
  }

  public function children(): HasMany
  {
    return $this->hasMany(Category::class, 'parent_id', 'id')->where('active', 1);
  }
}
