<?php
namespace plugins\server;
use controllers\controllers as CONT;

class server{
	function index($obj)
	{
//		$title="注册::看客网，就是我的";
//		require_once parse_tmp("/login/login.html");//引入模版
	}
	
	function lists($obj)
	{
		$obj->_bug=0;
		
		$cont=new CONT("server");
		$run=$cont->obj->lists();
		
		/*
		$accuracy=array();
		foreach ($run as $key => $row) 
		{
            $accuracy[$key] = $row["id"];
		}
		//SORT_ASC SORT_DESC
		if(count($accuracy)>1)
		{
			array_multisort($accuracy,SORT_DESC,$run);
		}
		*/
		echo json_encode($run);
	}
	
	function up_server_id($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("server");
		$run=$cont->obj->up_server_id($get);
	}
	
	function server_info($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("server");
		$run=$cont->obj->server_info();
	}
	
	function server_boss($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("server");
		$data=$cont->obj->server_boss();
		require_once parse_tmp("/files/boss.html");//引入模版
		
	}

}
?>