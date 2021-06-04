<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['year','month','amount_concilation', 'description'];
}
