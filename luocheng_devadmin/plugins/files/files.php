<?php
namespace plugins\files;
use controllers\controllers as CONT;

class files{
	function index($obj)
	{
//		$title="注册::看客网，就是我的";
//		require_once parse_tmp("/login/login.html");//引入模版
	}
	
	//显示
	function base($obj)
	{
		$obj->_bug=0;
		require_once parse_tmp("/files/base.html");//引入模版
	}
	
	//生成文件
	function generate($obj)
	{
		if(isset($_SESSION["userid"])==false or $_SESSION["userid"]<=0)
		{
			print("<script>alert('您尚未登录');</script>");
			return false;
		}
		$cont=new CONT("files");
		$generate=$cont->obj->generate($obj->get["tab"],1);
		echo $generate;
	}
	
	//生成ZIP
	function zip($obj)
	{
		$cont=new CONT("zip");
		$run=$cont->obj->b_zip();
		echo $run;
	}
		//生成EXCEL
	function csv()
	{

		require_once parse_tmp("/excel/excel.html");//引入模版
	}
		//游戏初始化
//	function initialization($obj)
//	{
//		if(!$obj->post["isok"])
//		{	
//		$obj->_bug=0;
//		$cont=new CONT("macro_data");
//		$prohibit_speech=$cont->obj->initialization($obj->get["all"]);
//		require_once parse_tmp("/files/initialization.html");//引入模版
//	    }
//	
//	}	
	
	
	
	


	function initialization($obj)
	{
		if(!$obj->post["isok"])
		{
//				require_once(GAME_DIR."config/config.php");
				require_once parse_tmp("/files/initialization.html");//引入模版
		}else{
			$userid=$_SESSION["userid"];
			$server_id=$_SESSION["server_id"];
			if(!$userid || !$server_id){echo "登录失效！";exit;}
			
			$cont=new CONT("zip");
			$run=$cont->obj->initialization($obj->mongo,$obj->post,$obj->session);
			echo $run;
		}
	}
	
	function deletecode($obj)
	{
		$cont=new CONT("files");
		$cont->obj->deletecode();
		echo "清楚成功";
	}
}
?>