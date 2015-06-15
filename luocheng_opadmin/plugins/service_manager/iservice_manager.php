<?php 
namespace plugins\service_manager;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;
use kernel\base\base_class\mongopage as MONGOPAGE;

class iservice_manager extends module
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
	
	function open_service_list($operators_id)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$server_list=$mongopage->mongodb($tmp_mongo,"server_list",array("operators_id"=>intval($operators_id),"combined_service"=>0),array("server_id"=>-1));
		$mypages=$mongopage->p(true);
		return array($server_list,$mypages);
	}
	
	function upsestatus($operators_id,$server_id,$status)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$tmp_server_list=$tmp_mongo->update("server_list",array("status"=>$status),array("operators_id"=>intval($operators_id),"server_id"=>intval($server_id)));
	}
	
	function combined_service_list($operators_id)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$server_list_tmp=$tmp_mongo->find("server_list",array('combined_service' =>array('$gt'=>0)))->sort(array("combined_service"=>-1));
		$server_list=array();
		foreach($server_list_tmp as $value)
		{
			if($value["combined_service"]==$value["server_id"]){
				$server_list[]=	$value;
			}
		}

		//$server_list=$tmp_mongo->find("server_list",array ('$where' => 'this.combined_service==this.server_id'))->sort(array("combined_service"=>-1));
		return array($server_list,$mypages);	
	}
	
	function add_server_show($operators_id)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$server_list=$tmp_mongo->find("server_list",array ('operators_id' =>intval($operators_id)))->sort(array("server_id"=>-1))->limit(1);
		foreach($server_list as $value)
		{
			return 	$value;
		}
		return array(
		  "mongoname"=> "s_".$operators_id."_1",
		  "operators_id"=>$operators_id,
		  "server_id"=> 0,
		  "server_name"=>"电信0服",
		);
	}
	
	function add_server($data)
	{
		$tmp=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".trim($data["operators_id"]),0,"operators");
		//实例一个mongo
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo->tmpmongoconn($tmp[0]["server_mongo_ip"],$tmp[0]["server_mongo_port"],$data["mongo_db"],"","",$tmp[0]["mongo_line"],$tmp[0]["mongo_auth"]);
		
	   //添加一个用户名
	   $tmp_mongo->adduser($tmp[0]["mongo_username"],$tmp[0]["mongo_password"]);
	   create_index($tmp_mongo);

		
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",intval($data["operators_id"]));
		
		preg_match_all("/[0-9]*/", $data["server_name"] , $match);
		$aliases_server_id=intval($match[0][6]);
		
		$server_data=array(
			"aliases_server_id"=>$aliases_server_id,
			"combined_service"=>0,
			"combined_service_list"=> array(),
			"mongoname"=>$data["mongo_db"],
			"operators_id"=>intval($data["operators_id"]),
			"server_id"=>intval($data["server_id"]),
			"server_name"=>$data["server_name"],
			"status"=>1,
			"open_service_time"=>0,
			"today"=>date("Y-m-d",time())

		);
		$tmp_mongo->insert("server_list",$server_data);
	}
	
	function free_service_list($operators_id)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$server_list=$tmp_mongo->find("server_list",array ('operators_id' =>intval($operators_id),"combined_service"=>0))->sort(array("server_id"=>1));
		return $server_list;
	}
	
	function select_service($operators_id,$server_id,$mainid)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$tmp_mongo->update("server_list",array("combined_service"=>intval($mainid)),array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
		$server_list=$tmp_mongo->findone("server_list",array ('operators_id' =>intval($operators_id),"server_id"=>intval($mainid)));
		if(in_array(intval($server_id),$server_list["combined_service_list"])==false)
		{
			$server_list["combined_service_list"][]=intval($server_id);
			$tmp_mongo->update("server_list",array("combined_service_list"=>$server_list["combined_service_list"]),array ('operators_id' =>intval($operators_id),"server_id"=>intval($mainid)));
		}
	}
	
	function del_combined_service($operators_id,$server_id,$mainid)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$tmp_mongo->update("server_list",array("combined_service"=>0),array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
		
		$server_list=$tmp_mongo->findone("server_list",array ('operators_id' =>intval($operators_id),"server_id"=>intval($mainid)));
		if(in_array(intval($server_id),$server_list["combined_service_list"]))
		{
			for($i=0;$i<count($server_list["combined_service_list"]);$i++)
			{
				if($server_list["combined_service_list"][$i]==	$server_id)
				{
					unset($server_list["combined_service_list"][$i]);	
				}
			}

			$tmp_mongo->update("server_list",array("combined_service_list"=>$server_list["combined_service_list"]),array ('operators_id' =>intval($operators_id),"server_id"=>intval($mainid)));
		}	
	}
	
	function create_combined($operators_id,$server_id)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		
		$tmp_mongo->update("server_list",array("combined_name"=>"未命名","combined_service_list"=>array(intval($server_id)),"combined_service"=>intval($server_id)),array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
	}
	
	function upservice_data($query_filed,$query_value,$filed,$data)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		
		$tmp_mongo->update("server_list",array($filed=>$data),array ($query_filed=>intval($query_value)));
	}
	
	function delservice($operators_id,$server_id)
	{
		$dbname="s_".trim($operators_id)."_".trim($server_id);

		$tmp_mongo=new TMP_MONGO;
		
		$tmp_mongo=openmongodb($tmp_mongo,"admin",intval($operators_id));
		$tmp_mongo->phpmongo->dropDB($dbname);
		
		
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",intval($operators_id));
		
		$server_list=$tmp_mongo->findone("server_list",array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
		if($server_list["combined_service"]>0)
		{//处理合服里面的数据
			$c_server_list=$tmp_mongo->findone("server_list",array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_list["combined_service"])));
			if(in_array(intval($server_id),$c_server_list["combined_service_list"]))
			{
				for($i=0;$i<count($c_server_list["combined_service_list"]);$i++)
				{
					if($c_server_list["combined_service_list"][$i]==$server_id)
					{
						unset($c_server_list["combined_service_list"][$i]);	
					}
				}
	
				$tmp_mongo->update("server_list",array("combined_service_list"=>$c_server_list["combined_service_list"]),array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_list["combined_service"])));
			}	
		}
		
		$tmp_mongo->delete("server_list",array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
	}
	
	function upopen_service_time($operators_id,$server_id,$time)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		
		$tmp_mongo->update("server_list",array("open_service_time"=>$time),array ('operators_id' =>intval($operators_id),"server_id"=>intval($server_id)));
	}
	
	function pay_count($server_data, $operators_id, $select_platform)
	{
		//,`mongo_auth`
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,trim($server_data["mongoname"]), $operators_id);
		
		$query = array();
		if ($select_platform == 'ios') {
			$query = array("mobile_platforms"=>'ios');
		} elseif ($select_platform == 'android') {
			$query = array("mobile_platforms"=>'android');
		}
		
		$pay_log=$tmp_mongo->aggregate("log_pay",array("openid"),array("money"),$query,array("openid"=>1,"money"=>1),array("openid"=>-1),$skip=0,$limit=0,$mtype=0);
		$data = array();
		$rundata=array();
		foreach($pay_log as $key=>$value)
		{
			$openid=$value["_id"]["openid"];
			$rundata[$openid]=$value["money"];
		}
		$data[0] = $rundata;

		$data[1] = $tmp_mongo->find("user_info")->count();
		$curr_date = date(‘Ymd’);
		$query1 = array("today"=>$curr_date);
		$query1 = array_merge($query, $query1);
		$rsl = $tmp_mongo->findone("log_two_login", $query1);
		$data[2] = $rsl;
		return $data;
	}
	
	function open_server_statistics($operators_id, $server_id, $time1, $time2,$select_platform)
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		if ($server_id == 0) {
			$query = array("operators_id"=>intval($operators_id),"combined_service"=>0, "open_service_time"=>array('$gte'=>$time1,'$lte'=>$time2));
		} else {
			$query = array("operators_id"=>intval($operators_id),"combined_service"=>0, "open_service_time"=>array('$gte'=>$time1,'$lte'=>$time2),"server_id"=>$server_id);
		}
		$server_list=$mongopage->mongodb($tmp_mongo,"server_list",$query,array("server_id"=>-1));
		$mypages=$mongopage->p(true);
		
		$money_list=array();
		foreach($server_list as $key=>$value)
		{
			$money_list[$value["server_id"]]=$this->pay_count($value, $operators_id, $select_platform);
		}

		return array($server_list,$mypages, $money_list);
	}
	
	function gateway_listen($operators_id, $type)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
	
		$gateway_listen_list=$tmp_mongo->findsort("log_gateway_listen",array("type"=>$type),array("time"=>1));

		$ret_list = array();
		$send_q = 0;
		$recv_q = 0;
	
		$ind = 0;
		$send_one = array();
		$recv_one = array();
		foreach ($gateway_listen_list as $key=>$value) {
			$send_q = $value["LAN_traffic_send"];
			$recv_q = $value["LAN_traffic_recv"];
			if ($ind == 0) {
				$value["LAN_traffic_send"] = 0;
				$value["LAN_traffic_recv"] = 0;
			} else {
				$value["LAN_traffic_send"] = $send_q - $send_one[$value["ip"]];
				$value["LAN_traffic_recv"] = $recv_q - $recv_one[$value["ip"]];
			}
			$ret_list[$value["ip"]] = $value;
			
			$send_one[$value["ip"]] = $send_q;
			$recv_one[$value["ip"]] = $recv_q;
			$ind++;
// 			if (!array_key_exists($value["ip"], $send_one)) {
// 				$send_one[$value["ip"]] = $value["LAN_traffic_send"];
// 				$recv_one[$value["ip"]] = $value["LAN_traffic_recv"];
// 			}
		}
		
		return $ret_list;
	}
	
	//用户曲线
	function newlog_online($date)
	{
		if($date==1){
			$onarr=$this->phpmongo->find("log_online",array("date"=>intval(date("Ymd",time()))))->sort(array("H"=>-1,"M"=>-1))->limit(24);
		}elseif($date==2){
			$onarr=$this->phpmongo->find("log_online",array("date"=>intval(date("Ymd",time()))))->sort(array("H"=>1,"M"=>1));
		}else{
			$onarr=$this->phpmongo->find("log_online",array("month"=>intval(str_replace("-","",$date))))->sort(array("date"=>1));
		}
		return $onarr;
	}
	
	function cpu_memory_used($operators_id, $type, $ip, $date)
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		
		if($date==1){
			$onarr=$tmp_mongo->find("log_gateway_listen",array("type"=>$type,"ip"=>$ip,"date"=>intval(date("Ymd",time()))))->sort(array("time"=>-1))->limit(24);
		}elseif($date==2){
			$onarr=$tmp_mongo->find("log_gateway_listen",array("type"=>$type,"ip"=>$ip,"date"=>intval(date("Ymd",time()))))->sort(array("time"=>1));
		}else{
			$onarr=$tmp_mongo->find("log_gateway_listen",array("type"=>$type,"ip"=>$ip,"month"=>intval(str_replace("-","",$date))))->sort(array("date"=>1,"YmdHM"=>-1));
		}
		
		#$gateway_listen_list=$tmp_mongo->findsort("log_gateway_listen",array("type"=>$type,"ip"=>$ip),array("YmdHM"=>1));
		
		return $onarr;
	}
}
