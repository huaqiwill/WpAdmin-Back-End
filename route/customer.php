<?php

use think\facade\Route;

//顾客操作
Route::group("customer", function () {
    Route::get('index', 'Customer/index');
    Route::get('add', 'Customer/add');
    Route::get('update', 'Customer/update');

    Route::post('add', 'Customer/add');
    Route::post('update', 'Customer/update');
    Route::post('delete', 'Customer/delete');
    Route::post('info', 'Customer/info');
    Route::post('orderList', 'Customer/orderList');
    Route::post('list', 'Customer/list');
    Route::post('nameList', 'Customer/nameList');
    Route::post('count', 'Customer/count');
    Route::post('getByPhone', 'Customer/getByPhone');
    Route::post('phoneList', 'Customer/phoneList');
});
