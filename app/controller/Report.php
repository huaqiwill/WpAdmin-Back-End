<?php

namespace app\controller;

use app\model\CustomerModel;
use app\model\OrderModel;
use app\model\PermissionModel;
use app\model\RoleModel;
use app\model\UserModel;
use app\Request;
use app\Response;

class Report
{
    public function index(Request $request)
    {
        $menus = $request->middleware("menus");
        
        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");
        return view('index', [
            'name' => $name,
            'select' => 'report',
            'role_id' => $role_id,
            'menus' => $menus
        ]);
    }

    //员工业绩列表
    public function list(Request $request)
    {
    }

    public function userAchievement(Request $request)
    {
        $res = new Response();
        //员工姓名，业绩，顾客数，订单数
        $userModel = new UserModel();
        $user_id_list = $userModel->column('id');
        $customerModel = new CustomerModel();
        $orderModel = new OrderModel();
        $achievement_list = [];
        foreach ($user_id_list as $user_id) {
            $name = $userModel->where('id', $user_id)->value('username');
            $achievement = $orderModel->where("user_id", $user_id)->sum("amount");
            $customer_count = $customerModel->where('user_id', $user_id)->count();
            $order_count = $orderModel->where('user_id', $user_id)->count();

            $achievement = [
                "name" => $name,
                "achievement" => $achievement,
                "customer_count" => $customer_count,
                "order_count" => $order_count
            ];
            $achievement_list[] =  $achievement;
        }

        return $res->ok("获取成功", $achievement_list);
    }
}
