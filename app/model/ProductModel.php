<?php

namespace app\model;

use think\Model;

class ProductModel extends Model
{
    protected $table = 'tb_product';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_at';
    protected $updateTime = false;
}