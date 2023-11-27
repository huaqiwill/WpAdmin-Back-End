<?php

use think\facade\Route;

Route::get('/', 'IndexController/index');

//用户操作
Route::get('/user', 'IndexController/index');
Route::post('/user/login', 'UserController/login');
Route::post('/user/register', 'UserController/register');

//商品操作
Route::get('/item/list', 'ItemController/list');
Route::get('/item/detail', 'ItemController/detail');
Route::post('/item/create', 'ItemController/create');
Route::post('/item/update', 'ItemController/update');
Route::post('/item/delete', 'ItemController/delete');

//顾客操作
Route::get('/customer/list', 'CustomerController/list');
Route::get('/customer/detail', 'CustomerController/detail');
Route::post('/customer/create', 'CustomerController/create');
Route::post('/customer/update', 'CustomerController/update');
Route::post('/customer/delete', 'CustomerController/delete');

//注册中间件
//Route::middleware('TokenVerify')->group(function () {
//    //商品操作
//    Route::post('item/list', 'UserController/list');
//    Route::post('item/add', 'UserController/add');
//    Route::post('item/del', 'UserController/del');
//    Route::post('item/update', 'UserController/update');
//});
