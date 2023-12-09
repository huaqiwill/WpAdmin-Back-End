<?php

namespace app\validate;

use app\model\UserModel;
use think\Validate;

class UserValidator extends Validate
{
    //全部规则
    protected $rule = [
        'user_id' => 'require|checkExist',
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
        'user_id.require' => '用户ID不能为空',
        'user_id.checkExist' => '用户ID不存在',
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

    protected function checkExist($value)
    {
        $userModel = new UserModel();
        return $userModel->where("id", $value)->find();
    }

    public function sceneList()
    {
        $this->rule = [];
    }

    public function sceneLogin()
    {
        $this->rule = [
            'username' => 'require',
            'password' => 'require',
        ];
    }

    public function sceneUpdate()
    {
        $this->rule = [
            'user_id' => 'require|checkExist',
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
    }

    public function sceneAdd()
    {
        $this->rule = [
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
    }
}
