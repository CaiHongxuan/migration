<?php

namespace App\Models\Balance;

use Illuminate\Database\Eloquent\Model;

class BalancePeriodDetailThumb extends Model
{

    protected $table = 'balance_period_detail_thumb';

    protected $fillable = ['thumb', 'detail_id'];

    public $timestamps = false;

}

