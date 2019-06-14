<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = ['slug','status'];

    public function langs($status = 1)
    {
        return Language::where('status', $status)->get();
    }

    public function forAdmin()
    {
        $shop = [];
        $shop['shop'] = $this;
        foreach ($this->langs() as $item) {
            $trans = $this->translate($item->locale)->first();
            if ($trans) {
                $shop['contents'][$item->locale] = $trans;
            } else {
                $shop['contents'][$item->locale] = new ShopTranslate;
            }
        }
        return $shop;
    }

    public function contents()
    {
        return $this->hasMany('App\ShopTranslate');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasOne(ShopTranslate::class)->where('locale', $locale);
    }

    public function getCategoriesForSelectAttribute()
    {
        return Category::pluck('slug', 'id')->toArray();
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function cities()
    {
        return $this->belongsToMany('App\City');
    }

    public function addresses()
    {
        return $this->belongsToMany('App\Address');
    }

    public function searchShops ($search, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasMany(ShopTranslate::class)->where('locale', $locale)->where('title', $search);
    }

    public function discounts()
    {
        return $this->belongsToMany('App\Discount', 'discount_shop', 'shop_id', 'discount_id');
    }
}