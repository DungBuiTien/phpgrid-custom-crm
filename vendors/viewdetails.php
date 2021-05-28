<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'viewdetails';

$distributor_id = addslashes($_GET['distributor_id']);
$mode = addslashes($_GET['mode']);
if($mode!="add" && $mode!="update" && $mode!="view") $mode = "add";

$distributor_info = array();

if($mode=="update" || $mode=="view"){
    // Mo ket noi den database
    $connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
    
    // Truy van thong tin nha phan phoi
    $sql =  "SELECT d.distributor_id, distributor_name, distributor_address, distributor_assign_date, distributor_discount, count(1) as distributor_num ".
            "FROM distributors d inner join distributor_user du on d.distributor_id = du.distributor_id ".
            "WHERE d.distributor_id = ?";
            
    $presmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($presmt, "i", $distributor_id);
    if(!mysqli_stmt_execute($presmt)){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
    $result = array();
    mysqli_stmt_bind_result($presmt, $result['distributor_id'], $result['distributor_name'], $result['distributor_address'], $result['distributor_assign_date'], $result['distributor_discount'], $result['distributor_num'] );
    while (mysqli_stmt_fetch($presmt)) {
        $distributor_info = $result;
    }
    mysqli_stmt_close($presmt);
    
    if(!isset($distributor_info['distributor_id'])){
        $err = "Nhà phân phối không tồn tại";
        display_ErrMsg($err);
        exit;
    }
}
?>

<div id="wrapper">
    <h2>Trang thông tin nhà phân phối</h2>
    <a style="margin: 0px" href="distributors.php"><<< Quay lại trang trước</a>
    <form class="info_form" id='info_form' method="POST" action="../controller/update_distributor.php">
    <input type="hidden" name='form_type' id='form_type' value="<?php if($mode=="add"){echo "add";}else{echo "update";}?>" >
    <div class="col-2">
    <label>
        Tên nhà phân phối
        <input placeholder="" id="distributor_name" name="distributor_name" tabindex="1" value="<?=$distributor_info['distributor_name']?>" <?php if($mode=="view") echo "readonly"?> required>
    </label>
    </div>
    <div class="col-2">
        <label>
        Mã số nhà phân phối
        <input placeholder="" id="distributor_id" name="distributor_id" tabindex="2" value="<?=$distributor_info['distributor_id']?>" readonly>
        </label>
    </div>
    <div class="col-1">
        <label>
        Địa chỉ nhà phân phối
        <input placeholder="" id="distributor_address" name="distributor_address" tabindex="3" value="<?=$distributor_info['distributor_address']?>" <?php if($mode=="view") echo "readonly"?> required>
        </label>
    </div>
    <div class="col-4">
        <label>
        Ngày bắt đầu phân phối
        <input id="distributor_assign_date" name="distributor_assign_date" tabindex="4" value="<?=(new DateTime($distributor_info["distributor_assign_date"]))->format('Y/m/d')?>" readonly>
        </label>
    </div>
    <div class="col-4">
        <label>
        Mức độ chiết khấu(%)
        <input id="distributor_discount" name="distributor_discount" tabindex="5" value="<?=$distributor_info['distributor_discount']?>" <?php if($mode=="view") echo "readonly"?> required>
        </label>
    </div>
    <div class="col-4">
        <label>
        Số nhân viên
        <input id="distributor_num" name="distributor_num" tabindex="6" value="<?=$distributor_info['distributor_num']?>" readonly>
        </label>
    </div>
    <div class="col-4">
        <label>
        Doanh số tháng gần nhất
        <input id="distributor_order" name="distributor_order" tabindex="7" value="4" readonly>
        </label>
    </div>
    <div class="col-submit">
<?php
    switch($mode){
        case "view":
            echo "<button type='button' onclick=\"location.href='viewdetails.php?mode=update&distributor_id=".$distributor_info['distributor_id']."'\">Chỉnh sửa</button>";
            echo "<button type='button' class='submitbtn' id='del_button' onclick='setDelPro()'>Xoá nhà phân phối</button>";
            break;
        case "update":
        case "add":
            echo "<button class='submitbtn'>Cập nhật</button>";
            break;
    }
    
?>
    </div>
    </form>
</div>

<?php 
if($mode=="view"){
    echo "<button type='button' onclick=\"document.getElementById('id01').style.display='block'\">Thêm nhân viên</button>";    

    $sql =  "SELECT u.id, u.name, u.email, r.role, s.status ".
            "FROM users u INNER JOIN distributor_user du on u.id = du.user_id ".
            "INNER JOIN roles r on u.user_roles = r.id ".
            "INNER JOIN user_status s on u.user_status = s.id";
    $dg = new C_DataGrid($sql, "id");
    $dg->set_query_filter("du.distributor_id = ".$distributor_info['distributor_id']);
    $dg->set_caption('Danh sách nhân viên của nhà phân phối');

    $dg->set_col_title("id", "Mã nhân viên")->set_col_width("id", 20)->set_col_align("id", 'center');
    $dg->set_col_title("name", "Tên nhân viên")->set_col_width("name", 100);
    $dg->set_col_title("role", "Vai trò");
    $dg->set_col_title("status", "Tình trạng")->set_col_width("status", 50);
    $dg->enable_search(true);
    $dg->display();
}
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
            <option value="1">Nhân viên bán hàng</option>
            <option value="2">Quản lý</option>
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

<?php
function display_ErrMsg($err){
    echo "<script type='text/javascript'> window.onload=display_ErrMsg('".$err."'); </script>";
} 
?>

<script>
function setDelPro() {
  document.getElementById("form_type").value="delete";
  document.getElementById("info_form").submit();
}
</script>

<?php
    include_once('../inc/footer.php');
?>