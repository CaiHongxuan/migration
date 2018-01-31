<?php

namespace App\Models\Balance;

use App\Models\Trade\Order;
use Illuminate\Database\Eloquent\Model;

class BalancePeriodDetail extends Model
{
    //
    protected $table = 'balance_period_detail';

    protected $fillable = [
        'type',
        'statement',
        'pay_credit',
        'period_id',
        'bill_id',
        'order_id',
        'delivery_id',
        'ext_id',
        'created_at',
        'status',
        'ext_id'
    ];

    public $timestamps = false;

    /**
     * 状态
     *
     * @var array
     */
    public static $status = [
        0 => '已退款',
        1 => '正常'
    ];

    /**
     * 收支类型
     */
    const TYPE_OF_OUT = 0;
    const TYPE_OF_IN = 1;

    public static $type = [
        self::TYPE_OF_OUT       => '支出',
        self::TYPE_OF_IN => '收入',
    ];

    /**
     * 账单明细类型
     */
    const TYPE_OF_BILL_PAY = 0;
    const TYPE_OF_BILL_COUPON = 1;
    const TYPE_OF_BILL_REFUND = 2;
    const TYPE_OF_BILL_WAITPAY = 3;
    const TYPE_OF_BILL_REPAY = 4;

    public static $type_bill = [
        self::TYPE_OF_BILL_PAY       => '订单发货',
        self::TYPE_OF_BILL_COUPON => '优惠券抵消',
        self::TYPE_OF_BILL_REFUND => '退货',
        self::TYPE_OF_BILL_WAITPAY => '退货（生成付款单）',
        self::TYPE_OF_BILL_REPAY => '还款',
    ];

    /**
     * 获取到对应的账单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bill()
    {
        return $this->belongsTo(BalancePeriodBill::class,'bill_id','id');
    }

    /**
     * 所属订单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}




