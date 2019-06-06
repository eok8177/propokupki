<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = ['slug','status'];

    public function langs($status = 1)
    {
        return Language::where('status', $status)->get();
    }

    public function forAdmin()
    {
        $discount = [];
        $discount['discount'] = $this;
        foreach ($this->langs() as $item) {
            $trans = $this->translate($item->locale)->first();
            if ($trans) {
                $discount['contents'][$item->locale] = $trans;
            } else {
                $discount['contents'][$item->locale] = new DiscountTranslate;
            }
        }
        return $discount;
    }

    public function contents()
    {
        return $this->hasMany('App\DiscountTranslate');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
//        dd($locale);
        return $this->hasOne(DiscountTranslate::class)->where('locale', $locale);
    }

    public function getCategoriesForSelectAttribute()
    {
//        dd(Category::pluck('slug', 'id')->toArray());
        return Category::pluck('slug', 'id')->toArray();
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function sities()
    {
        return $this->belongsToMany('App\City');
    }

    public function addresses()
    {
        return $this->belongsToMany('App\Address');
    }

    public function searchDiscounts ($search, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasMany(DiscountTranslate::class)->where('locale', $locale)->where('title', $search);
    }
}