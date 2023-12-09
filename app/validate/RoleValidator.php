<?php

namespace app\validate;

use think\Validate;

class RoleValidator extends Validate
{
    protected $rule = [
        'role_id' => 'require|int',
        'name' => 'require|unique:tb_role'
    ];

    protected $message = [
        'role_id.require' => '角色ID不能为空',
        'role_id.int' => '角色ID格式不正确',
        'name.require' => '角色名称不能为空',
        'name.unique' => '名称以已存在',
    ];

    protected function sceneAdd()
    {
        $this->rule = ['name' => 'require|unique:tb_role'];
    }

    protected function sceneDelete()
    {
        $this->rule = ['name' => 'require'];
    }

    protected function sceneList()
    {
        $this->rule = [];
    }

    protected function sceneUpdate()
    {
        $this->rule = [
            'role_id' => 'require',
            'name' => 'require|unique:tb_role'
        ];
    }

    protected function sceneInfo()
    {
        $this->rule = ['role_id' => 'require'];
    }

    protected function sceneDisable()
    {
        $this->rule = ['role_id' => 'require'];
    }

    protected function sceneEnable()
    {
        $this->rule = ['role_id' => 'require'];
    }
}
