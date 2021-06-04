<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentCategorie extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['name','type_register','shortened', 'is_valid','description'];
}
