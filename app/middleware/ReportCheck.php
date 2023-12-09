<?php

namespace app\middleware;

use app\Request;
use app\validate\ReportValidator;
use think\facade\Middleware;

class ReportCheck extends Middleware
{
  public function handle(Request $request, \Closure $next)
  {
    if ($request->isGet()) {
      return $next($request);
    }
    $validator = new ReportValidator();
    if (!$validator->scene($request->action())->check($request->post())) {
      return err($validator->getError());
    }
    return $next($request);
  }
}
