<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'distributors';
include_once('../inc/vendors_menu.php');

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Kiem tra ten nguoi dung nhap vao
$sql = "SELECT distributor_id, distributor_name, distributor_address, distributor_assign_date, distributor_discount FROM distributors order by distributor_id";
$result = mysqli_query($connect, $sql);

$distributors_num = mysqli_num_rows($result);

for ($i=0; $i<$distributors_num; $i++){
    $distributors_list[$i] = mysqli_fetch_array($result);
}

// Dong ket noi
mysqli_free_result($result);
mysqli_close($connect);
?>
<div>
    <button id ="add_distributor" onclick="location.href='viewdetails.php?mode=add'" type="button">Thêm mới</button>
</div>
<p style="text-align: right; margin: 0px">Tổng số <?=$distributors_num?> nhà phân phối</p>
<table id="distributors">
    <tr>
        <th>Mã NPP</th>
        <th>Tên nhà phân phối</th>
        <th>Địa chỉ nhà phân phối</th>
        <th>Ngày bắt đầu phân phối</th>
        <th>Mức độ giảm giá(%)</th>
        <th>Doanh thu tháng gần nhất</th>
    </tr>
<?php
    for ($i=0; $i<$distributors_num; $i++){
        echo "<tr>";
        echo "<td id='distributors_id'>".$distributors_list[$i]["distributor_id"]."</td>";
        echo "<td><a href = 'viewdetails.php?mode=view&distributor_id=".$distributors_list[$i]["distributor_id"]."'>".$distributors_list[$i]["distributor_name"]."</a></td>";
        echo "<td>".$distributors_list[$i]["distributor_address"]."</td>";
        echo "<td id='distributors_date'>".(new DateTime($distributors_list[$i]["distributor_assign_date"]))->format('Y-m-d')."</td>";
        echo "<td id='distributors_discount'>".$distributors_list[$i]["distributor_discount"]."</td>";
        echo "<td></td>";
        echo "</tr>";
    }
?>
</table>

<?php include_once('../inc/footer.php'); ?>