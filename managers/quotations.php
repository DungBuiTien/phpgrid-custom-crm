<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'quotations';
include_once('../inc/manager_menu.php');
$user_id = $_SESSION['userid'];
?>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

$sql =  "SELECT distributor_id FROM distributor_user where user_id = ".$user_id;
$result = mysqli_query($connect, $sql);
$distributor = mysqli_fetch_array($result);

//Lay ra danh sach bao gia hien tai
$sql =  "SELECT q.quotation_id, c.contact_name, qs.value as status, q.original_price, q.sale_price, q.update_date ".
        "FROM quotations q ".
        "INNER JOIN contact c on q.contact_id = c.id ".
        "INNER JOIN quotation_status qs on q.status = qs.status ".
        "INNER JOIN distributor_user du on q.sale_id = du.user_id ".
        "WHERE du.distributor_id = ".$distributor[0]. 
        " ORDER BY update_date DESC";
$result = mysqli_query($connect, $sql);
$qo_num = mysqli_num_rows($result);

for ($i=0; $i<$qo_num; $i++){
    $qo_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);

$sql = 'SELECT * FROM quotation_status';
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
        <th>Mã báo giá</th>
        <th>Khách hàng</th>
        <th>Trạng thái</th>
        <th>Giá nhập hàng</th>
        <th>Giá bán</th>
        <th>Ngày cập nhật</th>
        <th>Chỉnh sửa</th>
    </tr>
<?php
    for ($i=0; $i<$qo_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 5%'><a href='viewquotation.php?mode=view&quotation_id=".$qo_list[$i]["quotation_id"]."'>".$qo_list[$i]["quotation_id"]."</a>.</td>";
        echo "<td style='width: 20%'>".$qo_list[$i]["contact_name"]."</td>";
        echo "<td style='width: 10%'>".$qo_list[$i]["status"]."</td>";
        echo "<td style='text-align: center; width: 20%'>".$qo_list[$i]["original_price"]."</td>";
        echo "<td style='text-align: center; width: 20%'>".$qo_list[$i]["sale_price"]."</td>";
        echo "<td style='text-align: center; width: 10%'>".$qo_list[$i]["update_date"]."</td>";
        echo "<td style='text-align: center; width: 5%'><button onclick=\"document.getElementById('".$qo_list[$i]['quotation_id']."').style.display='block'\" type=\"button\">edit</button></td>";
        echo "</tr>";
    }
?>
</table>
<?php
for ($i=0; $i<$qo_num; $i++){
    echo "<div id=\"".$qo_list[$i]["quotation_id"]."\" class=\"modal\" >";
    echo "    <form style=\"width: 50%\" id = \"form_id_".$qo_list[$i]["quotation_id"]."\" class=\"modal-content animate info_form\" action=\"../controller/update_quotation.php\" method=\"post\">";
    echo "    <input type=\"hidden\" name=\"mode\" value=\"update_status\">";
    echo "    <input type=\"hidden\" name=\"quotation_id\" value=\"".$qo_list[$i]["quotation_id"]."\">";
    echo "    <div class=\"imgcontainer\">";
    echo "      <span onclick=\"document.getElementById('".$qo_list[$i]["quotation_id"]."').style.display='none'\" class=\"close\" title=\"Close Modal\">&times;</span>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Trạng thái";
    echo "        <select name=\"status\" id=\"status\">";
    for ($j=0; $j<$stt_num; $j++){
        if($stt_list[$j]["value"]==$qo_list[$i]['status']){
            $option = "selected";
        } else {
            $option = "";
        }
        echo "<option value='".$stt_list[$j]['status']."' ".$option.">".$stt_list[$j]['value']."</option>";
    }
    echo "         </select>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-submit\">";
    echo "        <button class='submitbtn'>Cập nhật</button>";
    echo "        <button type=\"button\" onclick=\"document.getElementById('".$qo_list[$i]["quotation_id"]."').style.display='none'\" class=\"cancelbtn\">Cancel</button>";
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
</script>
<?php
include_once('../inc/footer.php');
?>
