<?php

use think\facade\Route;

//报表操作
Route::group("report", function () {
    Route::get('index', 'Report/index');
    Route::post('userAchievement', 'Report/userAchievement');
});