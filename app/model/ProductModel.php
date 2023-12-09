<?php

namespace app\model;

use think\Model;

class ProductModel extends Model
{
    protected $table = 'tb_product';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_at';
    protected $updateTime = false;

    public function getById($id)
    {
        return $this->where('id', $id)->find();
    }

    public function adminList($page, $page_size)
    {
        $products = $this->limit(($page - 1) * $page_size, $page_size)->select();
        $count = $this->count();
        return [$products, $count];
    }

    public function userList($user_id, $page, $page_size)
    {
        $products = $this->where('user_id', $user_id)
            ->limit(($page - 1) * $page_size, $page_size)
            ->select();

        $count = $this->where('user_id', $user_id)->count();

        return [$products, $count];
    }

    public function getByName($name)
    {
    }
}
