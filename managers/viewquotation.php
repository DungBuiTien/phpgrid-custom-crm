<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'viewquotation';
$user_id = $_SESSION['userid'];
$customer_id = addslashes($_GET['customer_id']);
$quotation_id = addslashes($_GET['quotation_id']);

if(isset($_POST['mode'])){
    $mode = addslashes($_POST['mode']);
    if ($mode!="update"){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
} else {
    $mode = addslashes($_GET['mode']);
    if ($mode!="add" && $mode!="view"){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
}

// Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
    
if ($mode=="add"){
    $page_name = "TẠO BÁO GIÁ MỚI";
    if(!check_exist_customer($connect, $customer_id, $user_id)){
        mysqli_close($connect);
        $err = "Khách hàng không tồn tại.";
        display_ErrMsg($err);
        exit;
    } else if(check_exist_quotation($connect, $customer_id)){
        mysqli_close($connect);
        $err = "Báo giá cho khách hàng tương ứng đã tồn tại.";
        display_ErrMsg($err);
        exit;
    }
}

if($mode=="update" || $mode=="view"){
    $sql = "SELECT customers_id FROM customers WHERE quotation_id = '".$quotation_id."'";
    $result = mysqli_query($connect, $sql);
    $tmp = mysqli_fetch_array($result);
    $customer_id =  $tmp[0];
}

    $sql =  "SELECT cu.customers_id, c.contact_name, c.address, d.distributor_name, u.name as sale_name ".
    "FROM customers cu inner join contact c on cu.contact_id = c.id ".
    "INNER JOIN users u on cu.sale_id = u.id ".
    "INNER JOIN distributor_user du on cu.sale_id = du.user_id ".
    "INNER JOIN distributors d on d.distributor_id = du.distributor_id ".
    "WHERE cu.customers_id = ?";
    
    $presmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($presmt, "i", $customer_id);
    if(!mysqli_stmt_execute($presmt)){
        $err = "Đã có lỗi xảy ra trong quá trình truy vấn.";
        display_ErrMsg($err);
        exit;
    }
    $result = array();
    mysqli_stmt_bind_result($presmt, $result['customers_id'], $result['contact_name'], $result['address'], $result['distributor_name'], $result['sale_name']);
    while (mysqli_stmt_fetch($presmt)) {
        $quotation_info = $result;
    }
    mysqli_stmt_close($presmt);


if($mode=="update" || $mode=="view"){
    $page_name = "THÔNG TIN BÁO GIÁ";
    $sql = "SELECT q.product_id, q.quantity, q.original_price, q.sale_price, p.name as product_name ".
    "FROM quotation_info q INNER JOIN products p ON q.product_id = p.product_id WHERE quotation_id = '".$quotation_id."'";
    $result = mysqli_query($connect, $sql);
    $qo_num = mysqli_num_rows($result);

    for ($i=0; $i<$qo_num; $i++){
        $qo_list[$i] = mysqli_fetch_array($result);
    }
    mysqli_free_result($result);

    $sql = "SELECT original_price, sale_price from quotations WHERE quotation_id = '".$quotation_id."'";
    $result = mysqli_query($connect, $sql);
    $sum = mysqli_fetch_array($result);
    mysqli_free_result($result);
}
?>

<div id="wrapper" style="width: 80%; margin: auto;">
    <h2 style="text-align: center"><?=$page_name?></h2>
    <form class="info_form" id='info_form' method="POST" action="../controller/update_quotation.php">
    <input type="hidden" name='mode' id='mode' value="<?php if($mode=="add"){echo "add";}else{echo "update";}?>" >
    <input type="hidden" name='customer_id' id='customer_id' value="<?php if($mode=="add"){echo $customer_id;}?>" >
    <input type="hidden" name='quotation_id' id='quotation_id' value="<?php if($mode=="view"){echo $quotation_id;}?>" >
    <div class="col-1">
        <label>
        <h1 style="text-align: center">BÁO GIÁ</h1>
        </label>
    </div>
    <div class="col-4">
    <label>
        Mã đơn hàng
        <p style="text-align: center" class="quotation_text"><?=$quotation_info['customers_id']?></p>
    </label>
    </div>
    <div class="col-4">
        <label>
        Ngày tạo đơn hàng
        <p style="text-align: center" class="quotation_text"><?=(new DateTime($quotation_info["update_date"]))->format('Y/m/d')?></p>
        </label>
    </div>
    <div class="col-2">
        <label>
        Khách hàng
        <p class="quotation_text"><?=$quotation_info['contact_name']." (Địa chỉ: ".$quotation_info['address'].")"?></p>

        </label>
    </div>
    <div class="col-3">
        <label>
        Tên nhà phân phối
        <p class="quotation_text"><?=$quotation_info['distributor_name']?></p>
        </label>
    </div>
    <div class="col-3">
        <label>
        Tên nhân viên bán hàng
        <p class="quotation_text"><?=$quotation_info['sale_name']?></p>
        </label>
    </div>
    <div class="col-3">
        <label>
        Tình trạng hiện tại
        <p class="quotation_text">Báo giá mới được tạo</p>
        </label>
    </div>
    <div class="col-1">
        <label style="text-align: center">
        Danh mục sản phẩm
        </label>
        <table class="info_table" style="font-size: 14px">
            <tr>
                <th style='text-align: center; width: 10%'>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th style='text-align: center; width: 10%'>Số lượng</th>
                <th style='text-align: center; width: 10%'>Giá nhập</th>
                <th style='text-align: center; width: 10%'>Giá bán</th>
            </tr>
<?php
            for ($i=0; $i<10; $i++){
                if ($i==0 || $qo_list[$i]['product_id']!=""){
                    $required = "required";
                } else {
                    $required = "";
                }
                echo "<tr>";
                echo "<td><input value='".$qo_list[$i]['product_id']."' style='text-align: center' type='number' id='product_".$i."_id' name='product_".$i."_id' onblur='getPrice(event)' ".$required." ></td>";
                echo "<td><input value='".$qo_list[$i]['product_name']."' id='product_".$i."_name' name='product_".$i."_name' readonly></td>";
                echo "<td><input value='".$qo_list[$i]['quantity']."' style='text-align: center' type='number' id='product_".$i."_quantity' onblur='changeQuantity(event)' name='product_".$i."_quantity'></td>";
                echo "<td><input value='".$qo_list[$i]['original_price']."' style='text-align: center' id='product_".$i."_original_price' onblur='updateSum()' name='product_".$i."_original_price' readonly></td>";
                echo "<td><input value='".$qo_list[$i]['sale_price']."' type='number' id='product_".$i."_sale_price' onblur='updateSum()' name='product_".$i."_sale_price' ".$required." ></td>";
                echo "</tr>";
            }
?>
            <tr>
                <td colspan='3'><h2>Tổng</h2></td>
                <td><input value="<?=$sum['original_price']?>" style='text-align: center' id='sum_ori' name='sum_ori'readonly></td>
                <td><input value="<?=$sum['sale_price']?>" style='text-align: center' id='sum_sale' name='sum_sale' readonly></td>
            </tr>
        </table>
    </div>


    <div class="col-submit">
<?php
    switch($mode){
        case "view":
            echo "<button class='submitbtn'>Cập nhật</button>";
            echo "<button type='button' class='submitbtn' id='cancel_button' onclick='location.href=\"customers.php\"'>Huỷ</button>";
            break;
        case "add":
            echo "<button class='submitbtn'>Thêm mới</button>";
            echo "<button type='button' class='submitbtn' id='cancel_button' onclick='location.href=\"customers.php\"'>Huỷ</button>";
            break;
    }
?>
    </div>
    </form>
</div>



<?php
function display_ErrMsg($err){
    echo "<script type='text/javascript'> window.onload=display_ErrMsg('".$err."'); </script>";
} 
function check_exist_quotation($connect, $customer_id){
    $sql = "SELECT quotation_id FROM customers WHERE customers_id = '".$customer_id."'";
    $result = mysqli_query($connect, $sql);
    $quotation_id = mysqli_fetch_array($result);
    if ($quotation_id['quotation_id'] == NULL) {
        return false;
    } else {
        return true;
    }
}
function check_exist_customer($connect, $customer_id, $sale_id){
    $sql = "SELECT customers_id FROM customers WHERE customers_id = '".$customer_id."' AND sale_id = '".$sale_id."'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return true;
    }
}
?>
<script>
    function getPrice(e){
        var product_id = e.target.value;
        var product_pos = e.target.getAttribute('id');
        var product_item = product_pos.substr(0,product_pos.length-3);

        var product_name = product_item+"_name";
        var product_quantity = product_item+"_quantity";
        var product_price = product_item+"_original_price";
        var product_sale_price = product_item+"_sale_price";

        if (document.getElementById(product_pos).value==""){
            document.getElementById(product_name).value="";
            document.getElementById(product_price).value="";
            document.getElementById(product_quantity).value="";
            document.getElementById(product_sale_price).value="";
            document.getElementById(product_pos).removeAttribute("required");
            document.getElementById(product_sale_price).removeAttribute("required");
            updateSum();
        } else {
            $.ajax( 
            {
                url : '../controller/getprice.php?product_id='+product_id,
                type : 'get',
                success : function( respond ) 
                {   
                    data = JSON.parse(respond);
                    if(typeof data == 'string'){
                        alert("Mã sản phẩm không tồn tại");
                        document.getElementById(product_pos).value="";
                        document.getElementById(product_name).value="";
                        document.getElementById(product_price).value="";
                        document.getElementById(product_quantity).value="";
                        document.getElementById(product_sale_price).value="";
                        document.getElementById(product_pos).removeAttribute("required");
                        document.getElementById(product_sale_price).removeAttribute("required");
                    } else {
                        document.getElementById(product_name).value=data.name;
                        document.getElementById(product_price).value=data.price;
                        document.getElementById(product_quantity).value=1;
                        document.getElementById(product_sale_price).setAttribute("required","required");
                    }
                    updateSum();
                }
            });
        }
        
    }

    function updateSum(){
        var sum_ori = 0;
        var sum_sale = 0;
        for(var i=0;i<10;i++){
            var quantity = document.getElementById("product_"+i+"_quantity").value;
            var price_ori = document.getElementById("product_"+i+"_original_price").value;
            var price_sale = document.getElementById("product_"+i+"_sale_price").value;
            if (quantity=='') quantity=0;
            if (price_ori=='') price_ori=0;
            if (price_sale=='') price_sale=0;
            sum_ori+=quantity*price_ori;
            sum_sale+=quantity*price_sale;
        }
        document.getElementById("sum_ori").value=sum_ori;
        document.getElementById("sum_sale").value=sum_sale;
    }

    function changeQuantity(e){
        var product_id = e.target.value;
        var product_quantity = e.target.getAttribute('id');
        if (document.getElementById(product_quantity).value<1){
            alert("Số lượng phải là số dương");
        } else {
            updateSum();
        }
    }

</script>

<?php
include_once('../inc/footer.php');
?>