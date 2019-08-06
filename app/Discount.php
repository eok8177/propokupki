<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $table = 'discounts';

//    protected $dates = ['date_start', 'date_end'];

    protected $fillable = ['slug','status', 'date_start', 'date_end'];

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
        $item = $this->hasOne(DiscountTranslate::class)->where('locale', $locale)->first();
        return $item ? $item : new DiscountTranslate();
    }


    public function shops()
    {
        return $this->belongsToMany('App\Shop');
    }

    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] =  Carbon::parse($value);
    }

    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] =  Carbon::parse($value.'23:59:59');
    }

//    public function searchDiscounts ($search, $locale = null)
//    {
//        $locale = $locale ?? app()->getLocale();
//        return $this->hasMany(DiscountTranslate::class)->where('locale', $locale)->where('title', $search);
//    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function searchDiscounts($shops)
    {

        return $this->belongsToMany(Shop::class)
            ->whereIn('shop_id', $shops);


    }
}