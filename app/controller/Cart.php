<?php

namespace app\controller;

use app\BaseController;
use app\model\CartModel;
use app\model\ProductModel;
use app\Request;
use app\Response;
use app\validate\ProductAddValidator;

class Cart extends BaseController
{
    public function add(Request $request)
    {
        $response = new Response();
        $postData = $request->post();
        $validator = new ProductAddValidator();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }

        $product_name = $request->post("product_name");
        $user_id = $request->middleware("user_id");

        $productModel = new ProductModel();
        $product = $productModel->where('name', $product_name)->find();
        if (!$product) {
            return $response->err("不存在的商品");
        }

        $product_id = $product['id'];

        $cartModel = new CartModel();
        $cart = $cartModel
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->where('order_id', "0")
            ->find();
        
        if ($cart) {
            return $response->err('商品已存在购物车中');
        }

        $res = $cartModel->create([
            "product_id" => $product_id,
            "product_num" => 1,
            "user_id" => $user_id,
            "order_id" => "0"
        ]);
        if ($res) {
            return $response->ok("添加成功");
        } else {
            return $response->err("添加失败，请重试");
        }
    }

    public function list(Request $request)
    {
        $res = new Response();
        //参数
        $user_id = $request->middleware("user_id");//注意这里不是来自middleware
        $order_id = $request->post("order_id");
        //模型
        $cartModel = new CartModel();

        //是否包含$order_id，包含则更新，不包含则新增
        if (empty($order_id)) {
            $carts = $cartModel->tempCartList($user_id);
        } else {
            $carts = $cartModel->cartList($user_id, $order_id);
        }
        return $res->ok("获取成功", $carts);
    }

    public function delete(Request $request)
    {
        $response = new Response();

        $cart_id = $request->post('cart_id');
        $cartModel = new CartModel();
        $res = $cartModel->where('cart_id', $cart_id)->delete();
        if ($res) {
            return $response->ok("删除成功");
        } else {
            return $response->err("删除失败");
        }
    }

    public function update(Request $request)
    {
        $response = new Response();

        $cart_id = $request->post('cart_id');
        $product_num = $request->post('product_num');

        if ((int)$product_num <= 0) {
            return $response->err("数量不能小于等于0");
        }

        $cartModel = new CartModel();
        $cart = $cartModel->where('cart_id', $cart_id)->find();
        if (!$cart) {
            return $response->err("错误，购物车不存在");
        }
        if (!$cart->save(['product_num' => (int)$product_num])) {
            return $response->err("修改失败，请重试");
        }
        return $response->ok("修改成功");
    }
}
