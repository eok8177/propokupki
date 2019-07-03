<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    protected $table = 'products';

    protected $attributes = [
        'status' => 1
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = ['old_price', 'price', 'discount', 'quantity', 'unit', 'image'];

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

    public function getTitleAttribute() {
        $result = ProductTranslate::where('locale', 'ua')->where('product_id', $this->id)->first();
        return $result->title;
    }
}