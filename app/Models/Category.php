<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use GlobalStatus;

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ScopeHasProduct($q)
    {
        $q->whereHas('products', function ($product) {
            return $product->active();
        });
    }

    public function ScopeHasFeatureProduct($q)
    {
        $q->whereHas('products', function ($product) {
            return $product->active()->featured();
        });
    }
}
