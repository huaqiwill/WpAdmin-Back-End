<?php

namespace app\controller;

use app\BaseController;
use app\middleware\TokenVerify;
use app\model\PermissionModel;
use app\model\RoleModel;
use app\model\UserModel;
use app\Response;
use app\utils\TokenCheck;
use app\validate\LoginValidator;
use app\Request;
use app\validate\UserAddValidator;
use app\validate\UserValidator;
use think\response\View;


class User extends BaseController
{
    public function list(Request $request)
    {
        $res = new Response();
        $page = $request->post('page', 1);
        $page_size = $request->post('page_size', 10);

        $userModel = new UserModel();
        $user_id = $request->middleware("user_id");
        $role_id = $request->middleware("role_id");
        if ($role_id == 1) {
            //1、管理员：获取全部用户
            list($users, $count) = $userModel->adminList($page, $page_size);
        } else {
            $users = $userModel
                ->where("owner_id", $user_id)
                ->limit(($page - 1) * $page_size, $page_size)
                ->select();

            $count = $userModel
                ->where("owner_id", $user_id)
                ->count();
        }
        $data = [
            "users" => $users,
            'count' => $count
        ];
        return $res->ok("获取成功", $data);
    }

    public function index(Request $request): View
    {
        $page = input('get.page', 1);
        $page_size = input('get.page_size', 10);

        $menus = $request->middleware("menus");

        $userModel = new UserModel();
        $user_id = $request->middleware("user_id");
        $users = $userModel->where("owner_id", $user_id)->limit(($page - 1) * $page_size, $page_size)->select();
        $amount = $userModel->where("owner_id", $user_id)->count();

        $role_id = $request->middleware("role_id");
        $name = $request->middleware("name");
        return view('index', [
            'name' => $name,
            'select' => 'user',
            'users' => $users,
            'amount' => $amount,
            'page_size' => $page_size,
            'role_id' => $role_id,
            'menus' => $menus
        ]);
    }

    public function login(Request $request)
    {
        if ($request->isGet()) {
            $token = $request->cookie("token");
            if (!empty($token)) {
                $userModel = new UserModel();
                $user = $userModel->validateToken($token);
                if ($user) {
                    return redirect("/index.php");
                }
            }
            return view('login');
        }

        $username = $request->post('username');
        $password = $request->post('password');

        $userModel = new UserModel();
        if ($user = $userModel->login($username, $password)) {
            if ($user['status'] == 0) {
                return err('您的账户已冻结,无法登录');
            }
            if ($user['role_id'] == "") {
                return err('您的账户无登录权限,请联系管理员');
            }
            $user['token'] = $userModel->generateToken($user['id']);
            return ok('登录成功', $user);
        } else {
            return err('用户名或密码错误');
        }
    }

    public function add(Request $request)
    {
        if ($request->isGet()) {
            $menus = $request->middleware("menus");
            $role_id = $request->middleware("role_id");

            $roleModel = new RoleModel();
            $roles = $roleModel->select();
            $name = $request->middleware("name");

            return view('add', [
                'name' => $name,
                'select' => 'user',
                'role_id' => $role_id,
                'roles' => $roles,
                'menus' => $menus
            ]);
        }

        $res = new Response();

        $postData = $request->post();
        $validator = new UserValidator();
        if (!$validator->check($postData)) {
            return $res->err($validator->getError());
        }

        $user_id = $request->middleware("user_id");
        $role_id = $request->middleware("role_id");
        $role_id_ = $postData['role_id'];
        if ($role_id == 1) { //管理员

        } else if ($role_id == 2) { //品牌方
            if ($role_id_ != 3) {
                return $res->err("品牌方只能创建老板账号");
            }
        } else if ($role_id == 3) { //老板
            if ($role_id_ != 4) {
                return $res->err("老板只能创建业务员");
            }
        } else { //业务员
            return $res->err("业务员不能创建角色");
        }
        $postData['owner_id'] = $user_id;
        $postData['status'] = '0';  //默认禁用
        $userModel = new UserModel();
        $result = $userModel->save($postData);
        if ($result) {
            return $res->ok('新增成用户功');
        } else {
            return $res->err('新增用户失败，请重试！');
        }
    }

    public function update(Request $request)
    {
        if ($request->isGet()) {
            $menus = $request->middleware("menus");
            return view('update', [
                'name' => $request->middleware("name"),
                'select' => 'user',
                'role_id' => $request->middleware("role_id"),
                'menus' => $menus
            ]);
        }
        $res = new Response();
        $user_id = $request->post("id");
        if (empty($user_id)) {
            return $res->err("无效的id");
        }

        $postData = $request->post();
        $validator = new UserValidator();
        $response = new Response();
        if (!$validator->check($postData)) {
            return $response->err($validator->getError());
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('id', $user_id)
            ->find();

        if (!$user) {
            return $res->err("修改失败，用户不存在");
        }

        if ($user->save($postData)) {
            return $res->ok("修改成功");
        } else {
            return $res->err("修改失败，请重试");
        }
    }


    public function info(Request $request)
    {
        $res = new Response();
        $user_id = $request->post('user_id');
        if (empty($user_id)) {
            return $res->err('错误的用户id');
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('id', $user_id)
            ->find();

        if ($user) {
            return $res->ok('获取成功', $user);
        } else {
            return $res->err('获取失败');
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


    //冻结和解冻业务员
    public function freeze(Request $request): \think\Response
    {
        $response = new Response();
        $user_id = $request->post("user_id");

        if (empty($user_id)) {
            return $response->err("用户ID不能为空");
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('id', $user_id)
            ->find();

        if (!$user) {
            return $response->err("不存在的用户");
        }

        $result = $user->save([
            'status' => '0'
        ]);

        if ($result) {
            return $response->ok("冻结成功");
        } else {
            return $response->err("冻结失败");
        }
    }

    public function unfreeze(Request $request): \think\Response
    {
        $response = new Response();
        $user_id = $request->post("user_id");

        if (empty($user_id)) {
            return $response->err("用户ID不能为空");
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('id', $user_id)
            ->find();

        if (!$user) {
            return $response->err("不存在的用户");
        }

        $result = $user->save([
            'status' => '1'
        ]);

        if ($result) {
            return $response->ok("解结成功");
        } else {
            return $response->err("解冻失败");
        }
    }

    //用户登出
    public function logout(Request $request): \think\Response
    {
        $user_id = $request->middleware("user_id");
        $userModel = new UserModel();
        $user = $userModel->where('id', $user_id)->find();
        $user->save(['token' => '']);
        return redirect("/index.php/user/login");
    }
}
