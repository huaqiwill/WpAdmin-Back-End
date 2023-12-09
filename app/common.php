<?php

use app\Response;

// 应用公共文件
function json(int $code, string $msg, $data = null): \think\Response
{
  $res = new Response();
  $ret = [
    'code' => $code,
    'msg' => $msg
  ];
  if ($data != null) {
    $ret['data'] = $data;
  }
  return $res->create($ret, 'json', 200)->header(['Content-Type' => 'application/json']);
}

function ok(string $msg, $data = null): \think\Response
{
  return json(200, $msg, $data);
}

function err(string $msg): \think\Response
{
  return json(400, $msg);
}

function errScript(string $msg)
{
  return "<script>alert('$msg');history.go(-1);</script>";
}
