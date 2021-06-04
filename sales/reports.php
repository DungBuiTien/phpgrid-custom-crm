<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'reports';
include_once('../inc/sales_menu.php');
$user_id = $_SESSION['userid'];
?>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

$sql =  "SELECT sum(original_price) as sum_original, SUM(sale_price) as sum_sale ".
        "FROM quotations q ".
        "WHERE q.sale_id = ".$user_id.
        " AND q.update_date > DATE(NOW() - INTERVAL 1 MONTH)".
        " AND q.status = 3";
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$sum_original = $tmp['sum_original'];
$sum_sale = $tmp['sum_sale'];
mysqli_free_result($result);

$sql = 'SELECT count(1) from quotations q where sale_id = '.$user_id.' AND q.update_date > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_quotation_total = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from quotations q where sale_id = '.$user_id.' AND q.status = 3 AND q.update_date > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_quotation_ok = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from quotations q where sale_id = '.$user_id.' AND q.status = 0 AND q.update_date > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_quotation_fail = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from opportunities o where sale_id = '.$user_id.' AND o.date_added > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_oppotunitie_total = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from opportunities o where sale_id = '.$user_id.' AND o.status = 2 AND o.date_added > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_oppotunitie_ok = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from opportunities o where sale_id = '.$user_id.' AND o.status = 0 AND o.date_added > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_oppotunitie_fail = $tmp[0];
mysqli_free_result($result);

$sql = 'SELECT count(1) from customers where sale_id = '.$user_id.' AND update_date > DATE(NOW() - INTERVAL 1 MONTH)';
$result = mysqli_query($connect, $sql);
$tmp = mysqli_fetch_array($result);
$num_customer_total = $tmp[0];
mysqli_free_result($result);

mysqli_close($connect);
?>
<div id="sale_report">
<h2 style="text-align: center">Báo cáo bán hàng trong tháng gần nhất </h2>
<table class="info_table">
    <tr>
        <th>Số lượng KH tiềm năng</th>
        <th>Số lượng KH</th>
        <th>Số báo giá đã gửi</th>
        <th>Tổng giá thành nhập hàng</th>
        <th>Tổng giá thành bán hàng</th>
    </tr>
    <tr>
        <td style="text-align:center"><?=$num_oppotunitie_total?></td>
        <td style="text-align:center"><?=$num_customer_total?></td>
        <td style="text-align:center"><?=$num_quotation_total?></td>
        <td style="text-align:center"><?=$sum_original?></td>
        <td style="text-align:center"><?=$sum_sale?></td>
    </tr>
</table>
</div>
<div id="chart" style="display: flex; justify-content: center;">
<div id="quotations" class="inline"></div>
<div id="opportunities" class="inline"></div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart)
function drawChart() {
    //KH tiem nang
  var data = google.visualization.arrayToDataTable([
  ['Trang thai', 'So luong'],
  ['Thành công', <?=$num_oppotunitie_ok?>],
  ['Thất bại', <?=$num_oppotunitie_fail?>]
]);

  var options = {'title':'TỈ LỆ THÀNH CÔNG TIẾP CẬN KHÁCH HÀNG TIỀM NĂNG', 'width':600, 'height':400};
  var chart = new google.visualization.PieChart(document.getElementById('quotations'));
  chart.draw(data, options);

    //Gui bao gia
  var data = google.visualization.arrayToDataTable([
  ['Trang thai', 'So luong'],
  ['Thành công', <?=$num_quotation_ok?>],
  ['Thất bại', <?=$num_quotation_fail?>]
]);

  var options = {'title':'TỈ LỆ KHÁCH HÀNG CHẤP NHẬN BÁO GIÁ', 'width':600, 'height':400};

  var chart = new google.visualization.PieChart(document.getElementById('opportunities'));
  chart.draw(data, options);
}



</script>


<?php
include_once('../inc/footer.php');
?>
