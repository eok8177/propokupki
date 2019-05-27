<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['slug','code','status'];

    public function langs($status = 1)
    {
        return Language::where('status', $status)->get();
    }

    public function forAdmin()
    {
        $city = [];
        $city['city'] = $this;
        foreach ($this->langs() as $item) {
            $trans = $this->translate($item->locale)->first();
            if ($trans) {
                $city['contents'][$item->locale] = $trans;
            } else {
                $city['contents'][$item->locale] = new CityTranslate;
            }
        }
        return $city;
    }

    public function contents()
    {
        return $this->hasMany('App\CategoryTranslate');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
//        dd($locale);
        return $this->hasOne(CategoryTranslate::class)->where('locale', $locale);
    }
}