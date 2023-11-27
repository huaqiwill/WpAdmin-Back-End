<?php

namespace app\validate;

use think\Validate;

class CustomerValidator extends Validate
{
    protected $rule = [
        'cname' => 'require',
        'ctype' => 'require',
        'origin' => 'require',
        'phone' => 'require|unique:tb_customer',
        'wechat' => 'require|unique:tb_customer',
        'email' => 'require',
    ];

    protected $message = [
        'cname.require' => '请输入顾客姓名',
        'ctype.unique' => '请输入顾客类型',
        'origin.require' => '请输入顾客来源',
        'phone.require' => '请输入顾客手机号',
        'phone.unique' => '顾客手机号已存在',
        'wechat.require' => '请输入顾客微信号',
        'wechat.unique' => '顾客微信已存在',
        'email.require' => '请输入顾客邮箱',
    ];

}