<?php

use think\facade\Route;

//角色操作
Route::group("role", function () {
    Route::get('index', 'Role/index');

    // Route::post('role/list', 'Role/list');
    Route::post('role/add', 'Role/add');
    Route::post('role/update', 'Role/update');
    Route::post('role/delete', 'Role/delete');
    Route::post('role/info', 'Role/info');
});
