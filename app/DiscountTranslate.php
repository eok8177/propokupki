<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountTranslate extends Model
{
    protected $table = 'discounts_translations';

    protected $fillable = ['title','locale','status'];

    /**
     * Get parent record.
     */
    public function parent()
    {
        return $this->belongsTo(Discount::class, 'discount_id')->withDefault();
    }


    public static function searchDiscounts ($locale = null, $search, $status)
    {
        $locale = $locale ?? app()->getLocale();

        if ($search) {
            $items = DiscountTranslate::where('title','LIKE', '%'.$search.'%');
        } else {
            $items = DiscountTranslate::where(function ($query) use ($status) {
                $query->WhereHas('parent', function ($query) use ($status) {
                    $query->where('status',$status);
                });
            });
        }

        return $items;
    }
}