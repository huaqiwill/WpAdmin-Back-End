<?php

namespace app\validate;

use think\Validate;

class AddressValidator extends Validate
{
    protected $rule = [
        'address_id' => 'require',
        'name' => 'require',
        'phone' => 'require',
        'province' => 'require',
        'city' => 'require',
        'district' => 'require',
        'detail' => 'require',
        'customer_id' => 'require'
    ];

    protected $message = [
        'address_id.require' => '地址ID不能为空',
        'name.require' => '地收货人姓名不能为空',
        'phone.require' => '收货人手机号不能为空',
        'province.require' => '省不能为空',
        'city.require' => '城市不能为空',
        'district.require' => '区县不能为空',
        'detail.require' => '详细地址不能为空',
        'customer_id.require' => '顾客ID不能为空'
    ];

    protected function sceneAdd()
    {
        $this->rule = [
            'name' => 'require',
            'phone' => 'require',
            'province' => 'require',
            'city' => 'require',
            'district' => 'require',
            'detail' => 'require',
            'customer_id' => 'require'
        ];
    }

    protected function sceneList()
    {
        $this->rule = [];
    }
    protected function sceneDelete()
    {
        $this->rule = ['address_id' => 'require'];
    }
    protected function sceneUpdate()
    {
        $this->rule = ['address_id' => 'require'];
    }
    protected function sceneInfo()
    {
        $this->rule = ['address_id' => 'require'];
    }
}
