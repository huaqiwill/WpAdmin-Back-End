<?php

namespace app\model;

use think\Model;

class ProductModel extends Model
{
    protected $table = 'tb_product';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_at';
    protected $updateTime = false;


    public function adminList($page, $page_size)
    {
        $products = $this->limit(($page - 1) * $page_size, $page_size)->select();
        $count = $this->count();
        return [$products, $count];
    }
}
