<?php 
namespace plugins\server_analysis;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class iserver_analysis extends module
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
	
	function first_day($tmp_mongo)
	{
		$ts=0;
		$pay_log_a=$tmp_mongo->find("log_pay")->sort(array("ts"=>1));
		$tmp_pay_count=$pay_log_a->count();
		foreach($pay_log_a as $tmpvalue){$ts=$tmpvalue["ts"];break;}
		$date=date("Y-m-d",$ts);
		$time1=strtotime($date." 00:00:00");
		$time2=strtotime($date." 23:59:59");
		$pay_log=$tmp_mongo->find("log_pay",array("ts"=>array('$gte'=>$time1,'$lte'=>$time2)))->count();
		if($pay_log==0)return 0;
		if($pay_log>5)
		{
			return 	$ts;
		}else{
			return 	$ts+86400;
		}
	}
	
	function analysis($analysis,$time)
	{
		$tmp_mongo=new TMP_MONGO;
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		
		$server_data=array();
		foreach($analysis as $server_id){
			$mongo_db="s_".intval($_SESSION["userid"])."_".$server_id;
			$tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],$mongo_db,$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"],$operators[0]["mongo_auth"]);
			//取得此服第一天时间
			$ts=$this->first_day($tmp_mongo);
			if($ts==0)continue;
			$query_time=(intval($time)-1)*86400;

			$query_time=$ts+intval($query_time);
			$date=date("Y-m-d",$query_time);
			$time1=strtotime($date." 00:00:00");
			$time2=strtotime($date." 23:59:59");
			
			//获取充值
			$pay_log_b=$tmp_mongo->aggregate("log_pay",array("openid"),array("money"),array("ts"=>array('$gte'=>$time1,'$lte'=>$time2)),array("ts"=>1,"money"=>1,"openid"=>1),array("ts"=>-1),$skip=0,$limit=0,$mtype=0);

			$today_money=0;
			$money=0;
			foreach($pay_log_b as $k=>$v)
			{
				//修正注册时间
				$base_user=$tmp_mongo->findone("user_base",array("user_name"=>$v["_id"]["openid"]));

				if($date==date("Y-m-d",$base_user["createtime"])){
					$today_money+=$v["money"];
				}else{
					$money+=$v["money"];
				}
			}
			
			//获取注册
			$base_user_reg_max=$tmp_mongo->find("user_base",array("createtime"=>array('$gte'=>$time1,'$lte'=>$time2)))->count();
			
			$log_online=$tmp_mongo->find("log_online",array("date"=>intval(date("Ymd",$query_time))));

			$_max=0;
			$oncount=0;
			if($log_online){
				foreach($log_online as $onv){
					foreach($onv["ip"] as $vcount)
					{
						if($vcount>=$_max)$_max=$vcount;
						$oncount+=$vcount;
					}
				}
			}
			
			$tm["open_server_date"]=$query_time;//开服日期
			$tm["base_user_reg_max"]=$base_user_reg_max;//注册数
			$tm["online"]=$_max;//在线数
			$tm["average_online"]=$oncount/$log_online->count();//平均在线
			$tm["today_money"]=$today_money;//当天注册用户充值数
			$tm["money"]=$money;//以前注册充值数
			$tm["money_count"]=$today_money+$money;//总充值数
			$server_data[$server_id]=$tm;
		}
		
		return $server_data;
	}
	
	
}