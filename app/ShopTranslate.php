<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopTranslate extends Model
{
    protected $table = 'shops_translations';

    protected $fillable = ['title','locale','status'];
}