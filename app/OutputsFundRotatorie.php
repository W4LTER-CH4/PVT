<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Util;
use Carbon\CarbonImmutable;
use Carbon;
use Illuminate\Support\Facades\DB;

class OutputsFundRotatorie extends Model
{
  use Traits\EloquentGetTableNameTrait;
  protected $table = 'outputs_fund_rotatories';
    public $fillable = [
        'code',
        'loan_id',
        'fund_rotary_entry_id',
        'description',
        'user_id',
        'role_id',
    ];

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!$this->code) {
            //$latest_payments = DB::table('loan_payments')->orderBy('created_at', 'desc')->limit(1)->first();
            $latest_fund = DB::table('outputs_fund_rotatories')->orderBy('id', 'desc')->latest()->first();
            if (!$latest_fund) $latest_fund = (object)['id' => 0];
            $this->code = implode(['FondoRot', str_pad($latest_fund->id + 1, 6, '0', STR_PAD_LEFT), '-', Carbon::now()->year]);
        }
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /*public function fundRotatoryEntry()
    {
      return $this->belongsTo(FundRotatoryEntry::class);
    }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
