<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['mode']);
$op_info = array();
$op_info['opportunities_id'] = addslashes($_POST['opportunities_id']);
$op_info['date'] = addslashes($_POST['date']);
$op_info['contact_id'] = addslashes($_POST['contact_id']);
$op_info['priority'] = addslashes($_POST['priority']);
$op_info['budget'] = addslashes($_POST['budget']);
$op_info['note'] = addslashes($_POST['note']);
$user_id = $_SESSION['userid'];

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

switch($mode){
    case "add":
        if(!check_exist_contact($connect, $op_info['contact_id'])){
            $err = "Khách hàng không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "INSERT INTO opportunities (opportunities_id, contact_id, sale_id, note, priority, budget, status, date_added) ".
            " values (NULL, '".$op_info['contact_id']."', '".$user_id."', '".$op_info['note']."', '".$op_info['priority']."', '".$op_info['budget']."', 1 ".
            ", CURRENT_TIMESTAMP())";
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/opportunities.php";
            header("Location: $redirect");
        }
        break;
    case "update":
        if(!check_exist_contact($connect, $op_info['contact_id']) || $op_info['opportunities_id']==""){
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE opportunities SET ".
            "note = '".$op_info['note']."', ".
            "priority = '".$op_info['priority']."', ".
            "budget = '".$op_info['budget']."' ".
            "WHERE opportunities_id = ".$op_info['opportunities_id'];
            var_dump($sql);
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/opportunities.php";
            header("Location: $redirect");
        }
        break;
    case "complete":
        if(!check_exist_contact($connect, $op_info['contact_id']) || $op_info['opportunities_id']==""){
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            mysqli_begin_transaction($connect);
            try{
                $sql1 = "UPDATE opportunities SET status = 2 WHERE opportunities_id = ".$op_info['opportunities_id'];
                $sql2 = "INSERT INTO customers (customers_id, contact_id, sale_id, note, status, budget, quotation_id, update_date) ".
                        " values (NULL, '".$op_info['contact_id']."', '".$user_id."', '".$op_info['note']."', 1, '".$op_info['budget']."', NULL, ".
                        " CURRENT_TIMESTAMP())";
                var_dump($sql1);
                var_dump($sql2);
                mysqli_query($connect, $sql1);
                mysqli_query($connect, $sql2);
                mysqli_commit($connect);
                mysqli_close($connect); 
            }catch (Throwable $x){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Cập nhật thất bại.";
                display_ErrMsg($err);
                exit;
            }
            $redirect = "../sales/opportunities.php";
            header("Location: $redirect");
        }
        break;
    case "fail":
        if(!check_exist_contact($connect, $op_info['contact_id']) || $op_info['opportunities_id']==""){
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE opportunities SET status = 0 WHERE opportunities_id = ".$op_info['opportunities_id'];
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/opportunities.php";
            header("Location: $redirect");
        }
        break;
    default:
        $err = "Đã có lỗi xảy ra.";
        mysqli_close($connect);
        display_ErrMsg($err);
        exit;
}

?>

<?php

function check_exist_contact($connect, $id){
    $sql = "SELECT id FROM contact WHERE id = '".$id."'";
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