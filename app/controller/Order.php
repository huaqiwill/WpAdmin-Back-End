<?php

namespace app\controller;

use app\BaseController;
use app\model\CartModel;
use app\model\CustomerModel;
use app\model\OrderModel;
use app\Request;
use app\Response;

class Order extends BaseController
{
    //订单总数
    public function count(Request $request)
    {
        $res = new Response();
        $user_id = $request->middleware("user_id");
        $orderModel = new OrderModel();
        $count = $orderModel->where("user_id", $user_id)->count();
        return $res->ok("获取成功", $count);
    }

    //订单首页
    public function index(Request $request)
    {
        return view('index', [
            'name' => $request->middleware("name"),
            'select' => 'order',
            'role_id' => $request->middleware("role_id"),
            'menus' => $request->middleware("menus")
        ]);
    }

    //新增订单
    public function add(Request $request)
    {
        if ($request->isGet()) {
            return view('add', [
                'name' =>  $request->middleware("name"),
                'select' => 'order',
                'role_id' => $request->middleware("role_id"),
                'menus' => $request->middleware("menus")
            ]);
        }

        $postData = $request->post();
        $user_id = $request->middleware("user_id");

        //根据customer_name和customer_phone查询顾客是否存在
        $customerModel = new CustomerModel();
        $customer = $customerModel
            ->where('cname', $postData['customer_name'])
            ->where('phone', $postData['customer_phone'])
            ->find();
        if (!$customer) {
            return err('错误！顾客不存在，请检查顾客姓名和手机号');
        }

        $customer_id = $customer['cid'];
        $postData['user_id'] = $user_id;
        $postData['customer_id'] = $customer_id;
        $postData['status'] = 0;  //录入
        $orderModel = new OrderModel();
        $order = $orderModel->create($postData);
        if ($order) {
            $order_id = $order['id'];

            $cartModel = new CartModel();
            $cartModel->tempCartListSetOrderId($user_id, $order_id);;

            return ok("订单创建成功");
        } else {
            return err("订单创建失败，请重试！");
        }
    }

    //修改订单
    public function update(Request $request)
    {
        if ($request->isGet()) {
            return view('update', [
                'name' => $request->middleware("name"),
                'select' => 'order',
                'role_id' => $request->middleware("role_id"),
                'user_id' => $request->middleware("user_id"),
                'menus' => $request->middleware("menus")
            ]);
        }

        $order_id = $request->post('order_id');
        //修改
        $postData = $request->post();
        $orderModel = new OrderModel();
        $result = $orderModel->where('id', $order_id)->update($postData);
        if ($result) {
            return ok("订单修改成功");
        } else {
            return err("订单修改失败，请重试！");
        }
    }

    //订单信息
    public function info(Request $request)
    {
        $order_id = $request->post("order_id");
        $user_id = $request->post("user_id");

        $orderModel = new OrderModel();
        $order = $orderModel
            ->where("id", $order_id)
            ->where('user_id', $user_id)
            ->find();

        if (!$order) {
            return err("订单不存在");
        }
        return ok("获取成功", $order);
    }

    //订单详情页
    public function detail(Request $request)
    {
        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");

        return view('detail', [
            'name' => $name,
            'select' => 'order',
            'role_id' => $role_id,
            'menus' => $request->middleware("menus")
        ]);
    }

    //根据手机号查找客户订单
    public function phoneQuery(Request $request)
    {
        $customer_phone = $request->post("customer_phone");
        //查询
        $customerModel = new CustomerModel();
        $customer = $customerModel->where("phone", $customer_phone)->find();
        if ($customer) {
            return ok("获取成功", $customer);
        } else {
            return ok("获取失败");
        }
    }

    public function print(Request $request): \think\response\View
    {
        return view('print', [
            'name' => $request->middleware("name"),
            'role_id' => $request->middleware("role_id"),
            'select' => 'order',
            'menus' => $request->middleware("menus")
        ]);
    }

