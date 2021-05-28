<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['form_type']);
$distributor_info = array();
$distributor_info['distributor_name'] = addslashes($_POST['distributor_name']);
$distributor_info['distributor_id'] = addslashes($_POST['distributor_id']);
$distributor_info['distributor_address'] = addslashes($_POST['distributor_address']);
$distributor_info['distributor_assign_date'] = addslashes($_POST['distributor_assign_date']);
$distributor_info['distributor_discount'] = addslashes($_POST['distributor_discount']);

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

switch($mode){
    case "add":
        $sql = "INSERT INTO distributors values ( NULL,?,?,?, CURRENT_TIMESTAMP())";
        $presmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($presmt, "sss", $distributor_info['distributor_name'], $distributor_info['distributor_address'], $distributor_info['distributor_discount']);
        if(!mysqli_stmt_execute($presmt)){
            mysqli_close($connect);
            $err = "Thêm nhà phân phối mới thất bại.";
            display_ErrMsg($err);
            exit;
        }
        mysqli_stmt_close($presmt);
        $sql = "SELECT LAST_INSERT_ID()";
        $result = mysqli_query($connect, $sql);
        $insert_id = mysqli_fetch_array($result);
        $redirect = "../vendors/viewdetails.php?mode=view&distributor_id=".$insert_id[0];
        header("Location: $redirect");
        break;
    case "update":
        if(!check_exist($connect, $distributor_info['distributor_id'])){
            $err = "Nhà phân phối không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE distributors SET distributor_name = ?, distributor_address = ?, distributor_discount = ? WHERE distributor_id = ?";
            $presmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($presmt, "ssss", $distributor_info['distributor_name'], $distributor_info['distributor_address'], $distributor_info['distributor_discount'], $distributor_info['distributor_id']);
            if(!mysqli_stmt_execute($presmt)){
                mysqli_close($connect);
                $err = "Cập nhật nhà phân phối thất bại";
                display_ErrMsg($err);
                exit;
            }
            mysqli_stmt_close($presmt);
            $redirect = "../vendors/viewdetails.php?mode=view&distributor_id=".$distributor_info['distributor_id'];
            header("Location: $redirect");
        }
        break;
    case "delete":
        if(!check_exist($connect, $distributor_info['distributor_id'])){
            $err = "Nhà phân phối không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            mysqli_begin_transaction($connect);

            $sql1 = "DELETE FROM distributors WHERE distributor_id = ".$distributor_info['distributor_id'];
            $sql2 = "DELETE FROM users WHERE id IN (SELECT user_id from distributor_user where distributor_id = ".$distributor_info['distributor_id'].")";
            $sql3 = "DELETE FROM distributor_user WHERE distributor_id = ".$distributor_info['distributor_id'];
            try {
                mysqli_query($connect, $sql1);
                mysqli_query($connect, $sql2);
                mysqli_query($connect, $sql3);
                mysqli_commit($connect);
            }catch (mysqli_sql_exception $exception){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Xoá nhà phân phối thất bại.";
                display_ErrMsg($err);
                exit;
            }
            $redirect = "../vendors/distributors.php";
            header("Location: $redirect");
        }
        break;
    default:
        $err = "Đã có lỗi xảy ra.";
        mysqli_close($connect);
        display_ErrMsg($err);
        exit;
    }
    mysqli_close($connect);
?>

<?php
function check_exist($connect, $distributor_id){
    $sql = "SELECT distributor_id FROM distributors WHERE distributor_id =".$distributor_id;
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}
function display_ErrMsg($err){
    echo "<script type='text/javascript'> window.onload=display_ErrMsg('".$err."'); </script>";
} 
?>
