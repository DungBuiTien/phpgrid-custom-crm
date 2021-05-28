<?php
//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
 
//Lay ra danh sach các task hien tai
$sql =  "SELECT name from users where id = ".$_SESSION['userid'];
$result = mysqli_query($connect, $sql);
$name = mysqli_fetch_array($result);
mysqli_free_result($result);
mysqli_close($connect);
?>

<section id="subtitle">
    <h2>Chào mừng nhân viên <?=$name[0]?></h2>
    <div>
    Bạn có thể xem các công việc của mình ở đây.
    </div>
    <br />
</section>
<div id="menu">
    <ul>
        <li><a href="tasks.php" <?php if($_GET['currentPage'] == 'tasks') echo 'class="active"'; ?>>Công việc</a></li>
        <li><a href="opportunities.php" <?php if($_GET['currentPage'] == 'opportunities') echo 'class="active"'; ?>>Cơ hội</a></li>
        <li><a href="customers.php" <?php if($_GET['currentPage'] == 'customers') echo 'class="active"'; ?>>Khách hàng hiện tại</a></li>
        <li><a href="quotations.php" <?php if($_GET['currentPage'] == 'quotations') echo 'class="active"'; ?>>Báo giá</a></li>
        <li><a href="contact_list.php" <?php if($_GET['currentPage'] == 'contact_list') echo 'class="active"'; ?>>Danh sách liên hệ</a></li>
        <li><a href="products.php" <?php if($_GET['currentPage'] == 'products') echo 'class="active"'; ?>>Danh sách sản phẩm</a></li>
    </ul>
</div>