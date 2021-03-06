<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTranslate extends Model
{
    protected $table = 'products_translations';

    protected $fillable = ['title','locale','status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}