<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanProperty extends Model
{
    use Traits\EloquentGetTableNameTrait;
    public $timestamps = true;
    public $fillable = [
        'land_lot_number',
        'neighborhood_unit',
        'location',
        'surface',
        'cadastral_code',
        'limit',
        'public_deed_number',
        'lawyer',
        'registration_number',
        'real_folio_number',
        'public_deed_date',
        'net_realizable_value',
        'commercial_value',
        'rescue_value',
        'real_city_id',
        'measurement'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class,'property_id','id');
    }
}
