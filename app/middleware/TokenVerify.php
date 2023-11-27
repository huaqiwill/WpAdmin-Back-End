<?php

namespace app\middleware;

use app\model\UserModel;
use think\facade\Request;
use think\facade\Middleware;
use think\Response;

class TokenVerify extends Middleware
{
    public function handle($request, \Closure $next)
    {
        // 从请求头获取 Token
        $token = Request::header('Authorization');

        // 假设从请求中获取用户 ID
        $userId = $request->param('user_id');

        // 在数据库中验证 Token
        $userModel = new UserModel();
        $user = $userModel->validateToken($userId, $token);

        if ($user) {
            // Token 验证成功，允许进入控制器方法
            return $next($request);
        } else {
            // Token 验证失败，返回未授权的响应
            return Response::create(['message' => 'Unauthorized'], 'json', 401);
        }
    }
}
