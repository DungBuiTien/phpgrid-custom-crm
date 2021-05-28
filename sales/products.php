<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'products';
include_once('../inc/sales_menu.php');
$user_id = $_SESSION['userid'];

$discount = (100 - getDiscount($user_id))/100;


$sql =  "SELECT product_id, name, description, category, type, price*".$discount." as price, quantity, update_date ".
        "FROM products";
$dg = new C_DataGrid($sql, "product_id");
$dg->set_caption('Danh sách sản phẩm của hãng');
$dg->set_col_currency('price');

$dg->set_col_title("product_id", "Mã SP")->set_col_width("product_id", 20)->set_col_align("product_id", 'center');
$dg->set_col_title("name", "Tên sản phẩm")->set_col_width("name", 100);
$dg->set_col_title("description", "Miêu tả sản phẩm");
$dg->set_col_title("category", "Phân loại sản phẩm")->set_col_width("category", 50);
$dg->set_col_title("type", "Loại hình sản phẩm")->set_col_width("type", 100);
$dg->set_col_title("price", "Giá")->set_col_width("price", 30);
$dg->set_col_title("quantity", "Trữ lượng còn lại")->set_col_width("quantity", 40)->set_col_align("quantity", 'center');
$dg->set_col_title("update_date", "Ngày cập nhật")->set_col_width("update_date", 60)->set_col_align("update_date", 'center');
$dg->enable_search(true);
$dg->display();


include_once('../inc/footer.php');
?>

<?php
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
