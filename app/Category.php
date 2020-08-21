<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Указывает, что метки времени не нужны.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Продукты данной категории.
     */
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
