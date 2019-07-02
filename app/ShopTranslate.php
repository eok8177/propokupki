<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopTranslate extends Model
{
    protected $table = 'shops_translations';

    protected $fillable = ['title','locale','status'];

    public function parent()
    {
        return $this->belongsTo(Shop::class, 'shop_id')->withDefault();
    }

    public static function searchShops ($locale = null, $search, $status, $shops_not_discounts=array())
    {

        $locale = $locale ?? app()->getLocale();

        if ($search) {
            $items = ShopTranslate::where('title','LIKE', '%'.$search.'%')->where('locale', $locale);
        } else {
            if ($status == 3) {
                $items = ShopTranslate::query();
            } elseif($status == 4){
                $items = ShopTranslate::where(function ($query) use ($shops_not_discounts){
                    $query->WhereHas('parent', function ($query) use ($shops_not_discounts){
                        $query->whereIn('shop_id', $shops_not_discounts);
                    });
                });
            } else {
                $items = ShopTranslate::where(function ($query) use ($status) {
                    $query->WhereHas('parent', function ($query) use ($status) {
                        $query->where('status',$status);
                    });
                });
            }

        }

        return $items;
    }
}