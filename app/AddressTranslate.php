<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressTranslate extends Model
{
    protected $table = 'address_translations';

    protected $fillable = ['title','locale','status'];
}