<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'viewquotation';
$user_id = $_SESSION['userid'];
$customer_id = addslashes($_GET['customer_id']);

if(isset($_POST['mode'])){
    $mode = addslashes($_POST['mode']);
    if ($mode!="update"){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
} else {
    $mode = addslashes($_GET['mode']);
    if ($mode!="add" && $mode!="view"){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
}

// Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
    
if ($mode=="add"){
    $page_name = "TẠO BÁO GIÁ MỚI";
    if(!check_exist_customer($connect, $customer_id, $user_id)){
        mysqli_close($connect);
        $err = "Khách hàng không tồn tại.";
        display_ErrMsg($err);
        exit;
    } else if(check_exist_quotation($connect, $customer_id)){
        mysqli_close($connect);
        $err = "Báo giá cho khách hàng tương ứng đã tồn tại.";
        display_ErrMsg($err);
        exit;
    }

    $sql =  "SELECT cu.customers_id, c.contact_name, c.address, d.distributor_name, u.name as sale_name ".
    "FROM customers cu inner join contact c on cu.contact_id = c.id ".
    "INNER JOIN users u on cu.sale_id = u.id ".
    "INNER JOIN distributor_user du on cu.sale_id = du.user_id ".
    "INNER JOIN distributors d on d.distributor_id = du.distributor_id ".
    "WHERE cu.customers_id = ?";
    
    $presmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($presmt, "i", $customer_id);
    if(!mysqli_stmt_execute($presmt)){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
    $result = array();
    mysqli_stmt_bind_result($presmt, $result['customers_id'], $result['contact_name'], $result['address'], $result['distributor_name'], $result['sale_name']);
    while (mysqli_stmt_fetch($presmt)) {
        $quotation_info = $result;
    }
    mysqli_stmt_close($presmt);
}

if($mode=="update" || $mode=="view"){

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

<div id="wrapper" style="width: 80%; margin: auto;">
    <h2 style="text-align: center"><?=$page_name?></h2>
    <form class="info_form" id='info_form' method="POST" action="../controller/update_quotation.php">
    <input type="hidden" name='mode' id='mode' value="<?php if($mode=="add"){echo "add";}else{echo "update";}?>" >
    <div class="col-1">
        <label>
        <h1 style="text-align: center">BÁO GIÁ</h1>
        </label>
    </div>
    <div class="col-4">
    <label>
        Mã đơn hàng
        <p style="text-align: center" class="quotation_text"><?=$quotation_info['customers_id']?></p>
    </label>
    </div>
    <div class="col-4">
        <label>
        Ngày tạo đơn hàng
        <p style="text-align: center" class="quotation_text"><?=(new DateTime($quotation_info["update_date"]))->format('Y/m/d')?></p>
        </label>
    </div>
    <div class="col-2">
        <label>
        Khách hàng
        <p class="quotation_text"><?=$quotation_info['contact_name']." (Địa chỉ: ".$quotation_info['address'].")"?></p>

        </label>
    </div>
    <div class="col-3">
        <label>
        Tên nhà phân phối
        <p class="quotation_text"><?=$quotation_info['distributor_name']?></p>
        </label>
    </div>
    <div class="col-3">
        <label>
        Tên nhân viên bán hàng
        <p class="quotation_text"><?=$quotation_info['sale_name']?></p>
        </label>
    </div>
    <div class="col-3">
        <label>
        Tình trạng hiện tại
        <p class="quotation_text">Báo giá mới được tạo</p>
        </label>
    </div>
    <div class="col-1">
        <label style="text-align: center">
        Danh mục sản phẩm
        </label>
        <table class="info_table" style="font-size: 14px">
            <tr>
                <th style='text-align: center; width: 10%'>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th style='text-align: center; width: 10%'>Số lượng</th>
                <th style='text-align: center; width: 10%'>Giá nhập</th>
                <th style='text-align: center; width: 10%'>Giá bán</th>
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
            echo "<button class='submitbtn'>Thêm mới</button>";
            echo "<button type='button' class='submitbtn' id='cancel_button' onclick='location.href=\"customers.php\"'>Huỷ</button>";
            break;
    }
    
?>
    </div>
    </form>
</div>


<?php
function display_ErrMsg($err){
    echo "<script type='text/javascript'> window.onload=display_ErrMsg('".$err."'); </script>";
} 
function check_exist_quotation($connect, $customer_id){
    $sql = "SELECT quotation_id FROM customers WHERE customers_id = '".$customer_id."'";
    $result = mysqli_query($connect, $sql);
    $quotation_id = mysqli_fetch_array($result);
    if ($quotation_id['quotation_id'] == NULL) {
        return false;
    } else {
        return true;
    }
}
function check_exist_customer($connect, $customer_id, $sale_id){
    $sql = "SELECT customers_id FROM customers WHERE customers_id = '".$customer_id."' AND sale_id = '".$sale_id."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}
include_once('../inc/footer.php');
?>