<?php

use think\facade\Route;

//用户操作
Route::group("user", function () {
    Route::get('index', 'User/index');
    Route::get('login', 'User/login');
    Route::get('add', 'User/add');
    Route::get('update', 'User/update');

    Route::post('update', 'User/update');
    Route::post('add', 'User/add');
    Route::post('delete', 'User/delete');
    Route::post('info', 'User/info');
    Route::post('login', 'User/login');
    Route::post('register', 'User/register');
    Route::post('freeze', 'User/freeze');
    Route::post('unfreeze', 'User/unfreeze');
    Route::post('list', 'User/list');
});

