<?php

use think\facade\Route;

//订单操作
Route::group("order", function () {
    Route::get('index', 'Order/index');
    Route::get('add', 'Order/add');
    Route::get('update', 'Order/update');
    Route::get('detail', 'Order/detail');
    Route::get('print', 'Order/print');

    Route::post('add', 'Order/add');
    Route::post('update', 'Order/update');
    Route::post('list', 'Order/list');
    Route::post('delete', 'Order/delete');
    Route::post('submit', 'Order/submit');
    Route::post('confirm', 'Order/confirm');
    Route::post('productList', 'Order/productList');
    Route::post('productAdd', 'Order/productAdd');
    Route::post('productUpdate', 'Order/productUpdate');
    Route::post('productDelete', 'Order/productDelete');
    Route::post('phoneQuery', 'Order/phoneQuery');
    Route::post('info', 'Order/info');
    Route::post('count', 'Order/count');
    Route::post('listGetByCustomerId', 'Order/listGetByCustomerId');
    Route::post('printInfo', 'Order/printInfo');
});

