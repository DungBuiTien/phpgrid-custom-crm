<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");

//Lay du lieu nguoi dung nhap vao
$username = addslashes($_POST['username']);
$password = addslashes($_POST['password']);

//Mo ket noi den database
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

//Kiem tra ten nguoi dung nhap vao
$query = sprintf("SELECT id, username, password, user_roles FROM users WHERE username='%s'", mysqli_real_escape_string($connect, $username));
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result) == 0) {
    $redirect = "../index.php";
    $_SESSION['tmp_status'] = 1;
    $_SESSION['tmp_username'] = $username;
    $_SESSION['tmp_password'] = $password;
    header("Location: $redirect");
exit;
}

// Lay du lieu nguoi dung tuong ung voi username da nhap
$row = mysqli_fetch_array($result);

// Dong ket noi
mysqli_free_result($result);
mysqli_close($connect);

// Kiem tra mat khau
if (password_verify($password, $row['password'])) {
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $row['id'];
    switch ($row['user_roles']) {
        // Chuyen huong trang dua tren role
        case 1:
            $redirect = "../sales/tasks.php";
            header("Location: $redirect");
            break;
        case 2:
            $redirect = "../managers/task_manager.php";
            header("Location: $redirect");
            break;
        case 3:
            $redirect = "../vendors/products.php";
            header("Location: $redirect");
            break;
    }
} else {
    $redirect = "../index.php";
    $_SESSION['tmp_status'] = 1;
    $_SESSION['tmp_username'] = $username;
    $_SESSION['tmp_password'] = $password;
    header("Location: $redirect");
    exit;
}

?>
