<?php
//用户安全退出设置
header("location:/?module=login");

	include "comm.php";

	$username=$_SESSION["xm"];
	$sid=session_id();

	//清空SESSION值
	$_SESSION=array();

	//删除客户端的在COOKIE中的Sessionid
	if(isset($_COOKIE[session_name()])){
		setCookie(session_name(), '', time()-3600, '/');
	}
	//彻底销毁session
	session_destroy();

echo '<script>window.close();</script>'; 
	 ?> 