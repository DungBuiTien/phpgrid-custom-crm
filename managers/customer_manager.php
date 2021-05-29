<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');
$_GET['currentPage'] = 'customer';
include_once('../inc/manager_menu.php');
$user_id = $_SESSION['userid'];

echo "<h3>Quản lý Khách hàng</h3>";

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");
$connect->set_charset("utf8");

$sql = "SELECT
    co.id,
    co.Address,
    co.Company,
    co.Contact_name,
    co.Date_of_Initial_Contact,
    co.Email,
    co.Phone
    FROM
    contact co
    INNER JOIN customers ON customers.contact_id = co.id
    INNER JOIN distributor_user du ON du.user_id = customers.sale_id
    WHERE du.distributor_id = (
        SELECT distributor_id
        FROM distributor_user
        WHERE distributor_user.user_id = ".$user_id."
)";
$result = mysqli_query($connect, $sql);
$stt_num = mysqli_num_rows($result);
for ($i=0; $i<$stt_num; $i++){
    $stt_list[$i] = mysqli_fetch_array($result);
}
mysqli_free_result($result);

mysqli_close($connect);
?>
<table class="info_table w-50">
    <thead>
        <tr>
            <td>Tên Khách hàng</td>
        </tr>
    </thead>
    <tbody>
        <?php
            for ($i=0; $i<$stt_num; $i++){
                echo "<tr>";
                echo "<td>".$stt_list[$i]["Contact_name"]."
                    <button onclick='detailCustomer(event);'>+</a>
                    </td>";
                echo "</tr>";
                echo "<tr style='display: none'>";
                echo "<td>";
        ?>
            <table>
                <tr>
                    <td>Tên: </td>
                    <td><?=$stt_list[$i]["Contact_name"] ?></td>
                    <td>Ngày sinh</td>
                    <td><?=$stt_list[$i]["Date_of_Initial_Contact"] ?></td>
                </tr>
                <tr>
                    <td>Công ty</td>
                    <td><?=$stt_list[$i]["Company"] ?></td>
                    <td>Địa chỉ</td>
                    <td><?=$stt_list[$i]["Address"] ?></td>
                </tr>
                <tr>
                    <td>Số điện thoại</td>
                    <td><?=$stt_list[$i]["Phone"] ?></td>
                    <td>Email</td>
                    <td><?=$stt_list[$i]["Email"] ?></td>
                </tr>
                
            </table>
        <?php           
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
<script>
    function detailCustomer(e) {
        var display = e.target.parentElement.parentElement.nextSibling.style.display;
        if(display == "block") {
            e.target.innerText = "+";
            e.target.parentElement.parentElement.nextSibling.style.display = "none";
        } else {
            e.target.innerText = "-";
            e.target.parentElement.parentElement.nextSibling.style.display = "block";
        }
    }
</script>
<?php
include_once('../inc/footer.php');
?>
