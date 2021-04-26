<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use Traits\EloquentGetTableNameTrait;
    protected $fillable = [

        'user_id',
        'affiliate_id',
        'type',
        'degree_id',
        'unit_id',
        'breakdown_id',
        'category_id',
        'month_year',
        'item',
        'base_wage',
        'dignity_pension',
        'seniority_bonus',
        'study_bonus',
        'position_bonus',
        'border_bonus',
        'east_bonus',
        'public_security_bonus',
        'deceased',
        'natality',
        'lactation',
        'prenatal',
        'subsidy',
        'gain',
        'payable_liquid',
        'quotable',
        'retirement_fund',
        'mortuary_quota',
        'mortuary_aid',
        'subtotal',
        'ipc',
        'total'

    ];
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    //relacion de la tabla polimorfica ajuste de contribuciones
    public function loan_contribution_adjusts()
    {
        return $this->morphMany(LoanContrubutionAdjust::class,'adjustable');
    }
    public function breakdown()
    {
        return $this->belongsTo(Breakdown::class,'breakdown_id','id');
    }
}
