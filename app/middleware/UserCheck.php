<?php

namespace app\middleware;

use app\Request;
use app\validate\UserValidator;
use think\facade\Middleware;

class UserCheck extends Middleware
{
  public function handle(Request $request, \Closure $next)
  {
    //如果是get请求，直接放行
    if ($request->isGet()) {
      return $next($request);
    }
    $validator = new UserValidator();
    if (!$validator->scene($request->action())->check($request->post())) {
      return err($validator->getError());
    }
    return $next($request);
  }
}
