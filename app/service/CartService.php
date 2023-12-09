<?php

namespace app\service;

use app\model\CartModel;

class CartService
{
    public $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    public function add($cart, $user_id)
    {
        $product_id = 0;
        $user_id = 0;

        $res = $this->cartModel->create([
            "product_id" => $product_id,
            "product_num" => 1,
            "user_id" => $user_id,
            "order_id" => "0"
        ]);
    }

    public function exists($user_id, $product_id)
    {
        return $this->cartModel
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->where('order_id', "0")
            ->find();
    }
}
