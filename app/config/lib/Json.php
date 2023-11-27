<?php

namespace app\config\lib;

use think\Exception;
use think\facade\Config;

class Json
{
    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        try {
            if (Config::get('api_return_standard')) {
                $data = $this->standard($data);
                if (!$data) {
                    return;
                }
            }
        } catch (Exception $ex) {

        }
    }

    private function standard($data)
    {
        if (!isset($data[0]) || !is_int($data[0]) || !isset($data[1])) {
            echo "数据格式错误<pre>";
            print_r($data);
            echo "</pre>";
            return false;
        }

        if (200 === $data[0]) {
            return ['ret' => 200, 'data' => $data[1], 'msg' => ''];
        }

        return ['ret' => $data[0], 'data' => [], 'msg' => $data[1]];
    }
}