<?php

namespace App\Models\Balance;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class BalancePeriodAddress extends Model
{
    //
    protected $table = 'balance_period_address';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}




