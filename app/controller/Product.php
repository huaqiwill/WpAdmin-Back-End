<?php

namespace app\controller;

use app\BaseController;
use app\model\ProductModel;
use app\Request;
use app\Response;
use app\validate\ProductValidator;

class Product extends BaseController
{
    public function index(Request $request)
    {
        $page = input('get.page');
        $page_size = input('get.page_size');
        if (empty($page)) {
            $page = 1;
        }
        if (empty($page_size)) {
            $page_size = 10;
        }


        $user_id = $request->middleware("user_id");

        $itemModel = new ProductModel();
        $products = $itemModel->where("user_id", $user_id)->limit(($page - 1) * $page_size, $page_size)->select();
        $amount = $itemModel->where("user_id", $user_id)->count();

        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");
        return view('index', [
            'name' => $name,
            'select' => 'product',
            'products' => $products,
            'title' => '商品管理',
            'amount' => $amount,
            'page_size' => $page_size,
            'role_id' => $role_id
        ]);
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            $role_id = $request->middleware("role_id");
            $name = $request->middleware("name");
            return view('add', [
                'name' => $name,
                'select' => 'product',
                'title' => '商品添加',
                'role_id' => $role_id
            ]);
        }
        $res = new Response();
        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new ProductValidator();
        if (!$validator->check($postData)) {
            return $res->err($validator->getError());
        }
        $user_id = $request->middleware("user_id");

        $productModel = new ProductModel();
        $postData['user_id'] = $user_id;
        $product = $productModel->create($postData);
        if ($product) {
            return $res->ok("订单创建成功", $product);
        } else {
            return $res->err("订单创建失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            return view('update', [
                'name' => $request->middleware('name'),
                'select' => 'product',
                'role_id' => $request->middleware("role_id")
            ]);
        }
        $response = new Response();
        $id = $request->post('id');
        if (empty($id)) {
            return $response->err("请输入产品id");
        }

        $postData = $request->post();
        $productModel = new ProductModel();
        $product = $productModel->where('id', $id)->find();

        if (!$product) {
            return $response->err('不存在的订单');
        }

        $result = $product->save($postData);
        if ($result) {
            return $response->ok("订单修改成功");
        } else {
            return $response->err("订单修改失败，请重试！");
        }
    }

    public function list(Request $request)
    {
        $response = new Response();

        $page = $request->post('page', 1);
        $page_size = $request->post('page_size', 10);

        $productModel = new ProductModel();
        $user_id = $request->middleware('user_id');
        $role_id = $request->middleware('role_id');

        if ($role_id == 1) {
            list($products, $count) = $productModel->adminList($page, $page_size);
        } else {
            $products = $productModel
                ->where('user_id', $user_id)
                ->limit(($page - 1) * $page_size, $page_size)
                ->select();

            $count = $productModel->where('user_id', $user_id)->count();
        }
        $data = [
            'products' => $products,
            'count' => $count
        ];
        return $response->ok('获取成功', $data);
    }

    public function nameList(Request $request)
    {
        $res = new Response();
        $model = new ProductModel();
        $names = $model->column("name");
        return $res->ok("获取成功", $names);
    }

    public function info(Request $request)
    {
        $response = new Response();
        $product_id = $request->post('product_id');
        if (empty($product_id)) {
            return $response->err("缺少产品id");
        }

        // 查找订单
        $productModel = new ProductModel();
        $product = $productModel
            ->where('id', $product_id)
            ->find();

        if (!$product) {
            return $response->err('不存在的订单');
        }
        return $response->ok('查询成功', $product);
    }





    public function delete(Request $request)
    {
        $res = new Response();

        $product_id = $request->post('product_id');
        if (empty($product_id)) {
            return $res->err("缺少产品id");
        }

        $productModel = new ProductModel();
        $product = $productModel->where('id', $product_id)->find();
        if (!$product) {
            return $res->err('不存在的订单');
        }

        // 删除订单
        $result = $product->delete();
        if ($result) {
            return $res->ok('删除成功');
        } else {
            return $res->err('删除失败,请重试!');
        }
    }
}
