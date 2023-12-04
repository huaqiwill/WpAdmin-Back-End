<?php

namespace app\validate;

use think\Validate;

class ProductAddValidator extends Validate
{
    protected $rule = [
        'product_name' => 'require',
    ];

    protected $message = [
        'product_name.require' => '商品名称不能为空',
    ];
}