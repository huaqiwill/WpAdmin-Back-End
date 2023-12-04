<?php

namespace app\controller;

use app\BaseController;
use app\model\AddressModel;
use app\Request;
use app\Response;

class Address extends BaseController
{
    public function index(Request $request)
    {
        return view('index', [
            'name' => $request->middleware('name'),
            'role_id' => $request->middleware("role_id"),
            'select' => 'address'
        ]);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            return view('add', [
                'name' => $request->middleware('name'),
                'role_id' => $request->middleware("role_id"),
                'select' => 'address'
            ]);
        }

        $res = new Response();
        $postData = $request->post();

        $model = new AddressModel();
        if (!$model->save($postData)) {
            return $res->err("新增失败");
        }
        return $res->ok("新增成功");
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            return view('update', [
                'name' => $request->middleware('name'),
                'role_id' => $request->middleware("role_id"),
                'select' => 'address'
            ]);
        }

        $res = new Response();
        $address_id = $request->post("address_id");
        $postData = $request->post();
        $model = new AddressModel();
        $address = $model->where('id', $address_id)->find();
        if (!$address->save($postData)) {
            return $res->err("修改失败");
        }
        return $res->ok("修改成功", $address);
    }

    public function editDetail(Request $request)
    {
        $address_id = $request->get("address_id");
        if (empty($address_id)) {
            return "无效的ID";
        }
        return view('edit_detail', [
            'address_id' => $address_id
        ]);
    }

    public function list(Request $request)
    {
        $res = new Response();
        $customer_id = $request->post("customer_id");
        $model = new AddressModel();
        if (empty($customer_id)) {
            $address_list = $model->select();
            $count = $model->count();
        } else {
            $address_list = $model->where('customer_id', $customer_id)->select();
            $count = $model->where('customer_id', $customer_id)->count();
        }

        $data = [
            "address_list" => $address_list,
            "count" => $count
        ];

        return $res->ok("获取成功", $data);
    }

    public function info(Request $request)
    {
        $res = new Response();
        $address_id = $request->post("address_id");
        $model = new AddressModel();
        $address = $model->where('id', $address_id)->find();
        return $res->ok("获取成功", $address);
    }

    public function delete(Request $request)
    {
        $res = new Response();
        $address_id = $request->post("address_id");
        $model = new AddressModel();
        $address = $model->where('id', $address_id)->find();
        if (!$address->delete()) {
            return $res->err("删除失败");
        }
        return $res->ok("删除成功", $address);
    }
}
