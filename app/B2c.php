<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2c extends Model
{
    protected $table = 'b2c';
    protected $fillable = [
        'b2c_id', 'email', 'status', 'product_id'
    ];
}
