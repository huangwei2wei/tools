<?/**
 * 定义登录
 */
session_start();
setcookie(session_name(), session_id(), time()+3600*24);
// 判断是否登陆
if (!$_SESSION["u_name"]) {
    $_SESSION["u_name"] = false;
    echo '<script>location.href="login.php";</script>';   
}    
?>
