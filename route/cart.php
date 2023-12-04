<?php

use think\facade\Route;

//购物车操作
Route::group("cart", function () {
    Route::post('add', 'Cart/add');
    Route::post('list', 'Cart/list');
    Route::post('update', 'Cart/update');
    Route::post('delete', 'Cart/delete');
});
