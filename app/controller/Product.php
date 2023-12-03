<?php

namespace app\controller;

use app\BaseController;
use app\model\ProductModel;
use app\Response;
use app\validate\ProductValidator;
use app\Request;

class ProductController extends BaseController
{
    public function list(Request $request)
    {
        $response = new Response();
        $itemModel = new ProductModel();
        $itemList = $itemModel->select();
        if ($itemList) {
            return $response->ok('获取成功', $itemList);
        } else {
            return $response->err('获取订单列表失败，请重试！');
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
        $itemModel = new ProductModel();
        $item = $itemModel->find($id);
        if (!$item) {
            return $response->err('不存在的订单');
        }
        return $response->ok('查询成功', $item);
    }

    public function create(Request $request)
    {
        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new ProductValidator();
        $response = new Response();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }
        $itemModel = new ProductModel();
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
        $itemModel = new ProductModel();
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

        $itemModel = new ProductModel();
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

}