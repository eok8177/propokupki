<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['slug', 'price', 'discount', 'quantity', 'unit'];

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
        $locale = $locale ?: app()->getLocale();

        foreach ($this->translations as $translation) {
            if ($translation->attributes['locale'] === $locale) {
                return $translation;
            }
        }

        return null;
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslate::class);
    }


    public function discounts()
    {
        return $this->belongsToMany('App\Discount', 'discount_product', 'product_id', 'discount_id');
    }
}