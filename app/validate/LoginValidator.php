<?php

namespace app\validate;

use think\Validate;

class LoginValidator extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',

    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'password.require' => '密码不能为空',

    ];
}