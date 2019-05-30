<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountTranslate extends Model
{
    protected $table = 'discounts_translations';

    protected $fillable = ['title','locale','status'];
}