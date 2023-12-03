<?php

namespace app\controller;

use app\BaseController;
use app\model\UserModel;
use app\Response;
use app\validate\LoginValidator;
use app\Request;

use think\facade\View;

class UserController extends BaseController
{
    public function index()
    {
        // 模板变量赋值
//        View::assign('name','ThinkPHP');
//        View::assign('email','thinkphp@qq.com');
//        // 或者批量赋值
//        View::assign([
//            'name'  => 'ThinkPHP',
//            'email' => 'thinkphp@qq.com'
//        ]);
        // 模板输出
        return View::fetch('login');
    }

    public function login(Request $request)
    {
        $response = new Response();

        $postData = $request->post();
        // 使用验证器对POST参数进行校验
        $validator = new LoginValidator();

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

    public function info(Request $request)
    {
        $response = new Response();
        $token = input('get.token');
        if (empty($token)) {
            return $response->err('用户token不存在');
        }

        $userModel = new UserModel();
        $res = $userModel->where('token', $token)->select();
        if ($res) {
            return $response->ok('获取成功', $res);
        } else {
            return $response->err('获取失败');
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

    //用户登出
    public function logout()
    {

    }
}