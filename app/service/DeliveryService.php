<?php

namespace app\service;

use app\model\DeliveryModel;

class DeliveryService
{
    public $deliveryModel;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
    }

}