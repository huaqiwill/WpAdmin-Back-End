<?php

namespace app\validate;

use think\Validate;

class ProductValidator extends Validate
{
    protected $rule = [
        'name' => 'require|unique:tb_product',
        'price' => 'require',
        'number' => 'require',
    ];

    protected $message = [
        'name.require' => '商品名称不能为空',
        'name.unique' => '商品名称已存在',
        'price.require' => '商品价格不能为空',
        'number.require' => '商品数量不能为空',
    ];


}