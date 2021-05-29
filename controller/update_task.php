<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');
$user_id = $_SESSION['userid'];

$mode = addslashes($_POST['mode']);
$task_info = array();
$task_info['task_id'] = addslashes($_POST['task_id']);
$task_info['date'] = addslashes($_POST['date']);
$task_info['contact'] = addslashes($_POST['contact']);
$task_info['sale_id'] = addslashes($_POST['sale_id']);
$task_info['type_id'] = addslashes($_POST['type_id']);
$task_info['work_id'] = addslashes($_POST['work_id']);
$task_info['task_desc'] = addslashes($_POST['task_desc']);
$task_info['task_update'] = addslashes($_POST['task_update']);
$task_info['task_due_date'] = addslashes($_POST['task_due_date']);


//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

$user_role=get_Role($connect,$user_id);

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
            if($user_role==2){
                $redirect = "../managers/task_manager.php";
            } else {
                $redirect = "../sales/tasks.php";
            }
            
            header("Location: $redirect");
        }
        break;
    case "add_to_sale":
        if(!check_exist_contact($connect, $task_info['contact'])){
            mysqli_close($connect);
            $err = "Khách hàng không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else if(!check_exist_sale($connect, $user_id, $task_info['sale_id'])) {
            mysqli_close($connect);
            $err = "Nhân viên không tồn tại hoặc không do bạn quản lý.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "INSERT INTO notes (id, date, description, todo_type_id, todo_work_id, todo_due_date, contact_id, task_status,task_update, sales_rep) ".
            " values (NULL, '".$task_info['date']."', '".$task_info['task_desc']."', '".$task_info['type_id']."', '".$task_info['work_id']."', '".$task_info['task_due_date']."', '".$task_info['contact']."', 1".
            ", '".$task_info['task_update']."', ".$task_info['sale_id'].")";
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            if($user_role==2){
                $redirect = "../managers/task_manager.php";
            } else {
                $redirect = "../sales/tasks.php";
            }
            
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
            if($user_role==2){
                $redirect = "../managers/task_manager.php";
            } else {
                $redirect = "../sales/tasks.php";
            }
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
            if($user_role==2){
                $redirect = "../managers/task_manager.php";
            } else {
                $redirect = "../sales/tasks.php";
            }
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

function get_Role($connect, $user_id){
    $sql = "SELECT user_roles FROM users WHERE id = '".$user_id."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        $err = "Cập nhật thất bại.";
        display_ErrMsg($err);
        exit;
    } else {
        $role = mysqli_fetch_array($result);
    }
    return $role[0];
}

function check_exist_contact($connect, $id){
    $sql = "SELECT id FROM contact WHERE id = '".$id."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}

function check_exist_sale($connect, $manager_id, $sale_id){
    $sql = "SELECT id FROM users u INNER JOIN distributor_user du on u.id = du.user_id WHERE id = '".$sale_id."' ".
    "AND du.distributor_id = (SELECT distributor_id FROM distributor_user WHERE user_id = ".$manager_id.")";
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