<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'opportunities';
include_once('../inc/sales_menu.php');
$user_id = $_SESSION['userid'];
?>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Lay ra danh sach các task hien tai
$sql =  "SELECT o.opportunities_id, c.id as contact_id, c.contact_name, c.address, u.name, o.note, o.priority, o.budget, o.date_added ".
        "FROM opportunities o INNER JOIN contact c on o.contact_id = c.id ".
        "INNER JOIN users u on o.sale_id = u.id ".
        "WHERE o.sale_id = ".$user_id." AND status = 1 ".
        " ORDER BY o.priority DESC";
$result = mysqli_query($connect, $sql);
$op_num = mysqli_num_rows($result);

for ($i=0; $i<$op_num; $i++){
    $op_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);
mysqli_close($connect);
?>
<div>
    <button onclick="document.getElementById('new').style.display='block'" type="button" style="margin: 5px">Thêm mới</button>
</div>
<table class="info_table">
    <tr>
        <th>Ngày thêm</th>
        <th>Khách hàng</th>
        <th>Địa chỉ</th>
        <th>Miêu tả</th>
        <th>Độ ưu tiên (1-5)</th>
        <th>Ngân sách tối đa</th>
        <th>Chỉnh sửa</th>
    </tr>
<?php
    for ($i=0; $i<$op_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 10%'>".$op_list[$i]["date_added"]."</td>";
        echo "<td style='width: 10%'>".$op_list[$i]["contact_name"]."</td>";
        echo "<td style='width: 20%'>".$op_list[$i]["address"]."</td>";
        echo "<td>".$op_list[$i]["note"]."</td>";
        echo "<td style='text-align: center; width: 5%'>".$op_list[$i]["priority"]."</td>";
        echo "<td style='text-align: center; width: 5%'>".$op_list[$i]["budget"]."</td>";
        echo "<td style='text-align: center; width: 5%'><button onclick=\"document.getElementById('".$op_list[$i]['opportunities_id']."').style.display='block'\" type=\"button\">edit</button></td>";
        echo "</tr>";
    }
?>
</table>
<div id="new" class="modal">
    <form id = "form_id" class="modal-content animate info_form" action="../controller/update_opp.php" method="post">
    <input type="hidden" name="mode" value="add">
    <div class="imgcontainer">
      <span onclick="document.getElementById('new').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
    <div class="col-1">
        <label style="text-align: center; font-size: large">Thêm cơ hội mới</label>
    </div> 
    <div class="col-4">
        <label>
        Ngày thêm
        <input id="date" name="date" value ="<?=date("Y/m/d")?>" readonly>
        </label>
    </div>
    <div class="col-4">
        <label>
        Mã khách hàng
        <input id="contact_id" name="contact_id" required>
        </label>
    </div>
    <div class="col-4">
        <label>
        Độ ưu tiên
        <select name="priority" id="priority">
            <option value=1>1</option>
            <option value=2>2</option>
            <option value=3>3</option>
            <option value=4>4</option>
            <option value=5>5</option>
        </select>
        </label>
    </div>
    <div class="col-4">
        <label>
        Ngân sách tối đa
        <input id="budget" name="budget" type="number">
        </label>
    </div>
    <div class="col-1">
        <label>
        Miêu tả
        <input id="note" name="note" required>  
        </label>
    </div>

    <div class="col-submit">
        <button class='submitbtn'>Thêm mới</button>
        <button type="button" onclick="document.getElementById('new').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>
<?php
for ($i=0; $i<$op_num; $i++){
    echo "<div id=\"".$op_list[$i]['opportunities_id']."\" class=\"modal\">";
    echo "    <form id = \"form_id_".$op_list[$i]['opportunities_id']."\" class=\"modal-content animate info_form\" action=\"../controller/update_opp.php\" method=\"post\">";
    echo "    <input type=\"hidden\" name=\"mode\" value=\"update\">";
    echo "    <input type=\"hidden\" name=\"opportunities_id\" value=\"".$op_list[$i]['opportunities_id']."\">";
    echo "    <div class=\"imgcontainer\">";
    echo "      <span onclick=\"document.getElementById('".$op_list[$i]['opportunities_id']."').style.display='none'\" class=\"close\" title=\"Close Modal\">&times;</span>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label style=\"text-align: center; font-size: large\">Chỉnh sửa cơ hội mới</label>";
    echo "    </div> ";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Ngày thêm";
    echo "        <input id=\"date\" name=\"date\" value =\"".$op_list[$i]['date_added']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Mã khách hàng";
    echo "        <input id=\"contact_id\" name=\"contact_id\" value =\"".$op_list[$i]['contact_id']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Độ ưu tiên";
    echo "        <select name=\"priority\" id=\"priority\">";
    echo "            <option value=1 ";if($op_list[$i]["priority"]==1) echo "selected"; echo">1</option>";
    echo "            <option value=2 ";if($op_list[$i]["priority"]==2) echo "selected"; echo">2</option>";
    echo "            <option value=3 ";if($op_list[$i]["priority"]==3) echo "selected"; echo">3</option>";
    echo "            <option value=4 ";if($op_list[$i]["priority"]==4) echo "selected"; echo">4</option>";
    echo "            <option value=5 ";if($op_list[$i]["priority"]==5) echo "selected"; echo">5</option>";
    echo "         </select>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Ngân sách tối đa";
    echo "        <input id=\"budget\" name=\"budget\" value =\"".$op_list[$i]['budget']."\">";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Miêu tả";
    echo "        <input id=\"note\" name=\"note\" value =\"".$op_list[$i]['note']."\" >";
    echo "        </label>";
    echo "    </div>";
    echo "    ";
    echo "";
    echo "    <div class=\"col-submit\">";
    echo "        <button class='submitbtn'>Cập nhật</button>";
    echo "        <button type=\"button\" onclick=\"op_complete('".$op_list[$i]['opportunities_id']."')\" class=\"submitbtn\">Thành công</button>";
    echo "        <button type=\"button\" onclick=\"op_fail('".$op_list[$i]['opportunities_id']."')\" class=\"submitbtn\">Thất bại</button>";
    echo "        <button type=\"button\" onclick=\"document.getElementById('".$op_list[$i]['opportunities_id']."').style.display='none'\" class=\"cancelbtn\">Cancel</button>";
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

function op_complete(id) {
    var form = 'form_id_'+id;
    document.getElementById(form).elements['mode'].value="complete";
    document.getElementById(form).submit();
}

function op_fail(id) {
    var form = 'form_id_'+id;
    document.getElementById(form).elements['mode'].value="fail";
    document.getElementById(form).submit();
}
</script>
<?php
include_once('../inc/footer.php');
?>
