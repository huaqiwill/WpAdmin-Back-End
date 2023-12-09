<?php

namespace app\controller;

use app\BaseController;
use app\model\PermissionModel;
use app\model\ProductModel;
use app\Request;

class Product extends BaseController
{
    public function index(Request $request)
    {

        return view('index', [
            'name' =>  $request->middleware("name"),
            'select' => 'product',
            'title' => '商品管理',
            'role_id' =>  $request->middleware("role_id"),
            'menus' => $request->middleware("menus")
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
                'role_id' => $role_id,
                'menus' => $request->middleware("menus")
            ]);
        }

        $product = $request->post();
        $user_id = $request->middleware("user_id");

        $productModel = new ProductModel();
        $product['user_id'] = $user_id;
        $product = $productModel->create($product);
        if ($product) {
            return ok("订单创建成功", $product);
        } else {
            return err("订单创建失败，请重试！");
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            return view('update', [
                'name' => $request->middleware('name'),
                'select' => 'product',
                'role_id' => $request->middleware("role_id"),
                'menus' => $request->middleware("menus")
            ]);
        }

        $id = $request->post('id');
        $postData = $request->post();

        $productModel = new ProductModel();
        $product = $productModel->getById($id);
        if (!$product) {
            return err('不存在的订单');
        }

        $result = $product->save($postData);
        if ($result) {
            return ok("订单修改成功");
        } else {
            return err("订单修改失败，请重试！");
        }
    }

    public function list(Request $request)
    {
        $page = $request->post('page', 1);
        $page_size = $request->post('page_size', 10);

        $productModel = new ProductModel();
        $user_id = $request->middleware('user_id');
        $role_id = $request->middleware('role_id');

        if ($role_id == 1) {
            list($products, $count) = $productModel->adminList($page, $page_size);
        } else {
            list($products, $count) = $productModel->userList($user_id, $page, $page_size);
        }
        $data = [
            'products' => $products,
            'count' => $count
        ];
        return  ok('获取成功', $data);
    }

    public function nameList()
    {
        $model = new ProductModel();
        $names = $model->column("name");
        return ok("获取成功", $names);
    }

    public function info(Request $request)
    {
        $product_id = $request->post('product_id');

        // 查找订单
        $productModel = new ProductModel();
        $product = $productModel->getById($product_id);

        if (!$product) {
            return  err('不存在的订单');
        }
        return  ok('查询成功', $product);
    }

    public function delete(Request $request)
    {
        $product_id = $request->post('product_id');

        $productModel = new ProductModel();
        $product = $productModel->getById($product_id);
        if (!$product) {
            return  err('不存在的订单');
        }

        // 删除订单
        $result = $product->delete();
        if ($result) {
            return  ok('删除成功');
        } else {
            return err('删除失败,请重试!');
        }
    }
}
