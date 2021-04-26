<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakdown extends Model
{
    public $timestamps = true;
    public $fillable = ['code','name'];
    public $guarded = ['id'];
}
