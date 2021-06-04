<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");
include_once('../inc/head.php');
$user_id = $_SESSION['userid'];

$mode = addslashes($_POST['mode']);
$quotation_info = array();
$quotation_info['customer_id'] = addslashes($_POST['customer_id']);
$quotation_info['quotation_id'] = addslashes($_POST['quotation_id']);
$quotation_info['status'] = addslashes($_POST['status']);

for ($i=0;$i<10;$i++){
    $quotation_info["product_".$i."_id"] = addslashes($_POST["product_".$i."_id"]);
    $quotation_info["product_".$i."_quantity"] = addslashes($_POST["product_".$i."_quantity"]);
    $quotation_info["product_".$i."_original_price"] = addslashes($_POST["product_".$i."_original_price"]);
    $quotation_info["product_".$i."_sale_price"] = addslashes($_POST["product_".$i."_sale_price"]);
}
$quotation_info['sum_ori'] = addslashes($_POST['sum_ori']);
$quotation_info['sum_sale'] = addslashes($_POST['sum_sale']);


//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

$user_role=get_Role($connect,$user_id);

switch($mode){
    case "add":
        if($quotation_info['customer_id']=="" || !check_exist_customer($connect, $quotation_info['customer_id'])){
            mysqli_close($connect);
            $err = "Hoá đơn không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            mysqli_begin_transaction($connect);
            try {
                $sql = 'SELECT customers_id, contact_id, sale_id FROM customers WHERE customers_id = '.$quotation_info['customer_id'];
                $result = mysqli_query($connect, $sql);
                $customer = mysqli_fetch_array($result);
                mysqli_free_result($result);
                
                // Update bang bao gia
                $sql1 = "INSERT INTO quotations values (NULL, ".$customer['contact_id'].", ".$customer['sale_id'].", 1, ".$quotation_info['sum_ori'].", ".$quotation_info['sum_sale'].", CURRENT_TIMESTAMP())";
                mysqli_query($connect, $sql1);
                $sql2 = "SELECT LAST_INSERT_ID()";
                $result = mysqli_query($connect, $sql2);
                $quotation_id = mysqli_fetch_array($result);
                $sql3 = "update customers set quotation_id = ".$quotation_id[0]." WHERE customers_id = ".$quotation_info['customer_id'];
                mysqli_query($connect, $sql3);

                // Update info 
                for ($i=0;$i<10;$i++){
                    if ($quotation_info["product_".$i."_id"]!=""){
                        $tmp_sql = "INSERT INTO quotation_info values (".$quotation_id[0].", ".$quotation_info["product_".$i."_id"]." , ".$quotation_info["product_".$i."_quantity"].", ".$quotation_info["product_".$i."_original_price"].", ".$quotation_info["product_".$i."_sale_price"].")";
                        mysqli_query($connect, $tmp_sql);
                    }

                }
                mysqli_commit($connect);
            } catch (Throwable $err){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Thêm báo giá mới thất bại.";
                display_ErrMsg($err);
                exit;
            }
            $redirect = "../sales/customers.php";
            header("Location: $redirect");
        }
        break;
    case "update":
        if($quotation_info['quotation_id']=="" || !check_exist_quotation($connect, $quotation_info['quotation_id'])){
            mysqli_close($connect);
            $err = "Hoá đơn không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            mysqli_begin_transaction($connect);
            try {
                $sql = 'UPDATE quotations set original_price = '.$quotation_info['sum_ori'].", sale_price = ".$quotation_info['sum_sale'].", update_date = CURRENT_TIMESTAMP() ".
                "WHERE quotation_id = ".$quotation_info['quotation_id'];
                mysqli_query($connect, $sql);
                
                // Update bang bao gia
                $sql1 = "DELETE FROM quotation_info WHERE quotation_id = ".$quotation_info['quotation_id'];
                mysqli_query($connect, $sql1);

                // Update info 
                for ($i=0;$i<10;$i++){
                    if ($quotation_info["product_".$i."_id"]!=""){
                        $tmp_sql = "INSERT INTO quotation_info values (".$quotation_info['quotation_id'].", ".$quotation_info["product_".$i."_id"]." , ".$quotation_info["product_".$i."_quantity"].", ".$quotation_info["product_".$i."_original_price"].", ".$quotation_info["product_".$i."_sale_price"].")";
                        var_dump($tmp_sql);
                        mysqli_query($connect, $tmp_sql);
                    }

                }
                mysqli_commit($connect);
            } catch (Throwable $err){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Cập nhật thất bại.";
                display_ErrMsg($err);
                exit;
            }
            if($user_role==2){
                $redirect = "../managers/quotations.php";
            } else {
                $redirect = "../sales/customers.php";
            }
            header("Location: $redirect");
        }
        break;
    case "update_status":
        if($quotation_info['quotation_id']=="" || !check_exist_quotation($connect, $quotation_info['quotation_id'])){
            mysqli_close($connect);
            $err = "Hoá đơn không tồn tại.";
            display_ErrMsg($err);
            exit;
        } else {
            mysqli_begin_transaction($connect);
            try {
                $sql = 'UPDATE quotations set status = '.$quotation_info['status'].' WHERE quotation_id = '.$quotation_info['quotation_id'];
                var_dump($sql);
                mysqli_query($connect, $sql);
                mysqli_commit($connect);
                mysqli_close($connect);
            } catch (Throwable $err){
                mysqli_rollback($connect);
                mysqli_close($connect);
                $err = "Cập nhật thất bại.";
                display_ErrMsg($err);
                exit;
            }
            if($user_role==2){
                $redirect = "../managers/quotations.php";
            } else {
                $redirect = "../sales/quotations.php";
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

function check_exist_customer($connect, $customer_id){
    $sql = "SELECT customers_id FROM customers WHERE customers_id = '".$customer_id."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}

function check_exist_quotation($connect, $quotation_id){
    $sql = "SELECT quotation_id FROM quotations WHERE quotation_id = '".$quotation_id."'";
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
?>