<?php

namespace app\controller;

use app\BaseController;
use app\model\CartModel;
use app\model\CustomerModel;
use app\model\DeliveryModel;
use app\model\OrderModel;
use app\model\ProductModel;
use app\model\UserModel;
use app\Request;
use app\Response;
use app\validate\OrderValidator;

class Order extends BaseController
{
    public function count(Request $request)
    {
        $res = new Response();
        $user_id = $request->middleware("user_id");
        $orderModel = new OrderModel();
        $count = $orderModel->where("user_id", $user_id)->count();
        return $res->ok("获取成功", $count);
    }

    public function index(Request $request)
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
        
        $orderModel = new OrderModel();
        if ($role_id == 1) {
            $orders = $orderModel->where(['status' => [1, 2]])->select();
            $amount = $orderModel->where(['status' => [1, 2]])->count();
        } else {
            $user_id = $request->middleware("user_id");
            $orders = $orderModel->where('user_id', $user_id)->select();
            $amount = $orderModel->where('user_id', $user_id)->count();
        }

        return view('index', [
            'name' => $name,
            'select' => 'order',
            'role_id' => $role_id,
            'orders' => $orders,
            'amount' => $amount,
            'page_size' => $page_size,
            'page' => $page
        ]);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            //参数校验
            //参数过滤
            //服务
            //数据返回
            $customerModel = new CustomerModel();
            $productModel = new ProductModel();

            $customers = $customerModel->select();
            $products = $productModel->select();

            $role_id = $request->middleware("role_id");
            $name = $request->middleware("name");

            $user_id = $request->middleware("user_id");
            $cartModel = new CartModel();
            $carts = $cartModel->tempCartList($user_id);

            $amount = 0;
            foreach ($carts as $cart) {
                $amount = $amount + $cart['price'] * $cart['product_num'];
            }
            $deliveryModel = new DeliveryModel();
            $deliveryList = $deliveryModel->where('status', 1)->select();

