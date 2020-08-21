<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Категории данного товара.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
}
