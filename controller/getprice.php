<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
$user_id = $_SESSION['userid'];
$product_id = addslashes($_GET['product_id']);

$discount = (100 - getDiscount($user_id))/100;

$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
$sql = "SELECT product_id, price*".$discount." as price, name FROM products WHERE product_id = ".$product_id;
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result)==0){
    $return = "error";
} else {
    $return = mysqli_fetch_array($result);
}
echo json_encode($return);

function getDiscount($user_id){
    $connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
    $sql =  "SELECT distributor_discount FROM distributors d INNER JOIN distributor_user du ON d.distributor_id = du.distributor_id WHERE du.user_id = ".$user_id;
    $result = mysqli_query($connect, $sql);
    $user = mysqli_fetch_array($result);
    mysqli_free_result($result);
    mysqli_close($connect);
    return $user['distributor_discount'];
}
?>