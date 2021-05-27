<?php
error_reporting(0);
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION['tmp_status'])){
    $tmp_status = $_SESSION['tmp_status'];
    unset($_SESSION['tmp_status']);
}else{
    $tmp_status = 0;
}
if(isset($_SESSION['tmp_username'])){
    $tmp_username = $_SESSION['tmp_username'];
    unset($_SESSION['tmp_username']);
}else{
    $tmp_username = "";
}
if(isset($_SESSION['tmp_password'])){
    $tmp_password = $_SESSION['tmp_password'];
    unset($_SESSION['tmp_password']);
}else{
    $tmp_password = "";
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Hệ thống quản lý khách hàng</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="Stylesheet" href="css/style.css" />
</head>
<body style="margin: 0px; padding: 0px">
<div class="login-form">
    <div class="container">
        <div class="left">
            <div class="name">ĐĂNG NHẬP</div>
            <div class="description">Vui lòng nhập tài khoản và mật khẩu để đăng nhập vào hệ thống quản lý khách hàng.</div>
        </div>
        <div class="right">
            <form class="form" method="POST" action="controller/login.php">
                <label for="username">Tài khoản</label>
                <input type="text" id="username" name = "username" value = "<?=$tmp_username ?>" required>
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name = "password" value = "<?=$tmp_password ?>" required>
                <button type="submit" id="submit">Đăng nhập</button>
                <?php if($tmp_status!=0) echo "<p style='color: red'>Tài khoản hoặc mật khẩu sai</p>" ?>
            </form>
        </div>
    </div>
</div>
</body>

