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
}