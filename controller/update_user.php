<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['mode']);
$user_info = array();
$user_info['username'] = addslashes($_POST['username']);
$user_info['password'] = addslashes($_POST['password']);
$user_info['role'] = addslashes($_POST['role']);
$user_info['name'] = addslashes($_POST['name']);
$user_info['email'] = addslashes($_POST['email']);
$user_info['distributor_id'] = addslashes($_POST['distributor_id']);

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

if($user_info['distributor_id']=='' || !check_exist_distributor($connect, $user_info['distributor_id']) || $user_info['role'] > 2){
    $err = "Thêm người dùng mới thất bại.";
    display_ErrMsg($err);
    exit;
}

switch($mode){
    case "add":
        if(check_exist_user($connect, $user_info['username'])){
            mysqli_close($connect);
            $err = "Username đã tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            $user_info['password'] = password_hash($user_info['password'], PASSWORD_DEFAULT);
            mysqli_begin_transaction($connect);

            $sql1 = "INSERT INTO users (id, username, password, user_roles, name, email, user_status) values (NULL, '".$user_info['username']."', '".
            $user_info['password']."', '".$user_info['role']."', '".$user_info['name']."', '".$user_info['email']."', 1)";
            
            try {
                mysqli_query($connect, $sql1);
                $sql2 = "SELECT LAST_INSERT_ID()";
                $result = mysqli_query($connect, $sql2);
                $insert_id = mysqli_fetch_array($result);
                $sql3 = "INSERT INTO distributor_user (distributor_id, user_id) values (".$user_info['distributor_id'].", ".$insert_id[0].")";
                $result = mysqli_query($connect, $sql3);
                mysqli_commit($connect);
            } catch (Throwable $err){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Thêm người dùng mới thất bại.";
                display_ErrMsg($err);
                exit;
            }
            $redirect = "../vendors/viewdetails.php?mode=view&distributor_id=".$user_info['distributor_id'];
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

function check_exist_user($connect, $username){
    $sql = "SELECT username FROM users WHERE username = '".$username."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}

function check_exist_distributor($connect, $distributor_id){
    $sql = "SELECT distributor_id FROM distributors WHERE distributor_id = ".$distributor_id;
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