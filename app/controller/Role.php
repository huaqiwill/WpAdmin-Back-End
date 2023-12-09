<?php

namespace app\controller;

use app\BaseController;
use app\model\PermissionModel;
use app\model\RoleModel;
use app\Request;
use app\Response;

class Role extends BaseController
{
    public function index(Request $request): \think\response\View
    {
        $menus = $request->middleware("menus");

        $page_size = $request->get("page_size", 10);
        $page = $request->get("page", 1);

        $name = $request->middleware("name");
        $role_id = $request->middleware("role_id");
        $roleModel = new RoleModel();
        $roles = $roleModel->select();

        return view('index', [
            'name' => $name,
            'select' => 'role',
            'role_id' => $role_id,
            'roles' => $roles,
            'page_size' => $page_size,
            'page' => $page,
            'menus' => $menus
        ]);
    }

    public function update(Request $request)
    {
        $role_id = $request->post("role_id");
        $roleModel = new RoleModel();
        $result =  $roleModel->where("id", $role_id)->update(["name" => $request->post("name")]);
        if ($result) {
            return ok("更新成功", $result);
        } else {
            return err("更新失败");
        }
    }

    public function add(Request $request)
    {
        $roleModel = new RoleModel();
        $result =  $roleModel->create($request->post());
        if ($result) {
            return ok("添加成功", $result);
        } else {
            return err("添加失败");
        }
    }

    public function disable(Request $request)
    {
        $role_id = $request->post("role_id");
        $roleModel = new RoleModel();
        $result =  $roleModel->where("id", $role_id)->update(["status" => 0]);
        if ($result) {
            return ok("禁用成功");
        } else {
            return err("禁用失败");
        }
    }

    public function enable(Request $request)
    {
        $role_id = $request->post("role_id");
        $roleModel = new RoleModel();
        $result =  $roleModel->where("id", (int)$role_id)->update(["status" => 1]);
        if ($result) {
            return ok("启用成功");
        } else {
            return err("启用失败");
        }
    }

    public function edit()
    {
    }

    public function info(Request $request)
    {
        $role_id = $request->post("role_id");
        $roleModel = new RoleModel();
        $result =  $roleModel->where("id", $role_id)->find();
        if ($result) {
            return ok("获取成功", $result);
        } else {
            return err("获取失败");
        }
    }

    public function pages()
    {
    }

    public function sidebar(Request $request)
    {
        $permissionModel = new PermissionModel();
        $menus = $permissionModel->order("order asc")->select();
        return view("sidebar", ['menus' => $menus]);
    }

    public function list()
    {
        $res = new Response();
        $roleModel = new RoleModel();
        $roles = $roleModel->select();
        return $res->ok("获取成功", $roles);
    }
}
