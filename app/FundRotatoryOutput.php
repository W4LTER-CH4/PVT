<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Util;
use Carbon\CarbonImmutable;
use Carbon;
use App\FundRotatory;
use App\Loan;

use Illuminate\Support\Facades\DB;

class FundRotatoryOutput extends Model
{
  use Traits\EloquentGetTableNameTrait;
  use SoftDeletes;
  protected $table = 'fund_rotatory_outputs';
    public $fillable = [
        'code',
        'loan_id',
        'fund_rotatory_id',
        'user_id',
        'role_id',
    ];
    

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!$this->code) {
            //$latest_payments = DB::table('loan_payments')->orderBy('created_at', 'desc')->limit(1)->first();
            $latest_fund = DB::table('fund_rotatory_outputs')->orderBy('id', 'desc')->latest()->first();
            if (!$latest_fund) $latest_fund = (object)['id' => 0];
            $this->code = implode([str_pad($latest_fund->id + 1, 6, '0', STR_PAD_LEFT)]);
            //$this->code = implode(['FondoRot', str_pad($latest_fund->id + 1, 6, '0', STR_PAD_LEFT), '-', Carbon::now()->year]);
        }
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function fund_rotatory()
    {
      return $this->belongsTo(FundRotatory::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
