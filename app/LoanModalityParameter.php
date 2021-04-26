<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProcedureModality;

class LoanModalityParameter extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public $fillable = [
        'procedure_modality_id',
        'debt_index',
        'quantity_ballots',
        'guarantors',
        'min_guarantor_category',
        'max_guarantor_category',
        'min_lender_category',
        'max_lender_category',
        'personal_reference',
        'maximum_amount_modality',
        'minimum_amount_modality',
        'maximum_term_modality',
        'minimum_term_modality'
    ];

    public function getDecimalIndexAttribute()
    {
        return $this->debt_index / (100);
    }

    public function procedure_modality()
    {
        return $this->belongsTo(ProcedureModality::class);
    }
}
