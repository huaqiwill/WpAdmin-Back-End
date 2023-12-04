<?php

namespace app\model;

use think\facade\Db;
use \think\Model;

class CartModel extends Model
{
    protected $table = 'tb_cart';

    public function tempCartList($user_id)
    {
        $carts = Db::table('tb_cart')
            ->alias('c')
            ->join('tb_product p', 'p.id=c.product_id')
            ->where(['c.user_id' => $user_id, 'order_id' => 0])->select();

        return $carts;
    }

    public function tempCartListSetOrderId($user_id, $order_id)
    {
        $carts = $this->tempCartList($user_id);
        $cart_id_list = [];
        foreach ($carts as $cart) {
            $cart_id_list[] = $cart['cart_id'];
        }
        $res = $this->whereIn('cart_id', $cart_id_list)
            ->update(["order_id" => $order_id]);
        return $res;
    }

    public function cartList($user_id, $order_id)
    {
        $carts = Db::table('tb_cart')
            ->alias('c')
            ->join('tb_product p', 'p.id=c.product_id')
            ->where(['c.user_id' => $user_id, 'order_id' => $order_id])->select();

        return $carts;
    }
}
