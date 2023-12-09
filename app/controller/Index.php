<?php

namespace app\controller;

use app\BaseController;
use app\model\CustomerModel;
use app\model\OrderModel;
use app\model\PermissionModel;
use app\model\ProductModel;
use app\model\UserModel;
use app\Request;

class Index extends BaseController
{
    public function index(Request $request)
    {
        $menus = $request->middleware("menus");
        return view('index', [
            'name' => $request->middleware("name"),
            'select' => 'home',
            'role_id' => $request->middleware("role_id"),
            'menus' => $menus
        ]);
    }

    public function static(Request $request)
    {
        $path = $request->param("path");
        $path =  app()->getRootPath() . "static\\" . str_replace("/", "\\", $path);
        if (is_file($path) && file_exists($path)) {
            //读文件
            $fileContent = file_get_contents($path);
        } else {
            //404
            $fileContent = file_get_contents(app()->getRootPath() . "static\\error\\404.html");
        }
        return $fileContent;
    }

    public function statisticList(Request $request)
    {
        $orderModel = new OrderModel();
        $statistic_order_count = [
            "name" => "订单数量",
            "count" => $orderModel->count(),
            "percentage" => 35,
        ];

        $customerModel = new CustomerModel();
        $statistic_customer_count = [
            "name" => "顾客数量",
            "count" => $customerModel->count(),
            "percentage" => 75,
        ];

        $productModel = new ProductModel();
        $statistic_product_count = [
            "name" => "商品数量",
            "count" => $productModel->count(),
            "percentage" => 25,
        ];

        $userModel = new UserModel();
        $statistic_user_count = [
            "name" => "人员数量",
            "count" => $userModel->count(),
            "percentage" => 65,
        ];

        $statistic_order_amount = [
            "name" => "订单总金额",
            "count" => $orderModel->sum("amount"),
            "percentage" => 85,
        ];

        $data = [
            $statistic_order_count,
            $statistic_customer_count,
            $statistic_product_count,
            $statistic_order_amount,
            $statistic_user_count,
        ];
        return ok("获取成功", $data);
    }
}
