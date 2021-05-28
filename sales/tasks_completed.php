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

<h3><a href="tasks.php">Công việc hiện tại</a> | Công việc đã hoàn thành</h3>

<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Lay ra danh sach các task hien tai
$sql =  "SELECT n.date, c.contact_name, t.type, d.work, n.description, n.task_update, n.todo_due_date ".
        "FROM notes n ".
        "INNER JOIN contact c on n.contact_id = c.id ".
        "INNER JOIN todo_type t on n.todo_type_id = t.id ".
        "INNER JOIN todo_desc d on n.todo_work_id = d.id ".
        "WHERE n.sales_rep = ".$user_id." AND n.task_status = 2 ".
        "order by n.Date DESC";
$result = mysqli_query($connect, $sql);
$task_num = mysqli_num_rows($result);

for ($i=0; $i<$task_num; $i++){
    $task_list[$i] = mysqli_fetch_array($result);
}

// Dong ket noi
mysqli_free_result($result);
mysqli_close($connect);
?>
<table class="info_table">
    <tr>
        <th>Ngày thêm</th>
        <th>Khách hàng</th>
        <th>Loại</th>
        <th>Tiến trình hiện tại</th>
        <th>Miêu tả công việc</th>
        <th>Cập nhật</th>
        <th>Thời gian thực hiện</th>
    </tr>
<?php
    for ($i=0; $i<$task_num; $i++){
        echo "<tr>";
        echo "<td style='text-align: center; width: 10%'>".$task_list[$i]["date"]."</td>";
        echo "<td style='width: 10%'>".$task_list[$i]["contact_name"]."</td>";
        echo "<td style='text-align: center; width: 5%'>".$task_list[$i]["type"]."</td>";
        echo "<td style='text-align: center; width: 15%'>".$task_list[$i]["work"]."</td>";
        echo "<td>".$task_list[$i]["description"]."</td>";
        echo "<td>".$task_list[$i]["task_update"]."</td>";
        echo "<td style='width: 10%'>".$task_list[$i]["todo_due_date"]."</td>";
        echo "</tr>";
    }
?>
</table>


<?php
include_once('../inc/footer.php');
?>