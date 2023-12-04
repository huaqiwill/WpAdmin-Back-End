<?php

namespace app\model;

use think\Model;

class CustomerModel extends Model
{
    protected $table = 'tb_customer';

    //根据用户id获取顾客列表id
    public function customerIdList($user_id)
    {
        return $this->where('user_id', $user_id)->column('id');
    }

    public function customerList($user_id)
    {
        return $this->where('user_id', $user_id)->select();
    }

    public function adminList($page, $page_size, $wechat, $phone)
    {
        $customers = $this
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->count();

        return [$customers, $count];
    }

    public function bossList($user_id, $page, $page_size, $wechat, $phone)
    {
        $customers = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->count();

        return [$customers, $count];
    }

    public function brandList($user_id, $page, $page_size, $wechat, $phone)
    {
        $customers = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->count();

        return [$customers, $count];
    }

    public function salesList($user_id, $page, $page_size, $wechat, $phone)
    {
        $customers = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this
            ->where('user_id', $user_id)
            ->where('wechat', 'like', "%$wechat")
            ->where('phone', 'like', "%$phone")
            ->count();

        return [$customers, $count];
    }
}
