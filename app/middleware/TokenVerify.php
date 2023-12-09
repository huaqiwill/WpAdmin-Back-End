<?php

namespace app\middleware;

use app\model\PermissionModel;
use app\model\UserModel;
use app\Request;
use think\facade\Middleware;

class TokenVerify extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        $route = $request->pathinfo();

        if ($route == 'user/login' || preg_match("/^static/", $route)) {
            return $next($request);
        }

        $token = $request->cookie("token");
        if (empty($token)) {
            return redirect('/index.php/user/login');
        }

        $userModel = new UserModel();
        $user = $userModel->validateToken($token);
        if (!$user) {
            //无效的token
            return redirect('/index.php/user/login');
        }

        $permissionModel = new PermissionModel();
        $menus = $permissionModel->menus();

        $user_id = $user['id'];
        $user_name = $user['username'];
        $role_id = $user['role_id'];

        $request->withMiddleware([
            "role_id" => $role_id,
            "name" => $user_name,
            "user_id" => $user_id,
            'menus' => $menus
        ]);
        return $next($request);
    }
}
