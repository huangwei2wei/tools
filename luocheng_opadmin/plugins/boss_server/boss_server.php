<?php
namespace plugins\boss_server;
use controllers\controllers as CONT;
use kernel\base\sql\nosql\mongo as TMP_MONGO;
use kernel\base\base_class\string as STRING;
set_time_limit(0);

class boss_server{
			
	function add_server($obj)
	{
		set_time_limit(0);
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;

		$post["mongo_db"]="s_".trim($post["operators_id"])."_".trim($post["server_id"]);
		$cont->obj->add_server($post);
		
		echo "ok";
	}
	
	function get_operators()
	{
		$tmp=dbarr("SELECT `id`,`name`,`identifier` FROM `operators` WHERE 1",0,"operators");
		$operators=array();
		foreach($tmp as $value)
		{
			if(strlen($value["name"])>0)
				$operators[]=array("id"=>$value["id"],"name"=>$value["name"],"identifier"=>$value["identifier"]);
		}
		print_r(json_encode($operators));
	}
	
	function del_server($obj)
	{
		/*$dbname="s_".trim($post["operators_id"])."_".trim($post["server_id"]);
		$this->phpmongo->drop($dbname);*/
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;

		$cont->obj->delservice(intval(trim($post["operators_id"])),intval(trim($post["server_id"])));
		echo "ok";
	}
	
	
	
}
?>