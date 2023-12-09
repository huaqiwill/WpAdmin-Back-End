<?php

namespace app\model;

use think\Model;
use think\facade\Db;

class RoleModel extends Model
{
    protected $table = "tb_role";

    public function permissionList($role_id)
    {
        return Db::table("tb_role_permission")
            ->alias("r")
            ->join("tb_permission p", 'r.permission_id=p.id')
            ->where("role_id", $role_id)
            ->order("order asc")
            ->select();
    }
}
