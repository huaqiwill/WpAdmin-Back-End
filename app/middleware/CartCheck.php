<?php

namespace app\middleware;

use app\Request;
use think\facade\Middleware;
use app\validate\CartValidator;

class CartCheck extends Middleware
{
  public function handle(Request $request, \Closure $next)
  {
    if ($request->isGet()) {
      return $next($request);
    }
    $validator = new CartValidator();
    if (!$validator->scene($request->action())->check($request->post())) {
      return err($validator->getError());
    }
    return $next($request);
  }
}
