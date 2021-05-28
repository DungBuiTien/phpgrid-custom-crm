<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'contact_list';
include_once('../inc/sales_menu.php');
?>
<div>
    <button id ="add_contact" onclick="document.getElementById('id01').style.display='block'" type="button" style="margin: 5px">Thêm mới</button>
</div>
<?php
$dg = new C_DataGrid("SELECT * FROM contact", "id", "contact");
$dg->set_caption('Danh sách liên hệ');
$dg->set_col_title("id", "Mã KH")->set_col_width("id", 20)->set_col_align("id", 'center');
$dg->set_col_title("Contact_name", "Tên khách hàng")->set_col_width("Contact_name", 100);
$dg->set_col_title("Date_of_Initial_Contact", "Ngày thêm")->set_col_width("Date_of_Initial_Contact", 50)->set_col_align("Date_of_Initial_Contact", 'center');
$dg->set_col_title("Company", "Công ty");
$dg->set_col_title("Address", "Địa chỉ");
$dg->set_col_title("Phone", "Số điện thoại")->set_col_width("Phone", 50)->set_col_align("Phone", 'center');
$dg->set_col_title("Email", "Địa chỉ Email");
$dg->enable_search(true);
$dg->display();
?>

<div id="id01" class="modal">
    
    <form class="modal-content animate info_form" action="../controller/update_contact.php" method="post">
    <input type="hidden" name="mode" value="add">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
    <div class="col-1">
        <label style="text-align: center; font-size: large">Thêm liên hệ mới</label>
    </div> 
    <div class="col-2">
        <label>
        Tên khách hàng
        <input id="Contact_name" name="Contact_name" required>
        </label>
    </div>
    <div class="col-2">
        <label>
        Số điện thoại
        <input id="Phone" name="Phone">
        </label>
    </div>
    <div class="col-1">
        <label>
        Địa chỉ
        <input id="Address" name="Address">
        </label>
    </div>
    <div class="col-1">
        <label>
        Công ty
        <input id="Company" name="Company">
        </label>
    </div>
    <div class="col-1">
        <label>
        Email
        <input id="email" name="email">
        </label>
    </div>
    

    <div class="col-submit">
        <button class='submitbtn'>Thêm mới</button>
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>

<script>
var modal = document.getElementById('id01');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php
include_once('../inc/footer.php');
?>

