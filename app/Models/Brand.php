<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  use HasFactory;

  public $timestamps = false;
  protected $table = "brands";
  protected $guarded = [];

  public function products()
  {
    return $this->hasMany(Product::class);
  }

  public function scopeIsActive($q)
  {
    return $q->where('active', 1);
  }
}
