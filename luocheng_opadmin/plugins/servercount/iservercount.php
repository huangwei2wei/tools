<?php 
namespace plugins\servercount;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class iservercount extends module
{
	function multi_array_sort($arr,$sort_key,$short=SORT_DESC)
	{
		$accuracy=array();
		foreach ($arr as $key => $row) 
		{
            $accuracy[$key] = $row[$sort_key];
		}
		//SORT_ASC SORT_DESC
		if(count($accuracy)>1)
		{
			array_multisort($accuracy, $short,$arr);
		}
		
		return $arr;
	}
	
	function server_list()
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",intval($_SESSION["userid"]));
		$tmp_server_list=$tmp_mongo->find("server_list",array("operators_id"=>intval($_SESSION["userid"])))->sort(array('server_id'=>-1));
		$server_list=array();
		foreach($tmp_server_list as $value)
		{
			$server_list[]=$value;
		}
		//$server_list=dbarr("SELECT * FROM  `server_list` WHERE  `operators_id` =".intval($_SESSION["userid"])." ORDER BY  `server_id` DESC",0,"operators");
		return $server_list;
	}

	function operations($post)
	{
		$server_id=$post["server_id"];
		$time=intval(str_replace("-","",$post["time"]));
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		
		$server_list=dbarr("SELECT * FROM  `server_list` WHERE  `operators_id` =".intval($_SESSION["userid"])." and server_id=".$server_id." ORDER BY  `server_id` DESC",0,"operators");
		$tmp_mongo=new TMP_MONGO;
		$mongo_db="s_".intval($_SESSION["userid"])."_".$server_id;
        $tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],$mongo_db,$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"],  $operators[0]["mongo_auth"]);
		//$tmp_mongo->db->authenticate($operators[0]["mongo_username"],  $operators[0]["mongo_password"]);
		
		$two_login=$tmp_mongo->find("log_two_login",array("month"=>$time));
		$two_login_arr=array();
		foreach($two_login as $value)
		{
			$two_login_arr[]=$value;
		}
		
		$two_login_arr=$this->multi_array_sort($two_login_arr,"time");
		
		return $two_login_arr;
	}
	
	function module_name_list()
	{
		$base_module=dbarr("SELECT * FROM `base_module` WHERE 1");
		$modulename=array();
		foreach($base_module as $value)
		{
			$modulename[$value["id"]]=$value["module_name"];
		}
		return $modulename;
	}
	
	function pay($post)
	{
		//$post["time"]="2013-08";
		$time=intval(str_replace("-","",$post["time"]));
		
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		
		//$server_list=dbarr("SELECT * FROM  `server_list` WHERE  `operators_id` =".intval($_SESSION["userid"])." ORDER BY  `server_id` DESC",0,"operators");
		$tmp_mongo1=new TMP_MONGO;
        $tmp_mongo1->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],trim("all_user"),$operators[0]["mongo_username"],  $operators[0]["mongo_password"],$operators[0]["mongo_line"],$operators[0]["mongo_auth"]);
		$server_list=$tmp_mongo1->find("server_list",array("operators_id"=>intval($_SESSION["userid"])));
		
		$money_list=array();
		foreach($server_list as $value)
		{
			$money_list[$value["server_id"]]=$this->pay_count($operators[0],$value,$time);
		}
		return $money_list;
	}
	
	function pay_count($mongodb,$server_data,$time)
	{
		//,`mongo_auth`
		$tmp_mongo=new TMP_MONGO;
        $tmp_mongo->tmpmongoconn($mongodb["server_mongo_ip"],$mongodb["server_mongo_port"],trim($server_data["mongoname"]),$mongodb["mongo_username"],  $mongodb["mongo_password"],$mongodb["mongo_line"],$mongodb["mongo_auth"]);
		//$tmp_mongo->db->authenticate($mongodb["mongo_username"],  $mongodb["mongo_password"]);
		
		$pay_log=$tmp_mongo->aggregate("log_pay",array("timecount"),array("money"),array("month"=>$time),array("timecount"=>1,"money"=>1,"month"=>1),array("timecount"=>-1),$skip=0,$limit=0,$mtype=0);

		$rundata=array();
		foreach($pay_log as $value)
		{
			$time1=intval(str_replace($time,"",$value["_id"]["timecount"]));
			$rundata[$time1]=$value["money"];
		}
		return $rundata;
	}
	
	
	function spread()
	{
		$put_in=$this->phpmongo->find("put_in");
		return $put_in;
	}
	
	function add_spread($post)
	{
		$this->phpmongo->insert("put_in",array("timecount"=>intval($post["timecount"]),"money"=>intval($post["money"])));
	}
	
	function up_spread($post)
	{
		$this->phpmongo->update("put_in",array("money"=>intval($post["money"])),array("timecount"=>intval($post["_timecount"])));	
	}
}