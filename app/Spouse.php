<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Util;

class Spouse extends Model
{
    use Traits\EloquentGetTableNameTrait;
    protected $fillable = [
        'affiliate_id',
        'city_identity_card_id',
        'identity_card',
        'registration',
        'last_name',
        'mothers_last_name',
        'first_name',
        'second_name',
        'surname_husband',
        'civil_status',
        'birth_date',
        'date_death',
        'reason_death',
        'city_birth_id',
        'death_certificate_number',
        'due_date',
        'is_duedate_undefined',
        'official',
        'book',
        'departure',
        'marriage_date',
    ];

    public function getCivilStatusGenderAttribute()
    {
        $civil_status = Util::get_civil_status($this->civil_status, $this->gender);
        unset($this->affiliate);
        return $civil_status;
    }

    public function getTitleAttribute()
    {
        return 'Vd' . ($this->affiliate->gender == 'M' ? 'a' : 'o') . '.';
    }

    public function getGenderAttribute()
    {
        return $this->affiliate->gender == 'M' ? 'F' : 'M';
    }

    public function getIdentityCardExtAttribute()
    {
        return $this->identity_card . ' ' . $this->city_identity_card->first_shortened;
    }

    public function getFullNameAttribute()
    {
        return preg_replace('/[[:blank:]]+/', ' ', join(' ', [$this->first_name, $this->second_name, $this->last_name, $this->mothers_last_name]));
    }

    public function getAddressAttribute()
    {
        return $this->affiliate->address;
    }

    public function getCellPhoneNumberAttribute()
    {
        return $this->affiliate->cell_phone_number;
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }
    public function disbursements()
    {
        return $this->morphMany(Loan::class, 'disbursable');
    }
    
    public function active_loans()
    {
        return Loan::where('disbursable_id', $this->id)->where('disbursable_type', 'spouses')->whereIn('state_id', [1,3])->count();
    }
    public function loans(){
        return Loan::where('disbursable_id', $this->id)->where('disbursable_type', 'spouses')->whereIn('state_id', [1,3])->get();
    }
    public function current_loans()
    {
      $loan_state = LoanState::whereName('Desembolsado')->first();
      return Loan::where('disbursable_id', $this->id)->where('disbursable_type', 'spouses')->where('state_id', $loan_state->id)->get();
      //return $this->belongsToMany(Loan::class, 'loan_affiliates')->withPivot(['payment_percentage'])->whereGuarantor(false)->where('state_id', $loan_state->id)->orderBy('loans.created_at', 'desc');
    }
    public function guarantees(){
        return $this->affiliate();
    }
}
