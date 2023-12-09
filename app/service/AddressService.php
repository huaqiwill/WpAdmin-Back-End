<?php

namespace app\service;

use app\model\AddressModel;

class AddressService
{
    private $addressModel;

    public function __construct()
    {
        $this->addressModel = new AddressModel();
    }

    public function delete($address_id)
    {
        return $this->addressModel->where('id', $address_id)->find()->delete();
    }

    public function update($address)
    {
        return $this->addressModel
            ->where('id', $address['address_id'])
            ->update($address);
    }

    //查询地址列表
    public function list($customer_id)
    {
        
    }

    //查询地址信息
    public function info($address_id)
    {
        
    }

    public function add($address)
    {
        return $this->addressModel->save($address);
    }
}
