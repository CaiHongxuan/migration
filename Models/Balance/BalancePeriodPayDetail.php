<?php
/**
 * Created by PhpStorm.
 * User: Hongxuan
 * Date: 2017/8/7 0007
 * Time: 11:54
 */

namespace App\Models\Balance;


use Illuminate\Database\Eloquent\Model;

class BalancePeriodPayDetail extends Model
{

    protected $table = 'balance_period_pay_detail';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * 付款状态
     */
    const STATUS_OF_TO_BE_CONFIRMED = 0;
    const STATUS_OF_CONFIRMED = 1;
    const STATUS_OF_REFUSED = 2;

    public static $status = [
        self::STATUS_OF_TO_BE_CONFIRMED => '待确认',
        self::STATUS_OF_CONFIRMED       => '已确认',
        self::STATUS_OF_REFUSED         => '已拒绝'
    ];

    /**
     * 付款类型
     */
    const PAY_TYPE_OF_ONLINE = 0;
    const PAY_TYPE_OF_OFFLINE = 1;
    const PAY_TYPE_OF_REFUND = 2;

    public static $pay_type = [
        self::PAY_TYPE_OF_ONLINE  => '线上',
        self::PAY_TYPE_OF_OFFLINE => '线下',
        self::PAY_TYPE_OF_REFUND  => '退款'
    ];

    /**
     * 交易类型
     */
    const TRADE_TYPE_OF_BALANCE = 0;
    const TRADE_TYPE_OF_WEPAY = 1;
    const TRADE_TYPE_OF_ALIPAY = 2;
    const TRADE_TYPE_OF_TRANSFER = 3;
    const TRADE_TYPE_OF_CASH = 4;
    const TRADE_TYPE_OF_BACK = 5;

    public static $trade_type = [
        self::TRADE_TYPE_OF_BALANCE  => '余额',
        self::TRADE_TYPE_OF_WEPAY    => '微信',
        self::TRADE_TYPE_OF_ALIPAY   => '支付宝',
        self::TRADE_TYPE_OF_TRANSFER => '转账汇款',
        self::TRADE_TYPE_OF_CASH     => '现金汇款',
        self::TRADE_TYPE_OF_BACK     => '退款',
    ];

    /**
     * 获取账单信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bill()
    {
        return $this->belongsTo(BalancePeriodBill::class, 'bill_id');
    }

    /**
     * 获取账期还款凭证
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function thumb()
    {
        return $this->hasMany(BalancePeriodDetailThumb::class, 'detail_id','id');
    }
}