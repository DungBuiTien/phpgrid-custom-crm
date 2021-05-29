<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'customers';
include_once('../inc/manager_menu.php');
$user_id = $_SESSION['userid'];
?>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

$sql =  "SELECT distributor_id FROM distributor_user where user_id = ".$user_id;
$result = mysqli_query($connect, $sql);
$distributor = mysqli_fetch_array($result);

//Lay ra danh sach KH hien tai
$sql =  "SELECT cu.customers_id, cu.contact_id, u.name as sale_name, c.contact_name, c.address, cu.note, cu.budget, cs.value as status, cu.quotation_id, cu.update_date ".
        "FROM customers cu INNER JOIN contact c on cu.contact_id = c.id ".
        "INNER JOIN customer_status cs on cu.status = cs.status ".
        "INNER JOIN distributor_user du on cu.sale_id = du.user_id ".
        "INNER JOIN users u ON cu.sale_id = u.id ".
        "WHERE du.distributor_id = ".$distributor[0].
        " AND cu.status <> 0 AND cu.status <> 3".
        " ORDER BY cu.update_date DESC";
$result = mysqli_query($connect, $sql);
$cu_num = mysqli_num_rows($result);

for ($i=0; $i<$cu_num; $i++){
    $cu_list[$i] = mysqli_fetch_array($result);
    if ($cu_list[$i]["budget"]==0){
        $cu_list[$i]["budget"]="";
    }
}
mysqli_free_result($result);

$sql = 'SELECT * FROM customer_status';
$result = mysqli_query($connect, $sql);
$stt_num = mysqli_num_rows($result);

for ($i=0; $i<$stt_num; $i++){
    $stt_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);

mysqli_close($connect);
?>
<div>
    <br>
</div>
<table class="info_table">
    <tr>
        <th>Ngày thêm</th>
        <th>Khách hàng</th>
        <th>Nhân viên bán hàng</th>
        <th>Địa chỉ</th>
        <th>Miêu tả</th>
        <th>Ngân sách tối đa</th>
        <th>Trạng thái</th>
        <th>Mã số báo giá</th>
    </tr>
<?php
    for ($i=0; $i<$cu_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 10%'>".$cu_list[$i]["update_date"]."</td>";
        echo "<td style='width: 10%'>".$cu_list[$i]["contact_name"]."</td>";
        echo "<td style='width: 10%'>".$cu_list[$i]["sale_name"]."</td>";
        echo "<td style='width: 20%'>".$cu_list[$i]["address"]."</td>";
        echo "<td>".$cu_list[$i]["note"]."</td>";
        echo "<td style='text-align: center; width: 10%'>".$cu_list[$i]["budget"]."</td>";
        echo "<td style='text-align: center; width: 15%'>".$cu_list[$i]["status"]."</td>";
        echo "<td style='text-align: center; width: 5%'><a href='viewquotation.php?mode=view&quotation_id=".$cu_list[$i]["quotation_id"]."'>".$cu_list[$i]["quotation_id"]."</a></td>";
        echo "</tr>";
    }
?>
</table>

<script>
var modal = document.getElementsByClassName('modal');
window.onclick = function(event) {
    for(var i =0;i<modal.length;i++){
        if (event.target == modal[i]) {
            modal[i].style.display = "none";
        }
    }
}
</script>
<?php
include_once('../inc/footer.php');
?>
