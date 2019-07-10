<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityTranslate extends Model
{
    protected $table = 'cities_translations';

    protected $fillable = ['title','locale','status'];
}