    public function printInfo(Request $request)
    {
        $customer_id = $request->post('customer_id');
        $order_id = $request->post('order_id');
        $user_id = $request->post('user_id');
        //查询
        $customerModel = new CustomerModel();
        $cartModel = new CartModel();
        $customer = $customerModel->where('cid', $customer_id)->find();
        $orders = $cartModel->cartList($user_id, $order_id);
        //顾客信息
        $receiptInfo = [];
        $receiptInfo['address'] = $customer->province . $customer->city . $customer->prefecture . $customer->address;
        $receiptInfo['name'] = $customer['cname'];
        $receiptInfo['phone'] = $customer['phone'];
        $receiptInfo['order_no'] = rand(11111111111111, 99999999999999);
        //计算订单需要支付的金额
        $pay = 0;
        foreach ($orders as $order) {
            $pay += $order['product_num'] * $order['price'];
        }
        $receiptInfo['pay'] = $pay;
        //商品信息
        $data = [
            "receiptInfo" => $receiptInfo,
            "orders" => $orders
        ];
        return ok("获取成功", $data);
    }

    //获取顾客的订单列表
    public function listGetByCustomerId(Request $request)
    {
        $customer_id = $request->post('customer_id');
        //查询
        $orderModel = new OrderModel();
        $orders = $orderModel->where("customer_id", $customer_id)->select();
        return ok("获取成功", $orders);
    }

    //订单列表
    public function list(Request $request)
    {
        //参数
        $page = $request->post("page", 1);
        $page_size = $request->post("page_size", 10);
        $name = $request->post("name", "");
        $phone = $request->post("phone", "");
        //中间件
        $role_id = $request->middleware("role_id");
        $user_id = $request->middleware("user_id");
        //模型
        $orderModel = new OrderModel();
        //查询
        if ($role_id == 1) {
            //超级管理员：可以获取全部订单信息
            list($orders, $count, $amount) = $orderModel->adminList($page, $page_size, $name, $phone);
        } else if ($role_id == 2) {
            //品牌方：可以获取所属品牌的全部订单信息
            list($orders, $count, $amount) = $orderModel->brandList($user_id, $page, $page_size, $name, $phone);
        } else if ($role_id == 3) {
            //老板：可以获取所有业务员的订单的信息
            list($orders, $count, $amount) = $orderModel->bossList($user_id, $page, $page_size, $name, $phone);
        } else {
            //业务员：可以获取自己的订单信息
            list($orders, $count, $amount) = $orderModel->salesList($user_id, $page, $page_size, $name, $phone);
        }
        //结果
        $data = [
            "orders" => $orders,
            "count" => $count,
            "amount" => $amount
        ];
        return ok('获取成功', $data);
    }

    //删除订单
    public function delete(Request $request)
    {
        $order_id = $request->post('order_id');

        $orderModel = new OrderModel();
        $result = $orderModel->where('id', $order_id)->delete();
        if ($result) {
            return ok('删除成功');
        } else {
            return err('删除失败,请重试!');
        }
    }

    //确认订单：管理员
    public function confirm(Request $request): \think\Response
    {
        $order_id = $request->post("order_id");

        $orderModel = new OrderModel();
        $order = $orderModel->where('id', $order_id)->update(["status" => 2]);
        if ($order) {
            return ok("订单确认成功");
        } else {
            return err("订单确认失败，请重试");
        }
    }

    //提交订单
    public function submit(Request $request): \think\Response
    {
        $order_id = $request->post("order_id");

        $orderModel = new OrderModel();
        $commit_at = date('Y-m-d');
        $order = $orderModel->where('id', $order_id)->update([
            "status" => 1,
            "commit_at" => $commit_at
        ]);

        if ($order) {
            return ok("订单提交成功");
        } else {
            return err("订单提交失败，请重试");
        }
    }
}
