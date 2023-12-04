<?php

namespace app\utils;

use Exception;
use Firebase\JWT\JWT;

class TokenCheck
{
    //这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐  salt
    private $key = "!@#$%*&";

    //生成验签
    public function signToken($uid): string
    {
        $token = array(
            "iss" => $this->key,        //签发者 可以为空
            "aud" => '',          //面象的用户，可以为空
            "iat" => time(),      //签发时间
            "nbf" => time() + 3,    //在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time() + 200, //token 过期时间
            "data" => [           //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
                'user_id' => $uid,
            ]
        );
        //根据参数生成了 token
        return JWT::encode($token, $this->key, "HS256");
    }


    //验证token
    function checkToken($token): array
    {
        $status = array("code" => 2);
        try {
            JWT::$leeway = 60; //当前时间减去60，把时间留点余地
            $decoded = JWT::decode($token, $this->key); //HS256方式，这里要和签发的时候对应
            $arr = (array) $decoded;
            $res['code'] = 1;
            $res['data'] = $arr['data'];
            return $res;
        } catch (\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            $status['msg'] = "签名不正确";
        } catch (\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            $status['msg'] = "token失效";
        } catch (\Firebase\JWT\ExpiredException $e) { // token过期
            $status['msg'] = "token失效";
        } catch (Exception $e) { //其他错误
            $status['msg'] = "未知错误";
        }
        return $status;
    }
}