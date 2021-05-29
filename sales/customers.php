<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'customers';
include_once('../inc/sales_menu.php');
$user_id = $_SESSION['userid'];
?>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Lay ra danh sach KH hien tai
$sql =  "SELECT cu.customers_id, cu.contact_id, c.contact_name, c.address, cu.note, cu.budget, cs.value as status, cu.quotation_id, cu.update_date ".
        "FROM customers cu INNER JOIN contact c on cu.contact_id = c.id ".
        "INNER JOIN customer_status cs on cu.status = cs.status ".
        "WHERE cu.sale_id = ".$user_id." AND cu.status <> 0 AND cu.status <> 3".
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
        <th>Địa chỉ</th>
        <th>Miêu tả</th>
        <th>Ngân sách tối đa</th>
        <th>Trạng thái</th>
        <th>Mã số báo giá</th>
        <th>Chỉnh sửa</th>
    </tr>
<?php
    for ($i=0; $i<$cu_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 10%'>".$cu_list[$i]["update_date"]."</td>";
        echo "<td style='width: 10%'>".$cu_list[$i]["contact_name"]."</td>";
        echo "<td style='width: 20%'>".$cu_list[$i]["address"]."</td>";
        echo "<td>".$cu_list[$i]["note"]."</td>";
        echo "<td style='text-align: center; width: 10%'>".$cu_list[$i]["budget"]."</td>";
        echo "<td style='text-align: center; width: 15%'>".$cu_list[$i]["status"]."</td>";
        echo "<td style='text-align: center; width: 5%'>".$cu_list[$i]["quotation_id"]."</td>";
        echo "<td style='text-align: center; width: 5%'><button onclick=\"document.getElementById('".$cu_list[$i]['customers_id']."').style.display='block'\" type=\"button\">edit</button></td>";
        echo "</tr>";
    }
?>
</table>
<?php
for ($i=0; $i<$cu_num; $i++){
    echo "<div id=\"".$cu_list[$i]['customers_id']."\" class=\"modal\">";
    echo "    <form id = \"form_id_".$cu_list[$i]['customers_id']."\" class=\"modal-content animate info_form\" action=\"../controller/update_customer.php\" method=\"post\">";
    echo "    <input type=\"hidden\" name=\"mode\" value=\"update\">";
    echo "    <input type=\"hidden\" name=\"customers_id\" value=\"".$cu_list[$i]['customers_id']."\">";
    echo "    <div class=\"imgcontainer\">";
    echo "      <span onclick=\"document.getElementById('".$cu_list[$i]['customers_id']."').style.display='none'\" class=\"close\" title=\"Close Modal\">&times;</span>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label style=\"text-align: center; font-size: large\">Chỉnh sửa khách hàng</label>";
    echo "    </div> ";
    echo "    <div class=\"col-3\">";
    echo "        <label>";
    echo "        Ngày thêm";
    echo "        <input id=\"date\" name=\"date\" value =\"".$cu_list[$i]['update_date']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-3\">";
    echo "        <label>";
    echo "        Mã khách hàng";
    echo "        <input id=\"contact_id\" name=\"contact_id\" value =\"".$cu_list[$i]['contact_id']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-3\">";
    echo "        <label>";
    echo "        Ngân sách tối đa";
    echo "        <input id=\"budget\" name=\"budget\" value =\"".$cu_list[$i]['budget']."\">";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-2\">";
    echo "        <label>";
    echo "        Trạng thái";
    echo "        <select name=\"status\" id=\"status\">";
    for ($j=0; $j<$stt_num; $j++){
        if($stt_list[$j]["value"]==$cu_list[$i]['status']){
            $option = "selected";
        } else {
            $option = "";
        }
        echo "<option value='".$stt_list[$j]['status']."' ".$option.">".$stt_list[$j]['value']."</option>";
    }
    echo "         </select>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-2\">";
    echo "        <label>";
    echo "        Mã số báo giá";
if ($cu_list[$i]['quotation_id']!="" || $cu_list[$i]['quotation_id']!=0){
    echo "        <input id=\"quotation_id\" name=\"quotation_id\" value =\"".$cu_list[$i]['quotation_id']."\" readonly>";
} else {
    echo "        <br><button style='margin: 5px' id =\"add_quotation\" onclick=\"location.href='viewquotation.php?mode=add&customer_id=".$cu_list[$i]['customers_id']."'\" type=\"button\">Thêm mới</button>";
}
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Miêu tả";
    echo "        <input id=\"note\" name=\"note\" value =\"".$cu_list[$i]['note']."\" >";
    echo "        </label>";
    echo "    </div>";
    echo "    ";
    echo "";
    echo "    <div class=\"col-submit\">";
    echo "        <button class='submitbtn'>Cập nhật</button>";
    echo "        <button type=\"button\" onclick=\"document.getElementById('".$cu_list[$i]['customers_id']."').style.display='none'\" class=\"cancelbtn\">Cancel</button>";
    echo "    </div>";
    echo "  </form>";
    echo "</div>";
}
?>
<script>
var modal = document.getElementsByClassName('modal');
window.onclick = function(event) {
    for(var i =0;i<modal.length;i++){
        if (event.target == modal[i]) {
            modal[i].style.display = "none";
        }
    }
}

<?php
include_once('../inc/footer.php');
?>
