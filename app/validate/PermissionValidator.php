<?php

namespace app\validate;

use app\model\PermissionModel;
use think\Validate;

class PermissionValidator extends Validate
{
    protected $rule = [
        'permission_id' => 'require|checkIdExist',
        'name' => 'require|checkNameExist',
        'path' => 'require',
        'role_id' => 'require',
    ];

    protected $message = [
        'permission_id.require' => '权限ID不能为空',
        'permission_id.checkExist' => '权限ID不存在',
        'role_id.require' => '角色ID不能为空',
        'id.require' => '权限ID不能为空',
        'name.require' => '权限名称不能为空',
        'name.checkNameExist' => '权限名称不存在',
        'path.require' => '权限路径不能为空',
        'order.require' => '排序不能为空'
    ];

    protected function sceneNameList()
    {
        $this->rule = [];
    }

    protected function checkIdExist($value)
    {
        $model = new PermissionModel();
        return !empty($model->where("id", $value)->find());
    }

    protected function checkNameExist($value)
    {
        $model = new PermissionModel();
        return !empty($model->where("name", $value)->find());
    }

    protected function sceneAdd()
    {
        $this->rule = [
            'name' => 'require|checkNameExist',
            'role_id' => 'require'
        ];
    }

    protected function sceneList()
    {
        $this->rule = [
            'role_id' => 'require'
        ];
    }

    protected function sceneUpdate()
    {
        $this->rule = [
            'permission_id' => 'require|checkIdExist',
            'order' => 'require'
        ];
    }

    protected function sceneDelete()
    {
        $this->rule = [
            'permission_id' => 'require',
            'role_id' => 'require'
        ];
    }

    protected function sceneInfo()
    {
        $this->rule = ['permission_id' => 'require|checkIdExist',];
    }
    protected function sceneEnable()
    {
        $this->rule = ['permission_id' => 'require|checkIdExist',];
    }
    protected function sceneDisable()
    {
        $this->rule = ['permission_id' => 'require|checkIdExist',];
    }
}
