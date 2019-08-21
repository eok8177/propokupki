<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class language extends Model
{
    protected $table = 'languages';

    protected $fillable = ['name','locale','status'];
}