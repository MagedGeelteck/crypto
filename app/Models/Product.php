<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use GlobalStatus;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function featureBadge(): Attribute
    {
        return new Attribute(function () {

            $html = '';
            if ($this->featured == Status::ENABLE) {
                $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans('No') . '</span>';
            }
            return $html;
        });
    }

    public function fileBadge(): Attribute
    {
        return new Attribute(function () {

            $html = '';
            if ($this->product_file) {
                $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans('No') . '</span>';
            }
            return $html;
        });
    }

    public function ScopeFeatured($q)
    {
        $q->where('featured',Status::ENABLE);
    }

}
