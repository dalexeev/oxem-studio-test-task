<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // <Магия Laravel>
    protected $table = 'categories';
    public $timestamps = false;
    // </Магия Laravel>
}
