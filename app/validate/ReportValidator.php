<?php

namespace app\validate;

use think\Validate;

class ReportValidator extends Validate
{
    protected $rule = [
        'address_id' => 'require',
    ];

    protected $message = [
        'address_id.require' => '地址ID不能为空',
    ];

    protected function sceneUserAchievement()
    {
        $this->rule = [];
    }
}
