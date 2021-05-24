<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProcedureModality;

class LoanInterest extends Model
{
    public $timestamps = true;
    protected $fillable = ['procedure_modality_id', 'annual_interest','penal_interest'];
    public $guarded = ['id'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getDailyCurrentInterestAttribute()
    {
        return $this->annual_interest / (100 * 360);
    }

    public function getDailyPenalInterestAttribute()
    {
        return $this->penal_interest / (100 * 360);
    }

    public function getMonthlyCurrentInterestAttribute()
    {
        return $this->annual_interest / (100 * 12);
    }

    public function getMonthlyPenalInterestAttribute()
    {
        return $this->penal_interest / (100 * 12);
    }

    public function procedure_modality()
    {
        return $this->belongsTo(ProcedureModality::class);
    }
}
