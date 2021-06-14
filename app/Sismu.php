<?php

namespace App;

use Carbon;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Util;
use Illuminate\Support\Facades\DB;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Sismu extends Model
{
    use Traits\EloquentGetTableNameTrait;
    use Traits\RelationshipsTrait;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'code',
        'amount_approved',
        'loan_term',
        'balance',
        'estimated_quota',
        'date_cut_refinancing',
        'disbursement_date',
        'loan_id',
    ];
    // add records

    public function records()
    {
      return $this->morphMany(Record::class, 'recordable');
    }
}
