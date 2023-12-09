<?php

namespace app\model;

use think\Model;
use think\facade\Db;

class PermissionModel extends Model
{
    protected $table = "tb_permission";

    public function menus()
    {
        return $this->order("order asc")->select();
    }

    public function deletePermission($role_id, $permission_id)
    {
        return Db::table("tb_role_permission")
            ->where("role_id", $role_id)
            ->where("permission_id", $permission_id)
            ->delete();
    }

    public function addPermission($role_id, $permission_id)
    {
        return Db::table("tb_role_permission")
            ->save(["role_id" => $role_id, "permission_id" => $permission_id]);
    }

    //检查权限是否存在，存在返回true
    public function permissionExist($role_id, $permission_id)
    {
        $res = Db::table("tb_role_permission")
            ->where("role_id", $role_id)
            ->where("permission_id", $permission_id)
            ->find();
        return !empty($res);
    }
}
