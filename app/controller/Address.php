<?php

namespace app\controller;

use app\BaseController;
use app\model\AddressModel;
use app\model\PermissionModel;
use app\Request;

class Address extends BaseController
{
    public function index(Request $request)
    {
        $menus = $request->middleware("menus");

        return view('index', [
            'name' => $request->middleware('name'),
            'role_id' => $request->middleware("role_id"),
            'select' => 'address',
            'menus' => $menus
        ]);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            return view('add', [
                'customer_id' => $request->get("id"),
                'name' => $request->middleware('name'),
                'role_id' => $request->middleware("role_id"),
                'select' => 'address'
            ]);
        }

        $data = $request->post();
        $addressModel = new AddressModel();
        $result = $addressModel->create($data);
        if (!$result) {
            return err("新增失败");
        }
        return ok("新增成功", $result);
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

        $postData = $request->post();
        $addressModel = new AddressModel();
        $result = $addressModel
            ->where('id', $postData['address_id'])
            ->update($postData);
        if (!$result) {
            return err("修改失败");
        }
        return ok("修改成功", $result);
    }

    public function edit(Request $request)
    {
        $address_id = $request->get("address_id");
        return view('edit_detail', [
            'address_id' => $address_id
        ]);
    }

    public function list(Request $request)
    {
        $customer_id = $request->post("customer_id");
        $page = $request->post("page", 1);
        $page_size = $request->post("page_size", 10);

        $addressModel = new AddressModel();
        if (empty($customer_id)) {
            //获取所属业务员的全部客户的地址列表
            $address_list = $addressModel
                ->limit(($page - 1) * $page_size, $page_size)
                ->select();
            $count = $addressModel->count();
        } else {
            //获取单个客户的地址列表
            $address_list = $addressModel
                ->where('customer_id', $customer_id)
                ->limit(($page - 1) * $page_size, $page_size)
                ->select();
            $count = $addressModel->where('customer_id', $customer_id)->count();
        }

        $data = [
            "address_list" => $address_list,
            "count" => $count
        ];

        return ok("获取成功", $data);
    }

    public function info(Request $request)
    {
        $address_id = $request->post("address_id");
        $addressModel = new AddressModel();
        $address = $addressModel->where('id', $address_id)->find();
        return ok("获取成功", $address);
    }

    public function delete(Request $request)
    {
        $address_id = $request->post("address_id");
        $addressModel  = new AddressModel();
        $result = $addressModel->where('id', $address_id)->delete();
        if (!$result) {
            return err("删除失败");
        }
        return ok("删除成功");
    }
}
