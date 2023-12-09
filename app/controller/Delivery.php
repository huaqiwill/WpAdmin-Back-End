<?php

namespace app\controller;

use app\BaseController;
use app\model\DeliveryModel;
use app\model\PermissionModel;
use app\Request;

class Delivery extends BaseController
{
    public function index(Request $request)
    {
        $menus = $request->middleware("menus");

        return view('index', [
            'name' => $request->middleware("name"),
            'select' => 'delivery',
            'role_id' => $request->middleware("role_id"),
            'menus' => $menus
        ]);
    }

    public function list()
    {
        $deliveryModel = new DeliveryModel();
        $delivery_list = $deliveryModel->where("status", 1)->select();
        return ok("获取成功", $delivery_list);
    }

    public function add(Request $request)
    {
        $delivery = $request->post();
        $deliveryModel = new DeliveryModel();
        $delivery['status'] = 1;    //默认启用
        $result = $deliveryModel->create($delivery);
        if ($result) {
            return ok("添加成功", $result);
        } else {
            return err("添加失败");
        }
    }

    public function nameList()
    {
        $model = new DeliveryModel();
        $names = $model->where('status', 1)->column("name");
        return ok("获取成功", $names);
    }

    public function update(Request $request)
    {
        //修改快递名称
        $name = $request->post("name");
        $delivery_id = $request->post("delivery_id");

        $deliveryModel = new DeliveryModel();
        $res = $deliveryModel->where("id", $delivery_id)->update(['name' => $name]);
        if ($res) {
            return ok("修改成功");
        } else {
            return err("修改失败");
        }
    }

    public function disable(Request $request)
    {
        $delivery_id = $request->post("delivery_id");

        $deliveryModel = new DeliveryModel();
        $res = $deliveryModel->where("id", $delivery_id)->update(['status' => 0]);
        if ($res) {
            return ok("禁用成功");
        } else {
            return err("禁用失败");
        }
    }

    public function info(Request $request)
    {
        $delivery_id = $request->post("delivery_id");

        $deliveryModel = new DeliveryModel();
        $delivery = $deliveryModel->where("id", $delivery_id)->find();
        if ($delivery) {
            return ok("获取成功", $delivery);
        } else {
            return err("获取失败");
        }
    }

    public function enable(Request $request)
    {
        $delivery_id = $request->post("delivery_id");

        $deliveryModel = new DeliveryModel();
        $res = $deliveryModel->where("id", $delivery_id)->update(['status' => 1]);
        if ($res) {
            return ok("启用成功");
        } else {
            return err("启用失败");
        }
    }
}
