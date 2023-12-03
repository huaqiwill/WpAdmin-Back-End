<?php
include './utils/check.php';
check_login();
header_html();

// Include database connection
include('./utils/connect.php');
$records = array();
// 获取客户姓名
if (isset($_GET['customerName'])) {
    $customerName = mysql_real_escape_string($_GET['customerName']);  // Sanitize the input to prevent SQL injection

    // 执行查询，从'ding'表中根据商品名称检索数据
    $query = "select * from ding where kename like '%$customerName%'";
    $result = mysql_query($query);
    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $records[] = $row;
        }
    } else {
        echo '未找到相关数据。';
    }
} else {
    echo '无效的请求。';
}

?>

<?php if (count($records) > 0): ?>
    <table class="table table-center table-hover datatable">
        <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>类型</th>
            <th>订单号</th>
            <th>总金额</th>
            <th>收件人姓名</th>
            <th>电话</th>
            <th>快递公司</th>
            <th>快递单号</th>
            <th>日期1</th>
            <th>日期2</th>
            <th>日期3</th>
            <th>商品名称</th>
            <th>商品ID</th>
            <th>数量</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $row) : ?>
            <tr>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['type'] ?></td>
                <td><?php echo $row['ordernum'] ?></td>
                <td><?php echo $row['total'] ?></td>
                <td><?php echo $row['shouname'] ?></td>
                <td><?php echo $row['tel'] ?></td>
                <td><?php echo $row['kuainame'] ?></td>
                <td><?php echo $row['kuainum'] ?></td>
                <td><?php echo $row['date01'] ?></td>
                <td><?php echo $row['date02'] ?></td>
                <td><?php echo $row['date03'] ?></td>
                <td><?php echo $row['kename'] ?></td>
                <td><?php echo $row['gid'] ?></td>
                <td><?php echo $row['num'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
