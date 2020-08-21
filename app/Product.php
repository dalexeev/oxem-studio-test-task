<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $with = ['categories'];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
}
