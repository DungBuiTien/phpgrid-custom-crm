
<?php
error_reporting(0);
if(!isset($_SESSION)){
    session_start();
}
$url_split = preg_split('[/]', $_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY);
$sub_path = $url_split[0];
$base_path = $_SERVER['SERVER_NAME']."/".$sub_path;
if(!isset($_SESSION)){
    $redirect = $base_path."/index.php";
    header("Location: $redirect");
} else {
    if(!isset($_SESSION['username'])){
        $redirect = $base_path."/index.php";
        header("Location: $redirect");
    } else {
        $username = $_SESSION['username'];
        //Mo ket noi den database
        $connect = mysqli_connect( PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME) or die("Không thể kết nối database");

        //Kiem tra role cua user hien tai nhap vao
        $query = sprintf("SELECT user_roles FROM users WHERE username='%s'", mysqli_real_escape_string($connect, $username));
        $result = mysqli_query($connect, $query);
            if (mysqli_num_rows($result) == 0) {
                $redirect = $base_path."/index.php";
                header("Location: $redirect");
            exit;
        }

        // Lay du lieu nguoi dung tuong ung voi username da nhap
        $row = mysqli_fetch_array($result);
        // Dong ket noi
        mysqli_close($connect);

        $page_role = $url_split[1];
        switch($page_role) {
            case "managers":
                if ($row['user_roles'] < 2){
                    display_PerErrMsg();
                    exit;
                }
                break;
            case "vendors":
                if ($row['user_roles'] < 3){
                    display_PerErrMsg();
                    exit;
                }
                break;
            case "sales":
                break;
            case "admin":
                break;
            default:
                display_PerErrMsg();
                exit;
        }
    }
}

function display_PerErrMsg(){
    echo "<script type='text/javascript'> window.onload=display_PerErrMsg(); </script>";
}
?>

