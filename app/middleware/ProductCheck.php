<?php

namespace app\middleware;

use app\Request;
use think\facade\Middleware;
use app\validate\ProductValidator;

class ProductCheck extends Middleware
{
  public function handle(Request $request, \Closure $next)
  {
    if ($request->isGet()) {
      return $next($request);
    }
    $validator = new ProductValidator();
    if (!$validator->scene($request->action())->check($request->post())) {
      return err($validator->getError());
    }
    return $next($request);
  }
}
