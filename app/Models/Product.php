<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = [];

    public function brand()
    {
      return $this->belongsTo(Brand::class);
    }

    public function scopeIsActive($q)
    {
      return $q->where('active', 1);
    }
}
