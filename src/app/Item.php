<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $casts = [
        'activate' => 'boolean',
        'options' => 'object',
        'price' => 'float',
    ];

    protected $fillable = [
        'name',
        'activate',
        'price',
        'options'
    ];

    protected $visible = [
        'id',
        'activate',
        'name',
        'price',
        'options'
    ];

}