            return view('add', [
                'name' => $name,
                'select' => 'order',
                'customers' => $customers,
                'products' => $products,
                'role_id' => $role_id,
                'carts' => $carts,
                'amount' => $amount,
                'deliveryList' => $deliveryList
            ]);
        }

        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new OrderValidator();
        $response = new Response();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }
        //根据customer_name和customer_phone查询顾客是否存在
        $customerModel = new CustomerModel();
        $customer = $customerModel
            ->where('cname', $postData['customer_name'])
            ->where('phone', $postData['customer_phone'])
            ->find();
        if (!$customer) {
            return $response->err('错误！顾客不存在，请检查顾客姓名和手机号');
        }
        $customer_id = $customer['cid'];

        $user_id = $request->middleware("user_id");
        $orderModel = new OrderModel();
        $postData['user_id'] = $user_id;
        $postData['customer_id'] = $customer_id;
        $postData['status'] = 0;  //录入
        $order = $orderModel->create($postData);
        if ($order) {
            $order_id = $order['id'];

            $cartModel = new CartModel();
            $cartModel->tempCartListSetOrderId($user_id, $order_id);;

            return $response->ok("订单创建成功");
        } else {
            return $response->err("订单创建失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            $customerModel = new CustomerModel();
            $productModel = new ProductModel();
            $deliveryModel = new DeliveryModel();

            $customers = $customerModel->select();  //顾客列表
            $products = $productModel->select();    //产品列表
            $deliveryList = $deliveryModel->where('status', 1)->select();   //快递列表

            $role_id = $request->middleware("role_id");
            $name = $request->middleware("name");
            $user_id = $request->middleware("user_id");

            return view('update', [
                'name' => $name,
                'select' => 'order',
                'customers' => $customers,
                'products' => $products,
                'role_id' => $role_id,
                'user_id' => $user_id,
                'deliveryList' => $deliveryList
            ]);
        }
        $response = new Response();
        $order_id = $request->post('order_id');
        if (empty($order_id)) {
            return $response->err("缺少订单id");
        }
        $postData = $request->post();
        // 更新订单信息
        $orderModel = new OrderModel();
        // 查找订单
        $item = $orderModel->where('id', $order_id)->find();

        if (!$item) {
            return $response->err('不存在的订单');
        }

        $result = $item->save($postData);
        if ($result) {
            return $response->ok("订单修改成功");
        } else {
            return $response->err("订单修改失败，请重试！");
        }

    }

    public function info(Request $request)
    {
        $res = new Response();
        $order_id = $request->post("order_id");
        $user_id = $request->post("user_id");
        if (empty($order_id) || empty($user_id)) {
            return $res->err("订单ID不存在");
        }

        $orderModel = new OrderModel();
        $order = $orderModel
            ->where("id", $order_id)
            ->where('user_id', $user_id)
            ->find();
        if (!$order) {
            return $res->err("订单不存在");
        }
        return $res->ok("获取成功", $order);
    }

    public function detailPage(Request $request)
    {
        $response = new Response();
        $order_id = $request->get('order_id');
        $user_id = $request->get('user_id');

        if (empty($order_id)) {
            return $response->errScript("缺少订单id");
        }

        if (empty($user_id)) {
            return $response->errScript("缺少用户id");
        }

        // 查找订单
        $orderModel = new OrderModel();
        $order = $orderModel->where('id', $order_id)->find();
        if (!$order) {
            return $response->errScript('不存在的订单');
        }

        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");
        $user_id = $request->middleware("user_id");
        $carts = [];
        $amount = 0;

        return view('detail', [
            'name' => $name,
            'select' => 'order',
            'role_id' => $role_id,
            'carts' => $carts,
            'amount' => $amount,
            'order' => $order
        ]);
    }


    //根据手机号查找客户
    public function phoneQuery(Request $request)
    {
        $res = new Response();
        $customer_phone = $request->post("customer_phone");
        $customerModel = new CustomerModel();
        $customer = $customerModel->where("phone", $customer_phone)->find();
        if ($customer) {
            return $res->ok("获取成功", $customer);
        } else {
            return $res->ok("获取失败");
        }
    }

    public function printPage(Request $request): \think\response\View
    {

        return view('print', [
            'name' => $request->middleware("name"),
            'role_id' => $request->middleware("role_id"),
            'select' => 'order'
        ]);
    }

    public function printInfo(Request $request)
    {
        $res = new Response();
        $customer_id = $request->post('customer_id');
        $order_id = $request->post('order_id');
        if (empty($customer_id) || empty($order_id)) {
            return $res->err('无效的顾客id和订单id');
        }
        $user_id = $request->middleware('user_id');
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
        $pay = 0;
        foreach ($orders as $order) {
            $pay += $order->product_num * $order->price;
        }
        $receiptInfo['pay'] = $pay;

        //商品信息
        $data = [
            "receiptInfo" => $receiptInfo,
            "orders" => $orders
        ];
        return $res->ok("获取成功", $data);
    }

    public function listGetByCustomerId(Request $request)
    {
        $res = new Response();
        $customer_id = $request->post('customer_id');
        $orderModel = new OrderModel();
        $orders = $orderModel->where("customer_id", $customer_id)->select();
        return $res->ok("获取成功", $orders);
    }

    public function list(Request $request)
    {
        $res = new Response();
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
        $data = [
            "orders" => $orders,
            "count" => $count,
            "amount" => $amount
        ];
        return $res->ok('获取成功', $data);
    }


    public function delete(Request $request)
    {
        $res = new Response();
        $order_id = $request->post('order_id');
        if (empty($order_id)) {
            return $res->err("缺少订单id");
        }

        $orderModel = new OrderModel();
        // 查找订单
        $order = $orderModel->find($order_id);
        if (!$order) {
            return $res->err('不存在的订单');
        }

        // 删除订单
        $result = $order->delete();
        if ($result) {
            return $res->ok('删除成功');
        } else {
            return $res->err('删除失败,请重试!');
        }
    }

    //确认订单：管理员
    public function confirm(Request $request): \think\Response
    {
        $response = new Response();
        $order_id = $request->post("order_id");
        if (empty($order_id)) {
            return $response->err("不存在的订单id");
        }

        $orderModel = new OrderModel();
        $order = $orderModel->where('id', $order_id)->find();
        if (!$order) {
            return $response->err("不存在的订单");
        }
        if ($order->save(["status" => 2])) {
            return $response->ok("订单确认成功");
        } else {
            return $response->err("订单确认失败，请重试");
        }
    }

    //提交订单
    public function submit(Request $request): \think\Response
    {
        $res = new Response();
        $order_id = $request->post("order_id");

        $orderModel = new OrderModel();
        $order = $orderModel->where('id', $order_id)->find();
        if (!$order) {
            return $res->err("不存在的订单");
        }

        $commit_at = date('Y-m-d');
        if ($order->save([
            "status" => 1,
            "commit_at" => $commit_at
        ])) {
            return $res->ok("订单提交成功");
        } else {
            return $res->err("订单提交失败，请重试");
        }
    }
}
