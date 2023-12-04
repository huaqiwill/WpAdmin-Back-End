<?php

namespace app\middleware;

use app\model\UserModel;
use app\Request;
use Closure;
use think\facade\Middleware;

class TokenVerify extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $currentRoute = $request->pathinfo();
        // 如果当前路由需要排除，可以在这里添加逻辑
        if ($currentRoute == 'user/login') {
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

        $userId = $user['id'];
        $roleId = $user['role_id'];
        $name = $user['username'];

        $request->withMiddleware([
            "role_id" => $roleId,
            "name" => $name,
            "user_id" => $userId
        ]);
        return $next($request);
    }
}
