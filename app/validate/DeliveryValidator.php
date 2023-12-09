<?php

namespace app\validate;

use think\Validate;

class DeliveryValidator extends Validate
{
    protected $rule = [
        'name' => 'require|unique:tb_delivery',
    ];

    protected $message = [
        'name.require' => '请输入快递名称',
        'name.unique' => '快递名称重复',
    ];

    protected $scene = [
        'add' => [],
        'enable' => []
    ];

    protected function sceneList()
    {
        $this->rule = [];
    }

    protected function sceneAdd()
    {
    }

    protected function sceneUpdate()
    {
        $this->rule = [
            'name' => 'require',
        ];
    }
}
