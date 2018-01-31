<?php

namespace App\Models\Balance;

use App\Models\Bus\Company;
use Illuminate\Database\Eloquent\Model;

class BalancePeriodBill extends Model
{
    protected $table = 'balance_period_bill';

    protected $fillable = ['cid', 'self_id', 'bill_code', 'out_time', 'repay_time', 'pay_credit', 'paid_credit', 'unpay_credit', 'period_id', 'return_status', 'status'];

    public $timestamps = false;

    /**
     * 还款状态
     */
    const RETURN_STATUS_OF_UNPAID = 0;
    const RETURN_STATUS_OF_PARTIAL_PAID = 1;
    const RETURN_STATUS_OF_USELESS_PAID = 2;
    const RETURN_STATUS_OF_PAID = 3;

    public static $return_status = [
        self::RETURN_STATUS_OF_UNPAID       => '未还款',
        self::RETURN_STATUS_OF_PARTIAL_PAID => '部分还款',
        self::RETURN_STATUS_OF_USELESS_PAID => '无效还款',
        self::RETURN_STATUS_OF_PAID         => '已还清',
    ];

    /**
     * 出账单状态
     */
    const STATUS_OF_EXPIRED = 0;
    const STATUS_OF_OVERDUE = 1;
    const STATUS_OF_PAID = 2;

    public static $status = [
        self::STATUS_OF_EXPIRED => '已到期',
        self::STATUS_OF_OVERDUE => '逾期',
        self::STATUS_OF_PAID => '还清无状态'
    ];

    /**
     * 商家信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'cid', 'id');
    }

    /**
     * 客户信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Company::class, 'self_id', 'id');
    }

    /**
     * 账期信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo(BalancePeriod::class, 'period_id', 'id');
    }

    /**
     * 账期详情
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detail()
    {
        return $this->hasMany(BalancePeriodDetail::class, 'bill_id', 'id');
    }

}




