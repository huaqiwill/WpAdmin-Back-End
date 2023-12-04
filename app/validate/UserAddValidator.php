<?php

namespace app\validate;

use think\Validate;

class UserAddValidator extends Validate
{
    protected $rule = [
        'username' => 'require|unique:tb_user',
        'password' => 'require',
        'phone' => 'require',
        'wechat' => 'require',
        'id_card' => 'require',
        'type' => 'require',
        'emergency_contact_name' => 'require',
        'emergency_contact_phone' => 'require',
        'role_id' => 'require',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.unique' => '用户名已存在',
        'password.require' => '密码不能为空',
        'phone.require' => '手机号不能为空',
        'role_id.require' => '角色不能为空',
        'wechat.require' => '微信不能为空',
        'id_card.require' => '身份证不能为空',
        'emergency_contact_name.require' => '紧急联系人姓名不能为空',
        'emergency_contact_phone.require' => '紧急联系人电话不能为空',
        'type.require' => '员工类型不能为空'
    ];
}
