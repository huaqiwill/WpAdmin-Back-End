<?php

namespace app;


class Response extends \think\Response
{
    public function json(int $code, string $msg, $data = null): \think\Response
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

    public function ok(string $msg, $data = null): \think\Response
    {
        return $this->json(200, $msg, $data);
    }

    public function err(string $msg): \think\Response
    {
        return $this->json(400, $msg);
    }

    public function errScript(string $msg)
    {
        return "<script>alert('$msg');history.go(-1);</script>";
    }
}