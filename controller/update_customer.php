<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');

$mode = addslashes($_POST['mode']);
$customer_info = array();
$customer_info['customers_id'] = addslashes($_POST['customers_id']);
$customer_info['date'] = addslashes($_POST['date']);
$customer_info['contact_id'] = addslashes($_POST['contact_id']);
$customer_info['status'] = addslashes($_POST['status']);
$customer_info['budget'] = addslashes($_POST['budget']);
if ($customer_info['budget']=="" || $customer_info['budget']==0) $customer_info['budget'] = 'NULL';
$customer_info['note'] = addslashes($_POST['note']);
$customer_info['quotation_id'] = addslashes($_POST['quotation_id']);
if ($customer_info['quotation_id']=="") $customer_info['quotation_id'] = 'NULL';
$user_id = $_SESSION['userid'];

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

switch($mode){
    case "update":
        if(!check_exist_contact($connect, $customer_info['contact_id']) || $customer_info['customers_id']==""){
            $err = "Cập nhật thất bại.";
            display_ErrMsg($err);
            exit;
        } else {
            $sql = "UPDATE customers SET ".
            "note = '".$customer_info['note']."', ".
            "status = '".$customer_info['status']."', ".
            "budget = '".$customer_info['budget']."' ".
            "WHERE customers_id = ".$customer_info['customers_id'];
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            $redirect = "../sales/customers.php";
            header("Location: $redirect");
        }
        break;
    default:
        $err = "Cập nhật thất bại.";
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