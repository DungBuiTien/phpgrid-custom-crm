<?php
if(!isset($_SESSION)){
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
include_once("../phpGrid_Lite/conf.php");

//Lấy dữ liệu nhập vào
$username = addslashes($_POST['username']);
$password = addslashes($_POST['password']);

//Mở kết nối
$connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

//Kiểm tra tên đăng nhập có tồn tại không
$query = sprintf("SELECT username, password, user_roles FROM users WHERE username='%s'", mysqli_real_escape_string($connect, $username));
$result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) == 0) {
        $redirect = "../index.php";
        $_SESSION['tmp_status'] = 1;
        $_SESSION['tmp_username'] = $username;
        $_SESSION['tmp_password'] = $password;
        header("Location: $redirect");
    exit;
}


$row = mysqli_fetch_array($result);

if (password_verify($password, $row['password'])) {
    $_SESSION['username'] = $username;
    switch ($row['user_roles']) {
        case 1:
            $redirect = "../sales/tasks.php";
            header("Location: $redirect");
            break;
        case 2:
            $redirect = "../managers/pipeline.php";
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
