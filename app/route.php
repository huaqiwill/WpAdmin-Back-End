<?php

use app\middleware\AddressCheck;
use app\middleware\CartCheck;
use app\middleware\CustomerCheck;
use app\middleware\DeliveryCheck;
use app\middleware\OrderCheck;
use app\middleware\PermissionCheck;
use app\middleware\ProductCheck;
use app\middleware\ReportCheck;
use app\middleware\RoleCheck;
use app\middleware\UserCheck;
use think\facade\Route;

Route::get('/', 'Index/index')->ext("html");

//主页
Route::group("index", function () {
    Route::post('statisticList', 'Index/statisticList');
});

//地址操作
Route::group("address", function () {
    Route::get('index', 'index')->ext("html");
    Route::get('add', 'add')->ext("html");
    Route::get('update', 'update')->ext("html");

    Route::post('add', 'add');
    Route::post('list', 'list');
    Route::post('update', 'update');
    Route::post('delete', 'delete');
    Route::post('info', 'info');
})->prefix("Address/")->middleware(AddressCheck::class);

//购物车操作
Route::group("cart", function () {
    Route::post('add', 'add');
    Route::post('list', 'list');
    Route::post('update', 'update');
    Route::post('delete', 'delete');
})->prefix("Cart/")->middleware(CartCheck::class);

//顾客操作
Route::group("customer", function () {
    Route::get('index', 'index')->ext("html");
    Route::get('add', 'add')->ext("html");
    Route::get('update', 'update')->ext("html");

    Route::post('add', 'add');
    Route::post('update', 'update');
    Route::post('delete', 'delete');
    Route::post('info', 'info');
    Route::post('orderList', 'orderList');
    Route::post('list', 'list');
    Route::post('nameList', 'nameList');
    Route::post('count', 'count');
    Route::post('getByPhone', 'getByPhone');
    Route::post('phoneList', 'phoneList');
})->prefix("Customer/")->middleware(CustomerCheck::class);

//快递操作
Route::group("delivery", function () {
    Route::get('index', 'index')->ext("html");

    Route::post('add', 'add');
    Route::post('update', 'update');
    Route::post('disable', 'disable');
    Route::post('enable', 'enable');
    Route::post('nameList', 'nameList');
    Route::post('list', 'list');
    Route::post('info', 'info');
})->prefix("Delivery/")->middleware(DeliveryCheck::class);

//订单操作
Route::group("order", function () {
    Route::get('index', 'index')->ext("html");
    Route::get('add', 'add')->ext("html");
    Route::get('update', 'update')->ext("html");
    Route::get('detail', 'detail')->ext("html");
    Route::get('print', 'print')->ext("html");

    Route::post('add', 'add');
    Route::post('update', 'update');
    Route::post('list', 'list');
    Route::post('delete', 'delete');
    Route::post('submit', 'submit');
    Route::post('confirm', 'confirm');
    Route::post('productList', 'productList');
    Route::post('productAdd', 'productAdd');
    Route::post('productUpdate', 'productUpdate');
    Route::post('productDelete', 'productDelete');
    Route::post('phoneQuery', 'phoneQuery');
    Route::post('info', 'info');
    Route::post('count', 'count');
    Route::post('listGetByCustomerId', 'listGetByCustomerId');
    Route::post('printInfo', 'printInfo');
})->prefix("Order/")->middleware(OrderCheck::class);

//产品操作
Route::group("product", function () {
    Route::get('index', 'index')->ext("html");
    Route::get('add', 'add')->ext("html");
    Route::get('update', 'update')->ext("html");

    Route::post('detail', 'detail');
    Route::post('add', 'add');
    Route::post('info', 'info');
    Route::post('list', 'list');
    Route::post('update', 'update');
    Route::post('delete', 'delete');
    Route::post('nameList', 'nameList');
})->prefix("Product/")->middleware(ProductCheck::class);

//报表操作
Route::group("report", function () {
    Route::get('index', 'index')->ext("html");
    Route::post('userAchievement', 'userAchievement');
})->prefix("Report/")->middleware(ReportCheck::class);

//角色操作
Route::group("role", function () {
    Route::get('index', 'index')->ext("html");
    Route::get("sidebar", "sidebar");

    // Route::post('list', 'list');
    Route::post('add', 'add');
    Route::post('update', 'update');
    Route::post('delete', 'delete');
    Route::post('info', 'info');
})->prefix("Role/")->middleware(RoleCheck::class);

//权限操作
Route::group("permission", function () {
    Route::get("index", "index")->ext("html");
    Route::get("add", "add")->ext("html");
    Route::get("update", "update")->ext("html");
    Route::get("menus", "menus");

    Route::post("list", "list");
    Route::post("add", "add");
    Route::post("info", "info");
    Route::post("update", "update");
    Route::post("enable", "enable");
    Route::post("disable", "disable");
    Route::post("namelist", "namelist");
})->prefix("Permission/")->middleware(PermissionCheck::class);

//用户操作
Route::group("user", function () {
    Route::get('index', 'index')->ext("html");
    Route::get('login', 'login')->ext("html");
    Route::get('add', 'add')->ext("html");
    Route::get('update', 'update')->ext("html");

    Route::post('update', 'update');
    Route::post('add', 'add');
    Route::post('delete', 'delete');
    Route::post('info', 'info');
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('freeze', 'freeze');
    Route::post('unfreeze', 'unfreeze');
    Route::post('list', 'list');
})->prefix("User/")->middleware(UserCheck::class);
