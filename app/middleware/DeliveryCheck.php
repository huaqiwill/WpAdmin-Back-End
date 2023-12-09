<?php

namespace app\middleware;

use app\Request;
use app\validate\DeliveryValidator;
use think\facade\Middleware;

class DeliveryCheck extends Middleware
{
  public function handle(Request $request, \Closure $next)
  {
    if ($request->isGet()) {
      return $next($request);
    }
    $validator = new DeliveryValidator();
    if (!$validator->scene($request->action())->check($request->post())) {
      return err($validator->getError());
    }
    return $next($request);
  }
}
