<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundRotatory extends Model
{
    protected $table = 'fund_rotatories';

    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['code_entry','check_number','amount','balance', 'balance_previous','date_check_delivery','description','user_id','role_id'];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getLastAttribute()
    {
        return $this->latest()->first();
    }
    public function fund_rotatory_outputs()
    {
        return $this->hasMany(FundRotatoryOutput::class);
    }
}
