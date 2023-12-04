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
    public function validateToken($token)
    {
        return $this->where('token', $token)->find();
    }

    //获取下一级的用户id列表
    public function subordinateList($user_id)
    {
        return $this->where('owner_id', $user_id)->column("id");
    }

    //获取下级用户信息
    public function subordinate($user_id)
    {
        return $this->where('owner_id', $user_id)->select();
    }

    public function adminList($page, $page_size)
    {
        $orders = $this->limit(($page - 1) * $page_size, $page_size)->select();
        $count = $this->count();
        return [$orders, $count];
    }
}
