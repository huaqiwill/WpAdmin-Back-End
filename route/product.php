<?php

use think\facade\Route;

//产品操作
Route::group("product", function () {
    Route::get('index', 'Product/index');
    Route::get('add', 'Product/add');
    Route::get('update', 'Product/update');

    Route::post('detail', 'Product/detail');
    Route::post('add', 'Product/add');
    Route::post('info', 'Product/info');
    Route::post('list', 'Product/list');
    Route::post('update', 'Product/update');
    Route::post('delete', 'Product/delete');
    Route::post('nameList', 'Product/nameList');
});
