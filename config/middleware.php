<?php
// 中间件配置
use app\middleware\TokenVerify;

return [
    // 中间件列表
    'middleware' => [
        // 注册名 => 中间件类名
//        'token_verify' => TokenVerify::class,
    ],
    // 别名或分组
    'alias' => [
//        'token' => TokenVerify::class,
    ],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [],
];
