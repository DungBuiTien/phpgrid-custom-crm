<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'tasks';
include_once('../inc/sales_menu.php');
$user_id = $_SESSION['userid'];
?>

<h3>Công việc hiện tại  | <a href="tasks_completed.php">Công việc đã hoàn thành</a></h3>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Lay ra danh sach các task hien tai
$sql =  "SELECT n.id, n.date, n.contact_id, c.contact_name, t.type, d.work, n.description, n.task_update, n.todo_due_date ".
        "FROM notes n ".
        "INNER JOIN contact c on n.contact_id = c.id ".
        "INNER JOIN todo_type t on n.todo_type_id = t.id ".
        "INNER JOIN todo_desc d on n.todo_work_id = d.id ".
        "WHERE n.sales_rep = ".$user_id." AND n.task_status = 1 ".
        "order by n.Date DESC";
$result = mysqli_query($connect, $sql);
$task_num = mysqli_num_rows($result);

for ($i=0; $i<$task_num; $i++){
    $task_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);

$sql =  "SELECT * FROM todo_type";
$result = mysqli_query($connect, $sql);
$task_type_num = mysqli_num_rows($result);

for ($i=0; $i<$task_type_num; $i++){
    $task_type_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);

$sql =  "SELECT * FROM todo_desc";
$result = mysqli_query($connect, $sql);
$task_desc_num = mysqli_num_rows($result);

for ($i=0; $i<$task_desc_num; $i++){
    $task_desc_list[$i] = mysqli_fetch_array($result);
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
        <th>Loại</th>
        <th>Tiến trình hiện tại</th>
        <th>Miêu tả công việc</th>
        <th>Cập nhật</th>
        <th>Thời gian thực hiện</th>
        <th>Chỉnh sửa</th>
    </tr>
<?php
    for ($i=0; $i<$task_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 10%'>".$task_list[$i]["date"]."</td>";
        echo "<td style='width: 10%'>".$task_list[$i]["contact_name"]."</td>";
        echo "<td style='text-align: center; width: 5%'>".$task_list[$i]["type"]."</td>";
        echo "<td style='width: 15%'>".$task_list[$i]["work"]."</td>";
        echo "<td>".$task_list[$i]["description"]."</td>";
        echo "<td>".$task_list[$i]["task_update"]."</td>";
        echo "<td style='width: 10%'>".$task_list[$i]["todo_due_date"]."</td>";
        echo "<td style='text-align: center; width: 30px'><button onclick=\"document.getElementById('".$task_list[$i]['id']."').style.display='block'\" type=\"button\">edit</button></td>";
        echo "</tr>";
    }
?>
</table>
<div id="new" class="modal">
    <form id = "form_id" class="modal-content animate info_form" action="../controller/update_task.php" method="post">
    <input type="hidden" name="mode" value="add">
    <div class="imgcontainer">
      <span onclick="document.getElementById('new').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
    <div class="col-1">
        <label style="text-align: center; font-size: large">Thêm công việc mới</label>
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
        <input id="contact" name="contact" required>
        </label>
    </div>
    <div class="col-4">
        <label>
        Loại
        <select name="type_id" id="type_id">
<?php
    for ($i=0; $i<$task_type_num; $i++){
        echo "<option value='".$task_type_list[$i]['id']."'>".$task_type_list[$i]['type']."</option>";
    }
?>
        </select>
        </label>
    </div>
    <div class="col-4">
        <label>
        Tiến trình hiện tại
        <select name="work_id" id="work_id">
<?php
    for ($i=0; $i<$task_desc_num; $i++){
        echo "<option value='".$task_desc_list[$i]['id']."'>".$task_desc_list[$i]['work']."</option>";
    }
?>
        </select>
        </label>
    </div>
    <div class="col-1">
        <label>
        Miêu tả công việc
        <input id="task_desc" name="task_desc">
        </label>
    </div>
    <div class="col-1">
        <label>
        Cập nhật
        <input id="task_update" name="task_update">
        </label>
    </div>
    <div class="col-1">
        <label>
        Thời gian thực hiện
        <input id="task_due_date" name="task_due_date">
        </label>
    </div>
    

    <div class="col-submit">
        <button class='submitbtn'>Thêm mới</button>
        <button type="button" onclick="document.getElementById('new').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>
<?php
for ($i=0; $i<$task_num; $i++){
    echo "<div id=\"".$task_list[$i]['id']."\" class=\"modal\">";
    echo "    <form id = \"form_id_".$task_list[$i]['id']."\" class=\"modal-content animate info_form\" action=\"../controller/update_task.php\" method=\"post\">";
    echo "    <input type=\"hidden\" name=\"mode\" value=\"update\">";
    echo "    <input type=\"hidden\" name=\"task_id\" value=\"".$task_list[$i]['id']."\">";
    echo "    <div class=\"imgcontainer\">";
    echo "      <span onclick=\"document.getElementById('".$task_list[$i]['id']."').style.display='none'\" class=\"close\" title=\"Close Modal\">&times;</span>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label style=\"text-align: center; font-size: large\">Chỉnh sửa công việc</label>";
    echo "    </div> ";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Ngày thêm";
    echo "        <input id=\"date\" name=\"date\" value =\"".$task_list[$i]['date']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Mã khách hàng";
    echo "        <input id=\"contact\" name=\"contact\" value =\"".$task_list[$i]['contact_id']."\" readonly>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Loại";
    echo "        <select name=\"type_id\" id=\"type_id\">";
    
        for ($j=0; $j<$task_type_num; $j++){
            if($task_list[$i]["type"]==$task_type_list[$j]['type']){
                $option = "selected";
            } else {
                $option = "";
            }
            echo "<option value='".$task_type_list[$j]['id']."' ".$option.">".$task_type_list[$j]['type']."</option>";
        }
    
    echo "        </select>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-4\">";
    echo "        <label>";
    echo "        Tiến trình hiện tại";
    echo "        <select name=\"work_id\" id=\"work_id\">";
    
        for ($k=0; $k<$task_desc_num; $k++){
            if($task_list[$i]["work"]==$task_desc_list[$k]['work']){
                $option = "selected";
            } else {
                $option = "";
            }
            echo "<option value='".$task_desc_list[$k]['id']."' ".$option.">".$task_desc_list[$k]['work']."</option>";
        }
    
    echo "        </select>";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Miêu tả công việc";
    echo "        <input id=\"task_desc\" name=\"task_desc\" value =\"".$task_list[$i]['description']."\" >";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Cập nhật";
    echo "        <input id=\"task_update\" name=\"task_update\" value =\"".$task_list[$i]['task_update']."\">";
    echo "        </label>";
    echo "    </div>";
    echo "    <div class=\"col-1\">";
    echo "        <label>";
    echo "        Thời gian thực hiện";
    echo "        <input id=\"task_due_date\" name=\"task_due_date\" value =\"".$task_list[$i]['todo_due_date']."\">";
    echo "        </label>";
    echo "    </div>";
    echo "    ";
    echo "";
    echo "    <div class=\"col-submit\">";
    echo "        <button class='submitbtn'>Update</button>";
    echo "        <button type=\"button\" onclick=\"task_complete('".$task_list[$i]['id']."')\" class=\"submitbtn\">Complete</button>";
    echo "        <button type=\"button\" onclick=\"document.getElementById('".$task_list[$i]['id']."').style.display='none'\" class=\"cancelbtn\">Cancel</button>";
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

function task_complete(id) {
    var form = 'form_id_'+id;
    document.getElementById(form).elements['mode'].value="complete";
    document.getElementById(form).submit();
}
</script>
<?php
include_once('../inc/footer.php');
?>