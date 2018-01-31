<?php

namespace App\Models;

use App\Models\Bus\Company;
use Illuminate\Database\Eloquent\Model;

class PayToCustom extends Model
{
    //表名
    protected $table = 'pay_to_custom';

    //字段名
    protected $fillable = ['pay_no','buyer_id', 'company_id','type', 'ext_id', 'total_money', 'reduce_money', 'result_money','remark','pay_type','status'];

    /**
     * 状态
     */
    const STATUS_UNPAY = 0;
    const STATUS_PAY = 1;

    public static $status = [
        self::STATUS_UNPAY  => '未支付',
        self::STATUS_PAY  => '已支付'
    ];

    /**
     * 类型
     */
    const TYPE_REFUND = 0;

    public static $type = [
        self::TYPE_REFUND  => '退款',
    ];

    /**
     * 支付类型
     */
    const PAY_TYPE_PENDING = 0;
    const PAY_TYPE_BALANCE = 1;
    const PAY_TYPE_WX = 2;
    const PAY_TYPE_ALI = 3;
    const PAY_TYPE_OFFLINE = 4;
    const PAY_TYPE_OFFCASH = 5;

    public static $pay_type = [
        self::PAY_TYPE_PENDING  => '未支付',
        self::PAY_TYPE_BALANCE  => '余额',
        self::PAY_TYPE_WX  => '微信',
        self::PAY_TYPE_ALI  => '支付宝',
        self::PAY_TYPE_OFFLINE  => '转账汇款(线下支付)',
        self::PAY_TYPE_OFFCASH  => '门店现金(线下支付)'
    ];

    /**
     * 获取购买者
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer()
    {
        return $this->hasOne(Company::class, 'id', 'buyer_id');
    }

    /**
     * 获取商家
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

}
