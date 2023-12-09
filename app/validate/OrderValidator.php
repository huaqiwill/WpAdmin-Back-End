<?php

namespace app\validate;

use think\Validate;

class OrderValidator extends Validate
{
    protected $rule = [
        'order_id' => 'require',
        'user_id' => 'require',
        'create_at' => 'require',
        'type' => 'require',
        'customer_id' => 'require',
        'customer_name' => 'require',
        'customer_phone' => 'require',
        'delivery_name' => 'require',
        'delivery_no' => 'require',
        'delivery_time' => 'require',
        'amount' => 'require',
    ];

    protected $message = [
        'customer_id.require' => '顾客ID不能为空',
        'create_at.require' => '下单日期不能为空',
        'type.require' => '订单类型不能为空',
        'customer_name.require' => '顾客姓名不能为空',
        'customer_phone.require' => '顾客手机号不能为空',
        'delivery_name.require' => '快递公司不能为空',
        'delivery_no.require' => '快递单号不能为空',
        'delivery_time.require' => '发货时间不能为空',
        'amount.require' => "金额不能为空",
        'order_id.' => '订单ID不能为空',
        'user_id' => '用户ID不能为空'
    ];

    protected function sceneList()
    {
        $this->rule = [];
    }

    protected function sceneListGetByCustomerId()
    {
        $this->rule = [
            'customer_id' => 'require',
        ];
    }

    protected function sceneInfo()
    {
        $this->rule = [
            'order_id' => 'require',
            'user_id' => 'require'
        ];
    }

    protected function sceneDetail()
    {
        $this->rule = [];
    }

    protected function scenePrintInfo()
    {
        $this->rule = [
            'order_id' => 'require',
            'customer_id' => 'require',
            'user_id' => 'require'
        ];
    }

    protected function scenePhoneQuery()
    {
        $this->rule = [
            'customer_phone' => 'require'
        ];
    }

    protected function sceneAdd()
    {
        $this->rule = [
            'user_id' => 'require',
            'create_at' => 'require',
            'type' => 'require',
            'customer_id' => 'require',
            'customer_name' => 'require',
            'customer_phone' => 'require',
            'delivery_name' => 'require',
            'delivery_no' => 'require',
            'delivery_time' => 'require',
            'amount' => 'require',
        ];
    }

    protected function sceneUpdate()
    {
        $this->rule = [
            'order_id' => 'require',
            'user_id' => 'require',
            'create_at' => 'require',
            'type' => 'require',
            'customer_id' => 'require',
            'customer_name' => 'require',
            'customer_phone' => 'require',
            'delivery_name' => 'require',
            'delivery_no' => 'require',
            'delivery_time' => 'require',
            'amount' => 'require',
        ];
    }

    protected function sceneDelete()
    {
        $this->rule = ['order_id' => 'require'];
    }


    protected function sceneConfirm()
    {
        $this->rule = ['order_id' => 'require'];
    }

    protected function sceneSubmit()
    {
        $this->rule = ['order_id' => 'require'];
    }
}
