<?php 
namespace plugins\server;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class iserver extends module
{
	function lists()
	{
		$operators_id=intval($_SESSION["userid"]);
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		
		$tmplist=$tmp_mongo->find("server_list",array ('operators_id' =>intval($operators_id)))->sort(array("server_id"=>-1));
		$server_list=array();
		foreach($tmplist as $value)
		{
			unset($value["_id"]);
			$server_list[]=$value;	
		}

		//$userid=intval($_SESSION["userid"]);
		//$server_list=dbarr("SELECT * FROM `server_list` WHERE `operators_id`=$userid order by `server_id` asc",0,"operators");
		return $server_list;
	}
	
	function up_server_id($data)
	{
		
		$server_id=intval($data["server_id"]);
		$userid=intval($_SESSION["userid"]);
		$server_list=dbarr("SELECT * FROM `server_list` WHERE `operators_id`=$userid and  `server_id`=$server_id",0,"operators");
		if(count($server_list)>0)
		{
			$_SESSION["server_id"]=$server_id;
			echo json_encode(array("e"=>"ok"));
		}else{
			echo json_encode(array("e"=>"no"));
		}
	}
	
	function server_info()
	{
		
		$server_id=intval($_SESSION["server_id"]);
		$userid=intval($_SESSION["userid"]);
		//$server_list=dbarr("SELECT operators_id,server_name,server_id FROM `server_list` WHERE `operators_id`=$userid and  `server_id`=$server_id",0,"operators");
		$operators=dbarr("SELECT name,company FROM `operators` WHERE `id`=$userid",0,"operators");
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$userid);
		$server_list=$tmp_mongo->findone("server_list",array("operators_id"=>$userid,"server_id"=>$server_id));
		
		if(count($operators))
		{
			echo json_encode(array(
																"e"=>"ok",
																"server_name"=>$server_list["server_name"],
																"operators_id"=>$server_list["operators_id"],
																"server_id"=>$server_list["server_id"],
															"name"=>$operators[0]["name"],
															"company"=>$operators[0]["company"],
															)
														);
		}else{
			echo json_encode(array("e"=>"no"));
		}
	}
	
	function server_boss()
	{
		$data=dbarr("SELECT * FROM `operators` WHERE 1",0,"operators");
		return $data;

	}
	function gamefu()
	{
		$data=dbarr("SELECT * FROM `server_list` WHERE 1",0,"operators");
		return $data;
		
	}
}
?>