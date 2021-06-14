<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundRotaryEntry extends Model
{
    protected $table = 'fund_rotary_entries';

    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['code_entry','amount','balance', 'balance_previous','date_entry_amount','user_id','role_id'];
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
}
