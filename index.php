<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 15:58
 */

use App\Models\Balance\BalancePeriod;
use App\Models\Balance\BalancePeriodAddress;
use App\Models\Balance\BalancePeriodBill;
use App\Models\Balance\BalancePeriodDetail;
use App\Models\Balance\BalancePeriodDetailThumb;
use App\Models\Balance\BalancePeriodLog;
use App\Models\Balance\BalancePeriodPayDetail;
use App\Models\PayToCustom;

// 加载composer
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

// 初始化配置
$DB = \App\DB::init(); // 这里的$DB相当于laravel的"\DB"

BalancePeriod::where('id', '>', 0)->update(['frozen' => 0, 'residue' => $DB::raw('credit')]);  // 账期合同表，重置所有账期额度
BalancePeriod::where('status', BalancePeriod::BALANCE_ACCOUNT_STATUS)->update(['status' => BalancePeriod::BALANCE_OVERDUE_STATUS]); // 将“合同失效，待结算”状态改为“已过期”
// BalancePeriodAddress::where('id', '>', 0)->delete();               // 账期地址表

BalancePeriodBill::where('id', '>', 0)->delete();                  // 账单表
BalancePeriodPayDetail::where('id', '>', 0)->delete();             // 账期还款详情表
BalancePeriodLog::where('id', '>', 0)->delete();                   // 账期明细表
BalancePeriodDetail::where('id', '>', 0)->delete();                // 账期流水（订单关联账期账单）表
BalancePeriodDetailThumb::where('id', '>', 0)->delete();           // 账期还款凭证表
PayToCustom::where('id', '>', 0)->delete();                        // 付款单
$DB->table('balance_period_chase')->where('id', '>', 0)->delete(); // 账单信息表
$DB->table('balance_receivable')->where('id', '>', 0)->delete();   // 账期应收管理表

echo "<h1 style='margin-top: 10%; text-align: center; color: #008200'>清除成功！</h1>";