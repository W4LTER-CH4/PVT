<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentPeriod extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['year','month','description','import_command','import_senasir'];
}
