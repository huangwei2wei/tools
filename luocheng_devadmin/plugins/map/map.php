<?php
namespace plugins\map;
use controllers\controllers as CONT;

class map{
	function index($obj)
	{
		$get=$obj->get;
		if(in_array($get["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		$_SESSION["mysqldb"]=$get["db"];
		echo "ok";
	}
	
	//修改商城数据
	function get_copy_map($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		
		$cont=new CONT("map");
		if(in_array($get["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		$_SESSION["mysqldb"]=$get["db"];
		$run=$cont->obj->get_copy_map($get["map_id"],$get["db"]);
		if($run)
		{
			echo json_encode($run);
		}else{
			echo "no";
		}
	}
	
	function get_info_map($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
	
		$cont=new CONT("map");
		$_SESSION["mysqldb"]=$get["db"];
		if(in_array($get["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		
		$run=$cont->obj->get_info_map($get["map_id"],$get["db"]);
		if($run)
		{
			echo json_encode($run);
		}else{
			echo "no";
		}
	}
	
	function copy_list_name($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("map");
		$_SESSION["mysqldb"]=$get["db"];
		if(in_array($get["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		$run=$cont->obj->copy_list_name($get["db"]);
		if($run)
		{
			echo json_encode($run);
		}else{
			echo "no";
		}
	}
	
	function set_copy_data($obj)
	{
		$obj->_bug=0;
		$post=$obj->get;
		
		$cont=new CONT("map");
		$_SESSION["mysqldb"]=$post["db"];
		if(in_array($post["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		$run=$cont->obj->set_copy_data($post["data"]);
		if($run)
		{
			echo "ok";
		}else{
			echo "no";
		}
	}
	
	function set_info_data($obj)
	{
		$obj->_bug=0;
		$post=$obj->get;
	
		$cont=new CONT("map");
		$_SESSION["mysqldb"]=$post["db"];
		if(in_array($post["db"],array("game","game2","game3"))==false){
			echo "no";return false;
		}
		$run=$cont->obj->set_info_data($post["data"]);
		if($run)
		{
			echo "ok";
		}else{
			echo "no";
		}
	}
	
	function edit($obj)
	{
		$cont=new CONT("map");
		//$mall_=$cont->obj->edit($type,$page);
		require_once parse_tmp("/map/edit.html");//引入模版	
	}
	
	function edit_get_map_1($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
	
		$cont=new CONT("map");
		$run=$cont->obj->edit_get_map_1($get["map_id"]);
		print_r($run);
	}
	
	function edit_upxy($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
	
		$cont=new CONT("map");
		$run=$cont->obj->edit_upxy($get["map_id"],$get["xy"]);
	}
	
}
?>