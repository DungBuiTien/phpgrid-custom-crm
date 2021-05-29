<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'employee';
include_once('../inc/manager_menu.php');
$user_id = $_SESSION['userid'];
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
$connect->set_charset("utf8");

$sql1 = "SELECT distributor_id
FROM distributor_user
WHERE distributor_user.user_id = ".$user_id."";
$result = mysqli_query($connect, $sql1);
$stt_num = mysqli_num_rows($result);

$distributor_info = mysqli_fetch_array($result);
mysqli_close($connect);

echo "<button style='margin: 5px' type='button' onclick=\"document.getElementById('id01').style.display='block'\">Thêm nhân viên</button>";    

    $sql =  "SELECT u.id, u.name, u.email, r.role, s.status ".
            "FROM users u INNER JOIN distributor_user du on u.id = du.user_id ".
            "INNER JOIN roles r on u.user_roles = r.id ".
            "INNER JOIN user_status s on u.user_status = s.id";
    $dg = new C_DataGrid($sql, "id");
    $dg->set_query_filter("du.distributor_id = ".$distributor_info["distributor_id"]);
    $dg->set_caption('Danh sách nhân viên của nhà phân phối');

    $dg->set_col_title("id", "Mã nhân viên")->set_col_width("id", 20)->set_col_align("id", 'center');
    $dg->set_col_title("name", "Tên nhân viên")->set_col_width("name", 100);
    $dg->set_col_title("role", "Vai trò");
    $dg->set_col_title("status", "Tình trạng")->set_col_width("status", 50);
    $dg->enable_search(true);
    $dg->display();
?>
<div id="id01" class="modal">
    
    <form class="modal-content animate info_form" action="../controller/update_user.php" method="post">
    <input type="hidden" name="mode" value="add">
    <input type="hidden" name="distributor_id" value="<?=$distributor_info['distributor_id']?>">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
    <div class="col-1">
        <label style="text-align: center; font-size: large">Thêm người dùng mới</label>
    </div> 
    <div class="col-3">
        <label>
        Tài khoản
        <input id="username" name="username" tabindex="11" required>
        </label>
    </div>
    <div class="col-3">
        <label>
        Mật khẩu
        <input id="password" name="password" tabindex="12" required>
        </label>
    </div>
    <div class="col-3" style="height: 77px">
        <label>
        Vai trò
        <select name="role" id="role">
            <option value="1" selected>Nhân viên bán hàng</option>
        </select>
        </label>
    </div>
    <div class="col-1">
        <label>
        Tên nhân viên
        <input id="name" name="name" tabindex="13">
        </label>
    </div>
    <div class="col-1">
        <label>
        Email
        <input id="email" name="email" tabindex="14">
        </label>
    </div>
    

    <div class="col-submit">
        <button class='submitbtn'>Thêm mới</button>
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>

<script>
var modal = document.getElementById('id01');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>