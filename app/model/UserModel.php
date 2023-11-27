<?php

namespace app\model;


use think\Exception;
use think\Model;

class UserModel extends Model
{
    protected $table = 'tb_user';

    // 用户注册
    public function register($username, $password, $phone, $email): bool
    {
        return $this->save([
            'username' => $username,
            'password' => $password,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    // 用户登录验证
    public function login($username, $password)
    {
        try {
            $res = $this->where('username', $username)->where('password', $password)->find();
            if ($res) {
                return $res;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    // 生成授权码
    public function generateToken($userId): string
    {
        $token = md5(uniqid(mt_rand(), true));
        $this->where('id', $userId)->update(['token' => $token]);
        return $token;
    }

    // 验证授权码
    public function validateToken($userId, $token)
    {
        return $this->where('id', $userId)
            ->where('token', $token)
            ->find();
    }
}