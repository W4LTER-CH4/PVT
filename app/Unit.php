<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'breakdown_id',
        'district',
        'code',
        'name',
        'shortened'
    ];
}