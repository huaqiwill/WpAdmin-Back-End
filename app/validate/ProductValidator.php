<?php

namespace app\validate;

use think\Validate;
use app\model\ProductModel;

class ProductValidator extends Validate
{
    protected $rule = [
        'id' => 'require|checkExist',
        'name' => 'require|unique:tb_product',
        'price' => 'require',
        'number' => 'require',
        'product_name' => 'require',
        'product_id' => 'require'
    ];

    protected $message = [
        'id.require' => '商品ID不能为空',
        'id.checkExist' => '不存在的订单',
        'name.require' => '商品名称不能为空',
        'name.unique' => '商品名称已存在',
        'price.require' => '商品价格不能为空',
        'number.require' => '商品数量不能为空',
        'product_name.require' => '商品名称不能为空',
    ];

    protected $scene = [
        'add' => ['product_name'],
        'update' => ['id'],
        'delete' => ['id'],
        'info' => ['product_id']
    ];

    public function checkExist($value)
    {
        $id = $value['id'];
        $productModel = new ProductModel();
        $product = $productModel->where('id', $id)->find();
        return !empty($product);
    }

    protected function sceneList()
    {
        $this->rule = [];
    }
}
