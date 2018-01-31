<?php

namespace App\Models\Balance;

use App\Models\Bus\Company;
use Illuminate\Database\Eloquent\Model;

class BalancePeriod extends Model
{
    //
    protected $table = 'balance_period';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * 账期类型
     */
    const TYPE_OF_WEEK = 1;
    const TYPE_OF_MONTH = 2;
    const TYPE_OF_TEN = 3;
    const TYPE_OF_HALF = 4;

    public static $type = [
        self::TYPE_OF_WEEK  => '周账单',
        self::TYPE_OF_MONTH => '月账单',
        self::TYPE_OF_TEN  => '10天账单',
        self::TYPE_OF_HALF => '半月账单'
    ];

    /**
     * 账期是否有效
     */
    const UNEFFECTIVE = 0;
    const EFFECTIVE = 1;

    public static $effective = [
        self::UNEFFECTIVE  => '无效',
        self::EFFECTIVE => '有效'
    ];

    /**
     * 还款日期
     */
    // 周账单
    const REPAY_TERM_OF_WEEK_NORMAL = 3;

    public static $repay_term_week = [
        self::REPAY_TERM_OF_WEEK_NORMAL => '出账后3天',
    ];

    // 10天账单
    const REPAY_TERM_OF_TEN_NORMAL = 3;

    public static $repay_term_ten = [
        self::REPAY_TERM_OF_TEN_NORMAL => '出账后3天',
    ];

    // 半月账单
    const REPAY_TERM_OF_HALF_THREE = 3;
    const REPAY_TERM_OF_HALF_SEVEN = 7;

    public static $repay_term_half = [
        self::REPAY_TERM_OF_HALF_THREE => '出账后3天',
        self::REPAY_TERM_OF_HALF_SEVEN => '出账后7天'
    ];

    // 月账单
    const REPAY_TERM_OF_MON_TYPE1 = 1;
    const REPAY_TERM_OF_MON_TYPE2 = 2;
    const REPAY_TERM_OF_MON_TYPE3 = 3;
    const REPAY_TERM_OF_MON_TYPE4 = 4;

    public static $repay_term_mon = [
        self::REPAY_TERM_OF_MON_TYPE1 => '出账后7天',
        self::REPAY_TERM_OF_MON_TYPE4 => '出账后15天',
        self::REPAY_TERM_OF_MON_TYPE2 => '出账后1月7天',
        self::REPAY_TERM_OF_MON_TYPE3 => '出账后2月7天',
    ];

    /**
     * 对应账期状态
     */
    const BALANCE_SUBMIT_STATUS = 0;
    const BALANCE_PASS_STATUS = 1;
    const BALANCE_SURRENDER_STATUS = 2;
    const BALANCE_CANCEL_STATUS = 3;
    const BALANCE_FAIL_STATUS = 4;
    const BALANCE_OVERDUE_STATUS = 5;
    const BALANCE_FREEZE_STATUS = 6;
    const BALANCE_ACCOUNT_STATUS = 100;

    // 账期状态
    public static $balance_status = [
        self::BALANCE_SUBMIT_STATUS    => '已提交',
        self::BALANCE_PASS_STATUS      => '已签约',
        self::BALANCE_SURRENDER_STATUS => '已解约',
        self::BALANCE_CANCEL_STATUS    => '已取消',
        self::BALANCE_FAIL_STATUS      => '审核不通过',
        self::BALANCE_OVERDUE_STATUS   => '已过期',
        self::BALANCE_FREEZE_STATUS    => '冻结',
        self::BALANCE_ACCOUNT_STATUS    => '合同失效，待结算',
    ];

    /**
     * 合同删除
     */
    const DELETE_NOT = 0;
    const DELETE_BUYER = 1;
    const DELETE_SUPPLY = 2;
    const DELETE_ALL = 3;

    const IS_DELETE = [
        self::DELETE_NOT => '未删除',
        self::DELETE_BUYER => '订货方删除',
        self::DELETE_SUPPLY => '供货方删除',
        self::DELETE_ALL => '订供都删除'
    ];

    /**
     * 账期到期状态
     */
    const PERIOD_EXPIRED_STATUS = 0;
    const PERIOD_OVERDUE_STATUS = 1;
    const PERIOD_NONE_STATUS = 2;

    public static $period_date_status = [
        self::PERIOD_EXPIRED_STATUS => '已到期',
        self::PERIOD_OVERDUE_STATUS => '逾期',
        self::PERIOD_NONE_STATUS    => '无'
    ];


    /**
     * 还款状态
     */
    const PERIOD_UNPAID_STATUS = 0;
    const PERIOD_PARTLY_PAID_STATUS = 1;
    const PERIOD_USELESS_PAID_STATUS = 2;
    const PERIOD_PAID_STATUS = 3;

    public static $period_return_status = [
        self::PERIOD_UNPAID_STATUS       => '未还款',
        self::PERIOD_PARTLY_PAID_STATUS  => '部分还款',
        self::PERIOD_USELESS_PAID_STATUS => '无效还款',
        self::PERIOD_PAID_STATUS         => '已还清'
    ];

    public static $next_week = [
        1 => 'next Monday',
        2 => 'next Tuesday',
        3 => 'next Wednesday',
        4 => 'next Thursday',
        5 => 'next Friday',
        6 => 'next Saturday',
        7 => 'next Sunday',
    ];

