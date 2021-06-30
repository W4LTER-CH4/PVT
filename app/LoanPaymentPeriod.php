<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentPeriod extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['year','month','amount_concilation', 'description','import_command','import_senasir'];
}
