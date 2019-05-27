<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslate extends Model
{
    protected $table = 'categories_translations';

    protected $fillable = ['title','locale','status'];
}