<?php

use think\facade\Route;

Route::get('/', 'Index/index');

//主页
Route::group("index", function () {
    Route::get('test', 'Index/test');
    Route::post('statisticList', 'Index/statisticList');
});

