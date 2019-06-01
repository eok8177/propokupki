<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['slug','status'];

    public function langs($status = 1)
    {
        return Language::where('status', $status)->get();
    }

    public function forAdmin()
    {
        $product = [];
        $product['product'] = $this;
        foreach ($this->langs() as $item) {
            $trans = $this->translate($item->locale)->first();
            if ($trans) {
                $product['contents'][$item->locale] = $trans;
            } else {
                $product['contents'][$item->locale] = new ProductTranslate;
            }
        }
        return $product;
    }

    public function contents()
    {
        return $this->hasMany('App\ProductTranslate');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasOne(ProductTranslate::class)->where('locale', $locale);
    }

    public function getCategoriesForSelectAttribute()
    {
        return Category::pluck('slug', 'id')->toArray();
    }

    public function shops()
    {
        return $this->belongsToMany('App\Category');
    }

    public function searchProducts ($search, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasMany(ProductTranslate::class)->where('locale', $locale)->where('title', $search);
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}