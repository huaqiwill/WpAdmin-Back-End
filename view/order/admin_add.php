<?php
include "../utils/check.php";
check_login();
header_html();

include '../utils/connect.php';
?>
<!DOCTYPE html>
<html lang="zh">
<?php include 'com_head.php' ?>
<body>

<!-- Main Wrapper -->
<div class="main-wrapper">

    <?php include 'header.html' ?>
    <?php include 'admin_sidebar.html' ?>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">订单管理</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../index/admin_index.html">主页</a></li>
                            <li class="breadcrumb-item"><a href="order.php">订单管理</a></li>
                            <li class="breadcrumb-item active">录入信息</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">选择客户下单</h4>
                            <form action="api/doaddgoods.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>下单时间</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>下单客户</label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="text" name="price" class="form-control">
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary">查询</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">商品订单信息</h4>
                            <form action="api/doaddgoods.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>备注</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>指定快递</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>延迟发货</label>
                                            <input type="text" name="vprice" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary">保存</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

<?php include "com_script.php"; ?>
<script>
    $(document).ready(function () {
        $("#order").addClass("active");
    })
</script>

</body>
</html>