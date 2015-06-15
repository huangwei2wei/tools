<?php
namespace plugins\login;
use controllers\controllers as CONT;
use kernel\base\base_class\cookie as COOKIE;

class login{
	function index($obj)
	{
	
		require_once parse_tmp("/login/login.html");//引入模版
	}
	
	function loginact($obj)
	{
		$post=$obj->post;
		
		if(!$post["operators"]){message("运营商尚未填写",-1,2000);}
		if(!$post["login_user"]){message("用户名尚未填写",-1,2000);}
		
		if(!$post["password"]){message("密码不能为空！",-1,2000);}
		
		$cont=new CONT("login");
		$run=$cont->obj->login($post);

		//exit;
		//1[用户已登录]|2[验证码错误]|100[登录成功]|4[密码错误]
		switch($run)
		{
			case 100:
				$message="登录成功";
				$url="/";
				
			break;
			
			case 1:
				$message="【您好已经登录】您可以在本后台随意流动！";
				$url="/";
				
			break;
			
			case 2:
				$message="【验证码错误】请从新校对验证码！";
				$url="/?module=login";
			break;
			
			case 4:
				$message="【错误】用户名或密码错误！";
				$url="/?module=login";
			break;
		}
		
		message($message,$url,2000);
	}
	
	function purview($obj)
	{
		$get=$obj->get;
		$cont=new CONT("login");
		$operators=$cont->obj->operators($get["view_id"]);
		$purview=(array)json_decode($operators["purview"]);
		require_once parse_tmp("/login/purview.html");//引入模版
	}
	
	function addpurview($obj)
	{
		$get=$obj->get;
		$post=$obj->post;
		
		$cont=new CONT("login");
		if(isset($post["user"])){
			$addpurview=$cont->obj->addpurview($get["view_id"],$post);
			header("Location: /?module=login&methods=purview&view_id=".$get["view_id"]); 
		}
		require_once parse_tmp("/login/addpurview.html");//引入模版
	}
	
	function delpurview($obj)
	{
		$get=$obj->get;
		$post=$obj->post;
		
		$cont=new CONT("login");
		if(isset($get["purview"])){
			$addpurview=$cont->obj->delpurview($get["view_id"],$get["purview"]);
			header("Location: /?module=login&methods=purview&view_id=".$get["view_id"]); 
		}

	}
}
?>