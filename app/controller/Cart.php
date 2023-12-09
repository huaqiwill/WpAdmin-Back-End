<?php

namespace app\controller;

use app\BaseController;
use app\model\CartModel;
use app\model\ProductModel;
use app\Response;
use \think\facade\Request;

use app\service\CartService;
use app\validate\ProductValidator;

class Cart extends BaseController
{
    public function add()
    {
        $postData = Request::post();
  
        $product_name = Request::post("product_name");
        $user_id = Request::middleware("user_id");

        $productModel = new ProductModel();
        $product = $productModel->where('name', $product_name)->find();
        if (!$product) {
            return err("不存在的商品");
        }

        $product_id = $product['id'];

        $cartModel = new CartModel();

        $cartService = new CartService();
        $exists = $cartService->exists($user_id, $product_id);
        if ($exists) {
            return err('商品已存在购物车中');
        }

        $res = $cartService->add(Request::post(), $user_id);
        if ($res) {
            return ok("添加成功");
        } else {
            return err("添加失败，请重试");
        }
    }

    public function list()
    {
        //参数
        $user_id = Request::middleware("user_id"); //注意这里不是来自middleware
        $order_id = Request::post("order_id");
        //模型
        $cartModel = new CartModel();

        //是否包含$order_id，包含则更新，不包含则新增
        if (empty($order_id)) {
            $carts = $cartModel->tempCartList($user_id);
        } else {
            $carts = $cartModel->cartList($user_id, $order_id);
        }
        return ok("获取成功", $carts);
    }

    public function delete()
    {
        $response = new Response();

        $cart_id = Request::post('cart_id');
        $cartModel = new CartModel();
        $res = $cartModel->where('cart_id', $cart_id)->delete();
        if ($res) {
            return $response->ok("删除成功");
        } else {
            return $response->err("删除失败");
        }
    }

    public function update()
    {
        $cart_id = Request::post('cart_id');
        $product_num = Request::post('product_num');

        if ((int)$product_num <= 0) {
            return err("数量不能小于等于0");
        }

        $cartModel = new CartModel();
        $cart = $cartModel->where('cart_id', $cart_id)->find();
        if (!$cart) {
            return err("错误，购物车不存在");
        }
        if (!$cart->save(['product_num' => (int)$product_num])) {
            return err("修改失败，请重试");
        }
        return ok("修改成功");
    }
}
