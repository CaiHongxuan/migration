<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 下午 2:58
 */
namespace App\Models\Balance;


use App\Models\Bus\Company;
use Illuminate\Database\Eloquent\Model;

class BalancePeriodLog extends Model
{
    protected $table = 'balance_period_log';

    protected $fillable = [
        'period_id', 'self_id', 'identity', 'ext_id', 'type', 'effect_type', 'credit_left', 'effect_money', 'remark'];

    /**
     * 收入/支出
     */
    const TYPE_OUT = 0;
    const TYPE_IN = 1;

    public static $type = [
        self::TYPE_OUT       => '支出',
        self::TYPE_IN => '收入'
    ];

    /**
     * 订货商/供货商
     */
    const IDENTITY_DING = 1;
    const IDENTITY_GONG = 2;

    public static $identity = [
        self::IDENTITY_DING => '订货商',
        self::IDENTITY_GONG => '供货商'
    ];

    /**
     * 影响类型
     */
    const EFFECT_TYPE_APPLY = 0;
    const EFFECT_TYPE_PAY = 1;
    const EFFECT_TYPE_REPAY = 2;
    const EFFECT_TYPE_BACK = 3;

    public static $effect_type = [
        self::EFFECT_TYPE_APPLY => '账期申请',
        self::EFFECT_TYPE_PAY  => '订单支付',
        self::EFFECT_TYPE_REPAY => '账期还款',
        self::EFFECT_TYPE_BACK  => '订单退还',
    ];

    /**
     * 账期信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo(BalancePeriod::class, 'period_id', 'id');
    }

    /**
     * 公司信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'self_id', 'id');
    }
}