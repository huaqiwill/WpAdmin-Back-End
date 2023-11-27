<?php

namespace app\controller;

use app\BaseController;
use app\model\UserModel;
use app\Response;
use app\validate\LoginValidator;
use app\Request;


class UserController extends BaseController
{
    public function login(Request $request)
    {
        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new LoginValidator();
        $response = new Response();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }
        $userModel = new UserModel();
        $username = $request->post('username');
        $password = $request->post('password');
        if ($user = $userModel->login($username, $password)) {
            //构造token
            $token = $userModel->generateToken($user['id']);
            $user['token'] = $token;
            return $response->ok('登录成功', $user);
        } else {
            return $response->err('用户名或密码错误');
        }
    }

    public function register(Request $request)
    {
        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new LoginValidator();
        $response = new Response();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }
        $userModel = new UserModel();
        $username = $request->post('username');
        $password = $request->post('password');
        //判断用户是否存在
        $exists = $userModel->where('username', $username)->find();
        if ($exists) {
            return $response->err('该用户名已存在');
        }

        $result = $userModel->save([
            'username' => $username,
            'password' => $password
        ]);
        if ($result) {
            return $response->ok('注册成功');
        } else {
            return $response->err('注册失败，请重试！');
        }
    }

    //添加业务员
    public function add()
    {

    }

    //修改业务员
    public function update()
    {

    }

    //冻结和解冻业务员
    public function freeze(Request $request)
    {

    }
}