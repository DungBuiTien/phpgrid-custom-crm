<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['mode']);
$task_info = array();
$task_info['task_id'] = addslashes($_POST['task_id']);
$task_info['date'] = addslashes($_POST['date']);
$task_info['contact'] = addslashes($_POST['contact']);
$task_info['type_id'] = addslashes($_POST['type_id']);
$task_info['work_id'] = addslashes($_POST['work_id']);
$task_info['task_desc'] = addslashes($_POST['task_desc']);
$task_info['task_update'] = addslashes($_POST['task_update']);
$task_info['task_due_date'] = addslashes($_POST['task_due_date']);
$user_id = $_SESSION['userid'];

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

switch($mode){
    case "add":
        if(!check_exist_contact($connect, $task_info['contact'])){
            mysqli_close($connect);
            $err = "Khách hàng không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "INSERT INTO notes (id, date, description, todo_type_id, todo_work_id, todo_due_date, contact_id, task_status,task_update, sales_rep) ".
            " values (NULL, '".$task_info['date']."', '".$task_info['task_desc']."', '".$task_info['type_id']."', '".$task_info['work_id']."', '".$task_info['task_due_date']."', '".$task_info['contact']."', 1".
            ", '".$task_info['task_update']."', ".$user_id.")";
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/tasks.php";
            header("Location: $redirect");
        }
        break;
    case "update":
        if(!check_exist_contact($connect, $task_info['contact']) || $task_info['task_id']==""){
            mysqli_close($connect);
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE notes SET ".
            "todo_type_id = '".$task_info['type_id']."', ".
            "todo_work_id = '".$task_info['work_id']."', ".
            "description = '".$task_info['task_desc']."', ".
            "task_update = '".$task_info['task_update']."', ".
            "todo_due_date = '".$task_info['task_due_date']."' ".
            "WHERE id = ".$task_info['task_id'];
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/tasks.php";
            header("Location: $redirect");
        }
        break;
    case "complete":
        if(!check_exist_contact($connect, $task_info['contact']) || $task_info['task_id']==""){
            mysqli_close($connect);
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE notes SET task_status = 2 WHERE id = ".$task_info['task_id'];
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/tasks.php";
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