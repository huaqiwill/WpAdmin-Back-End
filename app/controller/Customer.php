<?php

namespace app\controller;

use app\BaseController;
use app\model\CustomerModel;
use app\model\OrderModel;
use app\model\PermissionModel;
use app\Request;
use app\Response;
use app\validate\CustomerValidator;

class Customer extends BaseController
{
    public function index(Request $request)
    {
        return view('index', [
            'name' => $request->middleware("name"),
            'select' => 'customer',
            'role_id' =>  $request->middleware("role_id"),
            'menus' => $request->middleware("menus")
        ]);
    }

    public function phoneList(Request $request)
    {
        $name = $request->post("name");
        $user_id = $request->middleware("user_id");

        $customerModel = new CustomerModel();
        $phoneList = $customerModel
            ->where("user_id", $user_id)
            ->where("cname", $name)
            ->column('phone');
        return ok("获取成功", $phoneList);
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
            return view('add', [
                'name' => $request->middleware("name"),
                'select' => 'customer',
                'role_id' => $request->middleware("role_id"),
                'menus' => $request->middleware("menus")
            ]);
        }

        //新增
        $postData = $request->post();

        $user_id = $request->middleware("user_id");
        $postData['user_id'] = $user_id;

        $itemModel = new CustomerModel();
        $item = $itemModel->create($postData);
        if ($item) {
            return ok("顾客新增成功", $item);
        } else {
            return err("顾客新增失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            $name = $request->middleware("name");
            $role_id = $request->middleware("role_id");
            return view('update', [
                'name' => $name,
                'select' => 'customer',
                'role_id' => $role_id,
                'menus' => $request->middleware("menus")
            ]);
        }

        $customer_id = $request->post('cid');
        $itemModel = new CustomerModel();
        $result = $itemModel->where('cid', $customer_id)->update($request->post());
        if ($result) {
            return ok("顾客信息修改成功");
        } else {
            return err("顾客信息修改失败，请重试！");
        }
    }

    public function list(Request $request)
    {
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
        return ok('获取成功', $data);
    }

    public function getByPhone(Request $request)
    {
        $phone = $request->post("phone");

        $customerModel = new CustomerModel();
        $customer = $customerModel
            ->where("phone", $phone)
            ->find();

        if (!$customer) {
            return err("获取顾客信息失败");
        }
        return ok("获取成功", $customer);
    }

    public function nameList(Request $request)
    {
        $user_id = $request->middleware("user_id");

        $customerModel = new CustomerModel();
        $nameList = $customerModel
            ->where("user_id", $user_id)
            ->column("cname");

        return ok("获取成功", $nameList);
    }

    public function info(Request $request)
    {
        $customer_id = $request->post('customer_id');

        // 查找订单
        $itemModel = new CustomerModel();
        $item = $itemModel->where('cid', $customer_id)->find();
        if (!$item) {
            return err('不存在的订单');
        }

        return ok('查询成功', $item);
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
        $customer_id = $request->post('customer_id');

        $customerModel = new CustomerModel();
        $result = $customerModel->where('cid', $customer_id)->delete();
        if ($result) {
            return ok('删除成功');
        } else {
            return err('删除失败,请重试!');
        }
    }


    //录入订单
    public function entry()
    {
    }
}
