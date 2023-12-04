<?php

namespace app\model;

use think\Model;

class OrderModel extends Model
{
    protected $table = 'tb_order';

    public function adminList($page, $page_size, $name, $phone)
    {
        $customerModel = new CustomerModel();
        $customer_cid_list = $customerModel
            ->where('cname', 'like', "%$name")
            ->where('phone', 'like', "%$phone")
            ->column('cid');

        $orders = $this
            ->where('customer_id', 'in', $customer_cid_list)
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('customer_id', 'in', $customer_cid_list)
            ->count();

        $amount = $this
            ->where('customer_id', 'in', $customer_cid_list)
            ->sum("amount");

        return [$orders, $count, $amount];
    }

    public function brandList($user_id, $page, $page_size, $name, $phone)
    {
        //1、查询所有老板id
        $orders = [];
        $count = 0;
        $amount = 0;
        //2、获取订单
        return [$orders, $count, $amount];
    }


    public function bossList($user_id, $page, $page_size, $name, $phone)
    {
        $userModel = new UserModel();
        $user_id_list = $userModel->subordinateList($user_id);

        $orders = $this
            ->where('user_id', 'in', $user_id_list)
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('user_id', 'in', $user_id_list)
            ->count();

        $amount = $this
            ->where('user_id', 'in', $user_id_list)
            ->sum("amount");

        return [$orders, $count, $amount];
    }

    public function salesList($user_id, $page, $page_size, $name, $phone)
    {
        $customerModel = new CustomerModel();
        $customer_cid_list = $customerModel
            ->where('user_id', $user_id)
            ->where('cname', 'like', "%$name")
            ->where('phone', 'like', "%$phone")
            ->column('cid');

        $orders = $this
            ->where('user_id', $user_id)
            ->where('customer_id', 'in', $customer_cid_list)
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('user_id', $user_id)
            ->where('customer_id', 'in', $customer_cid_list)
            ->count();

        $amount = $this
            ->where('user_id', $user_id)
            ->where('customer_id', 'in', $customer_cid_list)
            ->sum('amount');

        return [$orders, $count, $amount];
    }
}
