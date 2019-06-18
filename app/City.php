<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

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
            $trans = $this->translate($item->locale);
            if ($trans) {
                $city['sities'][$item->locale] = $trans;
            } else {
                $city['cities'][$item->locale] = new CityTranslate;
            }
        }
        return $city;
    }

    public function contents()
    {
        return $this->hasMany('App\CityTranslate');
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
        return $this->hasMany(CityTranslate::class);
    }
}