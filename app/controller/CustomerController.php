<?php

namespace app\controller;

use app\BaseController;
use app\model\CustomerModel;
use app\Request;
use app\Response;
use app\validate\CustomerValidator;
use app\validate\ProductValidator;
use app\model\OrderModel;

class CustomerController extends BaseController
{
    public function list(\app\Request $request)
    {
        $response = new Response();
        $model = new CustomerModel();
        $itemList = $model->select();
        if ($itemList) {
            return $response->ok('获取成功', $itemList);
        } else {
            return $response->err('获取顾客列表失败，请重试！');
        }
    }

    public function detail(Request $request)
    {
        $response = new Response();
        $id = $request->get('id');
        if (empty($id)) {
            return $response->err("缺少id");
        }

        // 查找订单
        $itemModel = new CustomerModel();
        $item = $itemModel->find($id);
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

    public function create(Request $request)
    {
        $response = new Response();
        $postData = $request->post();
        $validator = new CustomerValidator();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }
        $itemModel = new CustomerModel();
        $item = $itemModel->create($postData);
        if ($item) {
            return $response->ok("订单创建成功", $item);
        } else {
            return $response->err("订单创建失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        $response = new Response();
        $id = $request->get('id');
        if (empty($id)) {
            return $response->err("请输入用户id");
        }
        $postData = $request->post();
        // 更新订单信息
        $itemModel = new CustomerModel();
        // 查找订单
        $item = $itemModel->find($id);

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

    public function delete(Request $request)
    {
        $response = new Response();
        $id = $request->get('id');
        if (empty($id)) {
            return $response->err("缺少id");
        }

        $itemModel = new CustomerModel();
        // 查找订单
        $item = $itemModel->find($id);
        $response = new Response();
        if (!$item) {
            return $response->err('不存在的订单');
        }

        // 删除订单
        $result = $item->delete();
        if ($result) {
            return $response->ok('删除成功');
        } else {
            return $response->err('删除失败,请重试!');
        }
    }


    //录入订单
    public function orderEntry()
    {

    }

    //顾客的订单列表
    public function orderList()
    {
        $response = new Response();
        if (input('?get.cid')) {
            return $response->err('请输入顾客id');
        }
        $cid = input('get.cid');
        $orderModel = new OrderModel();
        $orderList = $orderModel->where('cid', $cid)->select();
        if ($orderList) {

        } else {

        }
    }
}