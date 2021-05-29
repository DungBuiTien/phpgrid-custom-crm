<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['mode']);
$contact_info = array();
$contact_info['Contact_name'] = addslashes($_POST['Contact_name']);
$contact_info['Phone'] = addslashes($_POST['Phone']);
$contact_info['Address'] = addslashes($_POST['Address']);
$contact_info['Company'] = addslashes($_POST['Company']);
$contact_info['email'] = addslashes($_POST['email']);

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

switch($mode){
    case "add":
        if(check_exist_contact($connect, $contact_info['Phone'])){
            $err = "Liên hệ đã tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "INSERT INTO contact (id, Contact_name, Date_of_Initial_Contact, Company, Address, Phone, Email) values ".
            "(NULL, '".$contact_info['Contact_name']."', '".date("Y/m/d")."', '".
            $contact_info['Company']."', '".$contact_info['Address']."', '".$contact_info['Phone']."', '".$contact_info['email']."')"; 
            mysqli_query($connect, $sql);
            $redirect = "../sales/contact_list.php";
            header("Location: $redirect");
        }
        break;
    default
        $err = "Đã có lỗi xảy ra.";
        display_ErrMsg($err);
        exit;
}

?>

<?php

function check_exist_contact($connect, $phone){
    $sql = "SELECT phone FROM contact WHERE phone = '".$phone."'";
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