    /**
     * 获取出账时间
     * @param null $action_time     需要计算的时间
     * @param null $type            账期合同类型
     * @param null $cutoff          出账日
     * @return mixed
     * @throws \Exception
     */
    public function getBillTime($action_time = null,$type = null,$cutoff = null)
    {
        // 如果计算的时间为空，则取值当前时间
        if(is_null($action_time)){
            $action_time = time();
        }

        // 如果传值的账期合同类型为空，则取值当前类型
        if(is_null($type)){

            // 如果不是一个实例或不存在，将不能继续使用此方法
            if(empty($this->type)){
                throw new \Exception('账期类型不能为空!');
            }

            $type = $this->type;
        }

        // 传值的出账日
        if(is_null($cutoff) && in_array($type,[self::TYPE_OF_WEEK,self::TYPE_OF_MONTH])){

            // 如果不是一个实例或不存在，将不能继续使用此方法
            if(empty($this->cutoff)){
                throw new \Exception('账期类型不能为空!');
            }

            $cutoff = $this->cutoff;
        }

        // 如果已经存在账单日，并且出账日期大于今天，则使用出账日
        if(!empty($this->bill_time) && strtotime($this->bill_time) > time()){
            return $this->bill_time;
        }

        // 获取下个月的天数
        $next_month_to_day = $this->getNextMonthToDay($action_time);

        // 获取需要查找的日期的 "日"
        $today = date('j',$action_time);

        switch ($type){
            case self::TYPE_OF_WEEK:

               return date('Y-m-d',strtotime(array_get(BalancePeriod::$next_week,$cutoff),$action_time));
                break;
            case self::TYPE_OF_MONTH:
                // 出账日补0
                $cutoff = str_pad($cutoff,2,0,STR_PAD_LEFT);
                return date('Y-m-'.$cutoff,$cutoff > $today ? $action_time : strtotime("+{$next_month_to_day} day",$action_time));
                break;
            case BalancePeriod::TYPE_OF_TEN:
                // 出账日
                $cutoff = $today > 21 ? 01 : (($today > 11) ? 21 : 11);
                return date('Y-m-'.$cutoff,$cutoff == 01 ? strtotime("+{$next_month_to_day} day",$action_time) : $action_time);
                break;
            case BalancePeriod::TYPE_OF_HALF:
                // 出账日
                $cutoff = $today > 16 ? 01 : 16;
                return date('Y-m-'.$cutoff,$cutoff == 01 ? strtotime("+{$next_month_to_day} day",$action_time) : $action_time);
                break;
            default:
                throw new \Exception('账期类型不存在!');
        }
    }

    /**
     * 获取出账时间
     * @param null $bill_time       出账时间
     * @param null $type            账期合同类型
     * @param null $repay_term      还款期限
     * @return mixed
     * @throws \Exception
     */
    public function getRepayTime($bill_time,$type = null,$repay_term = null)
    {
        // 如果传值的账期合同类型为空，则取值当前类型
        if(is_null($type)){

            // 如果不是一个实例或不存在，将不能继续使用此方法
            if(empty($this->type)){
                throw new \Exception('账期类型不能为空!');
            }

            $type = $this->type;
        }

        // 如果传值的账期合同类型为空，则取值当前类型
        if(is_null($repay_term)){

            // 如果不是一个实例或不存在，将不能继续使用此方法
            if(empty($this->repay_term)){
                throw new \Exception('账期类型不能为空!');
            }

            $repay_term = $this->repay_term;
        }

        if($type == self::TYPE_OF_MONTH){//月账期
            $str = "";
            switch ($repay_term){
                case 1:$str = '+1 week';break;
                case 2:$str = '+1 month +1 week';break;
                case 3 :$str = '+2 month +1 week';break;
                case 4:$str = '+15 day';break;
            }
            return date('Y-m-d',strtotime($str,strtotime($bill_time)));
        }else if(in_array($type,[self::TYPE_OF_WEEK,self::TYPE_OF_TEN,self::TYPE_OF_HALF])){
            return date('Y-m-d',strtotime("+$repay_term day",strtotime($bill_time)));

        }
    }

    /**
     * 获取下个月的天数
     * @param null $date
     * @return int
     */
    public function getNextMonthToDay($date = null)
    {
        if(is_null($date)){
            $date = time();
        }

        $getMonth = intval(date('n',$date));

        // 如果是2,4,6,7,9,11,12 月份，下个月则有31天
        if(in_array($getMonth,[2,4,6,7,9,11,12])){
            return 31;
        }

        // 如果是3,5,8,10月份，下个月则有30天
        if(in_array($getMonth,[3,5,8,10])){
            return 30;
        }

        // 如果是1月份，则计算出2月份的天数
        return date('t',strtotime(date('Y-02-d',$date)));
    }
    
    /**
     * 获取客户公司名称
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 获取企业名称
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function own()
    {
        return $this->belongsTo(Company::class, 'own_id');
    }

    /**
     * 账单信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function period_bill()
    {
        return $this->hasMany(BalancePeriodBill::class, 'period_id', 'id');
    }

    /**
     * 获取账期地址
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(BalancePeriodAddress::class, 'id','period_id');
    }


}