<?php

namespace Ycstar\Xiaokun;

use Ycstar\Xiaokun\Exceptions\XiaokunException;

class Xiaokun extends Base
{
    protected $methods = [
        'openCity', //查询当前城市是否开通代驾
        'nearDriver', //获取周边空闲司机
        'estimateFee', //费用预估
        'creatOrder', //代驾下单
        'cancel', //取消订单
        'cancelReasons', //查询取消原因
        'orderDetail', //查询订单信息
        'realTimeBill', //查询实时费用
        'driverPosition', //查询司机实时位置
        'billDetail', //查询账单详情
        'driverPhoto', //查询验车照片
        'payUrl', //跳转代驾平台支付页面
    ];

    public function __call($method, array $arguments)
    {
        if (!in_array($method, $this->methods)) {
            throw new XiaokunException('非法的方法名');
        }
        return $this->request($method, ...$arguments);
    }

}