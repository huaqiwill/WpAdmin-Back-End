<?php

namespace app\validate;

use think\Validate;

class CustomerValidator extends Validate
{
    protected $rule = [
        'cname' => 'require',
        'sex' => 'require',
        'ctype' => 'require',
        'origin' => 'require',
        'phone' => 'require|unique:tb_customer',
        'wechat' => 'require|unique:tb_customer',
        'email' => 'require|unique:tb_customer',
        'create_at' => 'require'
    ];

    protected $message = [
        'cname.require' => '请输入顾客姓名',
        'sex.require' => '请选择顾客性别',
        'ctype.require' => '请输入顾客类型',
        'origin.require' => '请输入顾客来源',
        'phone.require' => '请输入顾客手机号',
        'phone.unique' => '顾客手机号已存在',
        'wechat.require' => '请输入顾客微信号',
        'wechat.unique' => '顾客微信已存在',
        'email.require' => '请输入顾客邮箱',
        'email.unique' => '邮箱已经存在',
        'create_at.require' => '请输入创建时间'
    ];
}
