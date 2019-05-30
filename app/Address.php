<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = ['slug','code','status'];

    public function langs($status = 1)
    {
        return Language::where('status', $status)->get();
    }

    public function forAdmin()
    {
        $addres = [];
        $addres['addres'] = $this;
        foreach ($this->langs() as $item) {
            $trans = $this->translate($item->locale)->first();
            if ($trans) {
                $addres['contents'][$item->locale] = $trans;
            } else {
                $addres['contents'][$item->locale] = new AddressTranslate;
            }
        }
        return $addres;
    }

    public function contents()
    {
        return $this->hasMany('App\AddressTranslate');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasOne(AddressTranslate::class)->where('locale', $locale);
    }

    public function searchAddress ($search, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->hasMany(AddressTranslate::class)->where('locale', $locale)->where('title', $search);
    }

    public function sities()
    {
        return $this->belongsToMany('App\City');
    }

    public function shops()
    {
        return $this->belongsToMany('App\Shop');
    }

}