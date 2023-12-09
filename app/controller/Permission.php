<?php

namespace app\controller;

use app\BaseController;
use app\model\PermissionModel;
use app\model\RoleModel;
use app\Request;

class Permission extends BaseController
{

    public function index(Request $request)
    {
        return view("index");
    }

    public function list(Request $request)
    {
        $roleModel = new RoleModel();
        $role_id = $request->post("role_id");
        $permission_list = $roleModel->permissionList($role_id);
        return ok("获取成功", $permission_list);
    }

    public function menus(Request $request)
    {
        // 获取角色 ID
        $role_id = $request->middleware("role_id");

        $permissionModel = new PermissionModel();

        // 从数据库中根据角色 ID 获取权限列表
        $menu_list = $permissionModel->where("role_id", $role_id)->select();

        // 初始化二级菜单
        $menus = [];

        // 组织菜单为二级菜单结构
        foreach ($menu_list as $menu) {
            // 如果是一级菜单
            if ($menu->parent_id == 0) {
                $subMenus = [];
                // 遍历权限列表，查找对应的二级菜单
                foreach ($menu_list as $subMenu) {
                    if ($subMenu->parent_id == $menu->id) {
                        $subMenus[] = $subMenu;
                    }
                }
                // 将一级菜单和对应的二级菜单组合成二级菜单结构
                $menus[] = [
                    'main_menu' => $menu,
                    'sub_menus' => $subMenus
                ];
            }
        }

        // 返回菜单数据
        return ok("获取成功", $menus);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            return view('add');
        }
        $name = $request->post("name");
        $role_id = $request->post("role_id");

        $permissionModel = new PermissionModel();
        //通过name获取permission_id
        $permission_id = $permissionModel->where("name", $name)->find()->id;

        $exits = $permissionModel->permissionExist($role_id, $permission_id);
        if ($exits) {
            return err("权限已经存在，无需重复添加");
        }

        $result = $permissionModel->addPermission($role_id, $permission_id);
        if ($result) {
            return  ok('新增用户权限成功', $result);
        } else {
            return err('新增用户权限失败，请重试！');
        }
    }

    public function delete(Request $request)
    {
        $permission_id = $request->post("permission_id");
        $role_id = $request->post("role_id");
        $permissionModel = new PermissionModel();
        $res = $permissionModel->deletePermission($role_id, $permission_id);
        if ($res) {
            return ok("删除成功");
        } else {
            return err("删除失败");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            return view('update');
        }
        $permission_id = $request->post("permission_id");
        $permissionModel = new PermissionModel();
        $res =  $permissionModel->where("id", $permission_id)->update($request->post());
        if ($res) {
            return $res->ok("修改成功");
        } else {
            return $res->err("修改失败，请重试");
        }
    }

    public function namelist(Request $request)
    {
        $permissionModel = new PermissionModel();
        $name_list = $permissionModel->column("name");
        return ok("获取成功", $name_list);
    }

    public function info(Request $request)
    {
        $permission_id = $request->post("permission_id");
        $permissionModel = new PermissionModel();
        $result = $permissionModel->where("id", $permission_id)->find();
        return ok("获取成功", $result);
    }

    public function enable(Request $request)
    {
        $permission_id = $request->post("id");
        $permissionModel = new PermissionModel();
        $result = $permissionModel->where("id", $permission_id)->update(["status", 1]);
        if ($result) {
            return ok("启用成功");
        } else {
            return err("启用失败");
        }
    }

    public function disable(Request $request)
    {
        $permission_id = $request->post("id");
        $permissionModel = new PermissionModel();
        $result = $permissionModel->where("id", $permission_id)->update(["status", 0]);
        if ($result) {
            return ok("启用成功");
        } else {
            return err("启用失败");
        }
    }
}
