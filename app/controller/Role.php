<?php

namespace app\controller;

use app\BaseController;
use app\model\OrderModel;
use app\model\RoleModel;
use app\Request;
use app\Response;

class Role extends BaseController
{
    public function index(Request $request): \think\response\View
    {
        $page_size = $request->get("page_size");
        $page = $request->get("page");
        if (empty($page_size)) {
            $page_size = 10;
        }
        if (empty($page)) {
            $page = 1;
        }

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
            'page' => $page
        ]);
    }


    public function add()
    {
    }

    public function edit()
    {
        
    }

    public function list()
    {
        $res = new Response();
        $roleModel = new RoleModel();
        $roles = $roleModel->select();
        return $res->ok("获取成功", $roles);
    }
}
