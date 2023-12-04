<?php

use think\facade\Route;

//地址操作
Route::group("address", function () {
    Route::get('index', 'Address/index');
    Route::get('add', 'Address/add');
    Route::get('update', 'Address/update');

    Route::post('add', 'Address/add');
    Route::post('list', 'Address/list');
    Route::post('update', 'Address/update');
    Route::post('delete', 'Address/delete');
    Route::post('info', 'Address/info');
});

