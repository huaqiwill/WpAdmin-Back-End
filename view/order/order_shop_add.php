<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include './utils/db_user.php';
    include './utils/db_cart.php';
    include './utils/db_goods.php';

    //参数列表
    $gid = $_POST['gid'];
    $uid = user_get_id($_SESSION['username']);
    //如果已经存在该商品，则修改商品，否则添加商品
    if (cart_exists($gid, $uid)) {
        if ($row = cart_update($gid, $uid)) {
            $data = array(
                'price' => $row['price'],
                'vprice' => $row['vprice']
            );
            exit(ret_ok("更新成功", $data));
        } else {
            exit(ret_err("商品数量更新失败" . mysql_error()));
        }
    } else {
        if ($good = goods_info($gid)) {
            if (cart_insert($gid, $uid)) {
                $data = array(
                    'price' => $good['price'],
                    'vprice' => $good['vprice']
                );
                exit(ret_ok("添加成功", $data));
            } else {
                exit(ret_err("商品添加失败：" . mysql_error()));
            }
        } else {
            exit(ret_err('查询商品信息失败：' . mysql_error()));
        }
    }
} else {
    exit();
}
