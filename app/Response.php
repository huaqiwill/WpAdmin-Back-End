<?php

namespace app;


class Response extends \think\Response
{
    public function json(int $code, string $msg, object $data = null): \think\Response
    {
        $ret = [
            'code' => $code,
            'msg' => $msg
        ];
        if ($data != null) {
            $ret['data'] = $data;
        }
        return $this->create($ret, 'json', 200)->header(['Content-Type' => 'application/json']);
    }

    public function ok(string $msg, object $data = null): \think\Response
    {
        return $this->json(200, $msg, $data);
    }

    public function err(string $msg): \think\Response
    {
        return $this->json(400, $msg);
    }
}