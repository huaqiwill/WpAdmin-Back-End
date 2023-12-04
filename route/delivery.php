<?php

use think\facade\Route;

//快递操作
Route::group("delivery", function () {
    Route::get('index', 'Delivery/index');

    Route::post('add', 'Delivery/add');
    Route::post('update', 'Delivery/update');
    Route::post('disable', 'Delivery/disable');
    Route::post('enable', 'Delivery/enable');
    Route::post('nameList', 'Delivery/nameList');
    Route::post('list', 'Delivery/list');
    Route::post('info', 'Delivery/info');
});
