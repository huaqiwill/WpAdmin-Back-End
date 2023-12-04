<?php

namespace app\controller;

use app\BaseController;
use app\model\DeliveryModel;
use app\Request;
use app\Response;
use app\validate\DeliveryValidator;

class Delivery extends BaseController
{
    public function index(Request $request)
    {
        $name = $request->middleware("name");
        $role_id = $request->middleware("role_id");
        $deliveryModel = new DeliveryModel();
        $deliveryList = $deliveryModel->select();

        return view('index', [
            'name' => $name,
            'select' => 'delivery',
            'role_id' => $role_id,
            'deliveryList' => $deliveryList,
        ]);
    }

    public function list(Request $request)
    {
        $res = new Response();
        $deliveryModel = new DeliveryModel();
        $delivery_list = $deliveryModel->select();
        return $res->ok("获取成功", $delivery_list);
    }

    public function add(Request $request)
    {
        $res = new Response();
        $postData = $request->post();
        $validator = new DeliveryValidator();
        if (!$validator->check($postData)) {
            return $res->err($validator->getError());
        }

        $deliveryModel = new DeliveryModel();
        $postData['status'] = 1;
        $result = $deliveryModel->save($postData);
        if ($result) {
            return $res->ok("添加成功");
        } else {
            return $res->err("添加失败");
        }
    }

    public function nameList(Request $request)
    {
        $res = new Response();
        $model = new DeliveryModel();
        $names = $model->where('status', 1)->column("name");
        return $res->ok("获取成功", $names);
    }

    public function update(Request $request)
    {
        $response = new Response();
        $name = $request->post("name");
        if (empty($name)) {
            return $response->err("快递名称不能为空");
        }
        $delivery_id = $request->post("delivery_id");
        $deliveryModel = new DeliveryModel();

        $delivery = $deliveryModel->where("id", $delivery_id)->find();
        if (!$delivery) {
            return $response->err("快递不存在");
        }

        $res = $delivery->save(['name' => $name]);
        if ($res) {
            return $response->ok("修改成功");
        } else {
            return $response->err("修改失败");
        }
    }

    public function disable(Request $request)
    {
        $response = new Response();
        $delivery_id = $request->post("delivery_id");
        $deliveryModel = new DeliveryModel();

        $delivery = $deliveryModel->where("id", $delivery_id)->find();
        if (!$delivery) {
            return $response->err("快递不存在");
        }

        $res = $delivery->save(['status' => 0]);
        if ($res) {
            return $response->ok("禁用成功");
        } else {
            return $response->err("禁用失败");
        }
    }

    public function info(Request $request)
    {
        $res = new Response();
        $delivery_id = $request->post("delivery_id");
        if (empty($delivery_id)) {
            return $res->err("快递id为空");
        }
        $deliveryModel = new DeliveryModel();
        $delivery = $deliveryModel->where("id", $delivery_id)->find();
        if (!$delivery) {
            return $res->err("获取失败");
        }
        return $res->ok("获取成功", $delivery);
    }

    public function enable(Request $request)
    {
        $response = new Response();
        $delivery_id = $request->post("delivery_id");
        $deliveryModel = new DeliveryModel();

        $delivery = $deliveryModel->where("id", $delivery_id)->find();
        if (!$delivery) {
            return $response->err("快递不存在");
        }

        $res = $delivery->save(['status' => 1]);
        if ($res) {
            return $response->ok("禁用成功");
        } else {
            return $response->err("禁用失败");
        }
    }
}
