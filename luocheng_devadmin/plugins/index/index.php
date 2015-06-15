<?php
namespace plugins\index;
use controllers\controllers as CONT;
use kernel\base\base_class\res as RES;
use kernel\base\base_class\cookie as COOKIE;

class index{
	function index($obj)
	{
		// 判断是否登陆
		if (!$_SESSION["operators"])
		{
		    $_SESSION["operators"] = false;
//		    message("进入网页游戏后台……","?module=login");
				require_once parse_tmp("/login/login.html");//引入模版
		}
		
		require_once parse_tmp("/index.html");//引入模版
	}
}
?>