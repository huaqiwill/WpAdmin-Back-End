<?php

namespace app\controller;

use app\BaseController;
use app\model\CustomerModel;
use app\model\OrderModel;
use app\Request;
use app\Response;
use app\validate\CustomerValidator;

class Customer extends BaseController
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

        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");
        $user_id = $request->middleware("user_id");

        $customerModel = new CustomerModel();
        $customers = $customerModel->where("user_id", $user_id)->select();
        $amount = $customerModel->where("user_id", $user_id)->count();

        return view('index', [
            'name' => $name,
            'select' => 'customer',
            'customers' => $customers,
            'role_id' => $role_id,
            'amount' => $amount,
            'page_size' => $page_size,
            'page' => $page
        ]);
    }

    public function phoneList(Request $request)
    {
        $res = new Response();
        $name = $request->post("name");
        if (empty($name)) {
            return $res->err("请输入顾客姓名");
        }
        $user_id = $request->middleware("user_id");
        $customerModel = new CustomerModel();
        $phoneList = $customerModel
            ->where("user_id", $user_id)
            ->where("cname", $name)
            ->column('phone');
        return $res->ok("获取成功", $phoneList);
    }

    public function count(Request $request)
    {
        $res = new Response();
        $user_id = $request->middleware("user_id");
        $customerModel = new CustomerModel();
        $amount = $customerModel->where("user_id", $user_id)->count();
        return $res->ok("获取成功", $amount);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            $name = $request->middleware("name");
            $role_id = $request->middleware("role_id");
            return view('add', [
                'name' => $name,
                'select' => 'customer',
                'role_id' => $role_id
            ]);
        }

        //新增
        $response = new Response();
        $postData = $request->post();
        $validator = new CustomerValidator();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }

        $user_id = $request->middleware("user_id");
        $postData['user_id'] = $user_id;

        $itemModel = new CustomerModel();
        $item = $itemModel->create($postData);
        if ($item) {
            return $response->ok("顾客新增成功", $item);
        } else {
            return $response->err("顾客新增失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            $response = new Response();

            $customer_id = $request->get("customer_id");
            if (empty($customer_id)) {
                return $response->errScript("无效的id");
            }

            $customerModel = new CustomerModel();
            $customer = $customerModel->where('cid', $customer_id)->find();

            if (!$customer) {
                return $response->errScript("数据不存在");
            }

            $name = $request->middleware("name");
            $role_id = $request->middleware("role_id");
            return view('update', [
                'name' => $name,
                'select' => 'customer',
                'role_id' => $role_id,
                'customer' => $customer
            ]);
        }

        $response = new Response();
        $customer_id = $request->post('cid');
        if (empty($customer_id)) {
            return $response->err("请输入顾客id");
        }
        $postData = $request->post();
        $validator = new CustomerValidator();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }

        // 更新订单信息
        $itemModel = new CustomerModel();
        // 查找订单
        $item = $itemModel->where('cid', $customer_id)->find();

        if (!$item) {
            return $response->err('不存在的顾客');
        }

        $result = $item->save($postData);
        if ($result) {
            return $response->ok("顾客信息修改成功");
        } else {
            return $response->err("顾客信息修改失败，请重试！");
        }

    }



    public function list(Request $request)
    {
        $res = new Response();

        $page = $request->post("page", 1);
        $page_size = $request->post("page_size", 10);
        $wechat = $request->post("wechat", "");
        $phone = $request->post("phone", "");

        $user_id = $request->middleware("user_id");
        $role_id = $request->middleware("role_id");

        $customerModel = new CustomerModel();
        if ($role_id == 1) {
            //所有顾客信息
            list($customers, $count) = $customerModel->adminList($page, $page_size, $wechat, $phone);
        } else if ($role_id == 2) {
            //所有老板的顾客
            list($customers, $count) = $customerModel->brandList($user_id, $page, $page_size, $wechat, $phone);
        } else if ($role_id == 3) {
            //老板所有业务员的顾客
            list($customers, $count) = $customerModel->bossList($user_id, $page, $page_size, $wechat, $phone);
        } else {
            //业务员顾客
            list($customers, $count) = $customerModel->salesList($user_id, $page, $page_size, $wechat, $phone);
        }

        //计算搭配总额
        $orderModel = new OrderModel();
        foreach ($customers as $key => $customer) {
            $customer_id = $customer->cid;
            $amount = $orderModel->where("customer_id", $customer_id)->sum("amount");
            $customers[$key]['amount'] = $amount;
        }

        $data = [
            "customers" => $customers,
            "count" => $count
        ];
        return $res->ok('获取成功', $data);
    }

    public function getByPhone(Request $request)
    {
        $res = new Response();
        $phone = $request->post("phone");
        if (empty($phone)) {
            return $res->err("请输入顾客手机号");
        }

        $customerModel = new CustomerModel();
        $customer = $customerModel
            ->where("phone", $phone)
            ->find();
        if (!$customer) {
            return $res->err("获取顾客信息失败");
        }
        return $res->ok("获取成功", $customer);
    }

    public function nameList(Request $request)
    {
        $res = new Response();
        $user_id = $request->middleware("user_id");
        $customerModel = new CustomerModel();
        $nameList = $customerModel
            ->where("user_id", $user_id)
            ->column("cname");

        return $res->ok("获取成功", $nameList);
    }

    public function info(Request $request)
    {
        $response = new Response();
        $customer_id = $request->post('customer_id');
        if (empty($customer_id)) {
            return $response->err("缺少顾客id");
        }

        // 查找订单
        $itemModel = new CustomerModel();
        $item = $itemModel->where('cid', $customer_id)->find();
        if (!$item) {
            return $response->err('不存在的订单');
        }

        return $response->ok('查询成功', $item);
    }

    public function find(Request $request)
    {
        $response = new Response();
        $wechat = input('post.wechat');
        $phone = input('post.phone');
        $id = input('get.id');
        if (empty($id)) {
            return $response->err("缺少id");
        }
        $model = new CustomerModel();
        $list = $model->where([
            'wechat' => ['like', "%$wechat"],
            'phone' => ['like', "%$phone"]
        ])->select();
        if ($list) {
            return $response->ok('获取成功', $list);
        } else {
            return $response->err('查询失败');
        }
    }



    public function delete(Request $request)
    {
        $response = new Response();
        $customer_id = $request->post('customer_id');
        if (empty($customer_id)) {
            return $response->err("缺少顾客id");
        }

        $customerModel = new CustomerModel();
        // 查找顾客
        $item = $customerModel->where('cid', $customer_id)->find();

        if (!$item) {
            return $response->err('不存在的顾客');
        }

        // 删除顾客
        $result = $item->delete();
        if ($result) {
            return $response->ok('删除成功');
        } else {
            return $response->err('删除失败,请重试!');
        }
    }


    //录入订单
    public function entry()
    {
    }
}
