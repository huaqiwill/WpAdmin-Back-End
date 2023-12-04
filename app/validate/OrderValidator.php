<?php

namespace app\validate;

use think\Validate;

class OrderValidator extends Validate
{
    protected $rule = [
        'create_at' => 'require',
        'type' => 'require',
        'customer_name' => 'require',
        'customer_phone' => 'require',
        'delivery_name' => 'require',
        'delivery_no' => 'require',
        'delivery_time' => 'require',
        'amount' => 'require',
    ];

    protected $message = [
        'create_at.require' => '下单日期不能为空',
        'type.require' => '订单类型不能为空',
        'customer_name.require' => '顾客姓名不能为空',
        'customer_phone.require' => '顾客手机号不能为空',
        'delivery_name.require' => '快递公司不能为空',
        'delivery_no.require' => '快递单号不能为空',
        'delivery_time.require' => '发货时间不能为空',
        'amount.require' => "金额不能为空"
    ];
}
