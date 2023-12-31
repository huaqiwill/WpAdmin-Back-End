<?php

namespace app\validate;

use think\Validate;

class IndexValidator extends Validate
{
    protected $rule = [
        'name' => 'require|unique:tb_delivery',
    ];

    protected $message = [
        'name.require' => '请输入快递名称',
        'name.unique' => '快递名称重复',
    ];

    protected $scene = [

    ];
}
