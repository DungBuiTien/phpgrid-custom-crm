<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'products';
include_once('../inc/vendors_menu.php');

$sql =  "SELECT product_id, name, description, category, type, price, quantity, update_date ".
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
$dg->enable_edit();
$dg->enable_search(true);
$dg->display();


include_once('../inc/footer.php');
?>
