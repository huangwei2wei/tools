<?php 
namespace plugins\user;
use controllers\module as MODULE;


class iuser extends module
{
	//用户列表
	function user_list($time,$page)
	{
		if($time)
		{
			$time1=strtotime($time." 00:00:00");
			$time2=strtotime($time." 23:59:59");
			$q=array(
				"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
				);
			
			$q1=array(
					"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
					"online"=>1
			);
		}else{
			$q=array();
			$q1=array("online"=>1);
		}
		
		//用户指定日期注册总数
		$user_info_count=$this->phpmongo->find("user_info",$q)->count();
		//用户指定日期，现在 在线总数
		$user_online_count=$this->phpmongo->find("user_info",$q1)->count();
		//用户指定日期在线列表
		$onarr=restoarray($this->phpmongo->find("user_info",$q1,array("user_id"=>1,"createtime"=>1))->sort(array("user_id"=>-1))->skip(($page-1)*10)->limit(10));
		
		foreach ($onarr as $key=>$value)
		{
			$user_hero=$this->phpmongo->findone("user_hero",array("user_id"=>$value["user_id"],"main"=>1));
			$onarr[$key]["name"]=$user_hero["name"];
			$onarr[$key]["lv"]=$user_hero["lv"];
		}
		
		return array($onarr,$user_info_count,$user_online_count);
	}
	
	function regtimelist($time1,$time2)
	{
		if($time1==0 or $time2==0)
		{
			$q=array();
		}else{
			$time1=strtotime($time1." 00:00:00");
			$time2=strtotime($time2." 23:59:59");
			$q=array(
				"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
				);
		}
		$user_online_count=$this->phpmongo->group("user_info","H",$q,"createtime",SORT_ASC);
		return $user_online_count["retval"];
	}
	function getAcuPcu($time1,$time2){
		
		if($time1==0 or $time2==0)
		{
			$q=array();
		}else{
			$time1=(int)$time1;
			$time2=(int)$time2;
			$q=array(
				"date"=>array('$gte'=>$time1,'$lte'=>$time2),
				);
		}
		
		$res = restoarray($this->phpmongo->find("log_online",$q));
		$runData = array();
		
		foreach($res as $k => $v){
			$date = $v["date"];
			$ip = $v["ip"];
			
			if(!isset($runData[$date])){
				$runData[$date] = array("count"=>0,"total"=>0,"max"=>0,"min"=>9999999);
			}
			
			foreach($ip as $k1 => $v1){
				$runData[$date]["total"] += $v1;
				if($v1 > $runData[$date]["max"]){
					$runData[$date]["max"] = $v1;
				}
				if($v1<$runData[$date]["min"]){
					$runData[$date]["min"] = $v1;
				}
				$runData[$date]["count"] ++;
			}
		}
		return $runData;
	}
	
	
	function logintimelist($time1,$time2)
	{
		$time1=strtotime($time1." 00:00:00");
		$time2=strtotime($time2." 23:59:59");
		$q=array(
			"ontime"=>array('$gt'=>$time1,'$lt'=>$time2),
			);
			
		$user_online_count=$this->phpmongo->find("user_info",$q,array("ontime"=>1));
		$tmp=array();
		foreach($user_online_count as $value)
		{
			$d=date("Ymd",$value["ontime"]);
			$h=intval(date("H",$value["ontime"]));
			if(isset($tmp[$d])==false)$tmp[$d]=array();
			if(isset($tmp[$d][$h])==false)$tmp[$d][$h]=0;
			$tmp[$d][$h]+=1;
		}
		//$user_online_count=$this->phpmongo->group("user_info","ontime",$q,"ontime",SORT_ASC);
		return $tmp;
	}
	
	function logintimelist1($time1,$time2)
	{
//		echo $time1 ."   ".$time2;
		if($time1==0 or $time2==0)
		{
			$q=array();
		}else{
			$time1=strtotime($time1." 00:00:00");
			$time2=strtotime($time2." 23:59:59");
			$q=array(
				"downtime"=>array('$gt'=>$time1,'$lt'=>$time2),
				);
		}
//		print_r( $q);
		$user_online_count=$this->phpmongo->find("user_info",$q,array("downtime"=>1));
		$tmp=array();
		foreach($user_online_count as $value)
		{
			$t=date("Y-m-d",$value["downtime"]);
			if(isset($tmp[$t])==false)$tmp[$t]=0;
			$tmp[$t]+=1;
		}
//		print_r($tmp);
		return $tmp;
	}
	
	//用户在线总数
	function on_user_sum()
	{
		$log_online=$this->phpmongo->find("log_online")->sort(array("date"=>-1,"H"=>-1,"M"=>-1))->limit(1);
		if($log_online==false)return 0;
		$onl=0;
		foreach($log_online as $value)
		{
			foreach($value["ip"] as $v)$onl+=$v;	
		}
		return $onl;

	}
	
	function get_data($time1=false,$time2=false){
		$q = array();
		$r = array();
		if($time1 && $time2){
			$time1=intVal(date("Ymd", strtotime(strval($time1))));
			$time2=intVal(date("Ymd", strtotime(strval($time2))));
			$q=array(
				"today"=>array('$gte'=>$time1,'$lte'=>$time2),
			);
			$r = $this->phpmongo->new_aggregate("log_two_login",array("today"),false,array(  "today_login_count",
						  "todayloginmax5",
						  "todayloginmax30",
						  "today_pay_count30",
						  "today_login_sum",
						  "activation",
						  "pay_number",
						  "total_LT",
						  "todayloginmax15",
						  "pay_money",
						  "reg_pay_number_people",
						  "activation_login",
						  "todayloginmax7",
						  "today_reg",
						  "today_pay_count7",
						  "pay_number_people",
						  "today_pay7",
						  "reg_pay_money",
						  "todayloginmax6",
						  "reg_pay_number",
						  "todayloginmax2",
						  "todayloginmax3",
						  "todayloginmax4",
						  "today_pay30"),$q, false, array('today'=>-1), 0, 0, 2);
//			$rsl =  $log_online=$this->phpmongo->find("log_two_login",$q)->sort(array("today"=>-1));
		}else{
			$r = $this->phpmongo->new_aggregate("log_two_login",array("today"),false,array(  "today_login_count",
									  "todayloginmax5",
									  "todayloginmax30",
									  "today_pay_count30",
									  "today_login_sum",
									  "activation",
									  "pay_number",
									  "total_LT",
									  "todayloginmax15",
									  "pay_money",
									  "reg_pay_number_people",
									  "activation_login",
									  "todayloginmax7",
									  "today_reg",
									  "today_pay_count7",
									  "pay_number_people",
									  "today_pay7",
									  "reg_pay_money",
									  "todayloginmax6",
									  "reg_pay_number",
									  "todayloginmax2",
									  "todayloginmax3",
									  "todayloginmax4",
									  "today_pay30"),$q, false, array('today'=>-1), 0, 7, 2);
//			$rsl =  $log_online=$this->phpmongo->find("log_two_login")->sort(array("today"=>-1))->limit(7);	
		}
//		print_r($r);
		$run = array();
		foreach($r as $k => $value){
			$value["today"]=$value["_id"]["today"];
			$run[] = $value;
		}
		return $run;
	}
	
	//联运渠道数据统计
	function get_data1($time1=false,$time2=false,$channel_id=false,$mobile_platforms=false){
		$q = array();
		if($time1 && $time2){
			$time1=intVal(date("Ymd", strtotime(strval($time1))));
			$time2=intVal(date("Ymd", strtotime(strval($time2))));
			$q=array(
				"today"=>array('$gte'=>$time1,'$lte'=>$time2),
			);
		}
		if($channel_id){
			$q["channel_id"] = intval( $channel_id);
		}
		if($mobile_platforms){
			$q["mobile_platforms"] = $mobile_platforms;
		}
		
		// new_aggregate($table,$groupkey,$group=false,$countkeys=false,$query=false,$show=false,$sort=false,$skip=0,$limit=0,$mtype=1)
		$r = $this->phpmongo->new_aggregate("log_two_login",array("today"),false,array("today_login_count",
					  "todayloginmax5",
					  "todayloginmax30",
					  "today_pay_count30",
					  "activation",
					  "pay_number",
					  "total_LT",
					  "todayloginmax15",
					  "pay_money",
					  "reg_pay_number_people",
					  "activation_login",
					  "todayloginmax7",
					  "today_reg",
					  "today_pay_count7",
					  "pay_number_people",
					  "today_pay7",
					  "reg_pay_money",
					  "todayloginmax6",
					  "reg_pay_number",
					  "todayloginmax2",
					  "todayloginmax3",
					  "todayloginmax4",
					  "today_pay30"),$q, false, array('today'=>-1), 0, 0, 2);

		$run = array();
		foreach($r as $k => $value){
			$value["today"]=$value["_id"]["today"];
			$run[] = $value;
		}
		return $run;
	}
	
	
	
	
	//用户注册总数
	function reg_user_sum()
	{
		return $this->phpmongo->find("user_info")->count();
	}
	//总激活数
	function act_user_sum()
	{
		return $this->phpmongo->find("user_base")->count();
	}
	
	//充值总数
	function recharge_line_day($time1,$time2)
	  {
		$time1=strtotime($time1." 00:00:00");
		$time2=strtotime($time2." 23:59:59");
		$q=array(
			"ts"=>array('$gt'=>$time1,'$lt'=>$time2),
		);
		$pay_log=$this->phpmongo->find("log_pay",$q); 
		
		$money=0;
		foreach($pay_log as $value)
		{
			$money+=$value["money"];
		}
		return array($money,$pay_log->count());
	  }
	//总建号数量
	function c_user_sum()
	{
		return $this->phpmongo->find("user_hero",array("main" =>1))->count();
	}
	
	//用户等级统计
	function user_lv_count($data=array())
	{
		$mobile_platforms = $data["mobile_platforms"];
		$q = array();
		if(isset($data["time1"]) and $data["time1"]){
			$time1=intval(date("Ymd", strtotime($data["time1"]." 00:00:00")));
			$time2= intval(date("Ymd",strtotime($data["time2"]." 23:59:59")));
			$q["create_time"]=array('$gte'=>$time1,'$lte'=>$time2);
		}
		if($mobile_platforms){
			$q["mobile_platforms"] = $mobile_platforms;
		}else{
			$q["mobile_platforms"] = "All";
		}

		$run = array();
		$data = $this->phpmongo->find("log_lv_info",$q);
		
		$stop_player_total_num=0;
		foreach($data as $k=>$v){
			unset($v["_id"]);
			$run[$v["lv"]][] = $v;
			$stop_player_total_num=$v["stop_player_num"];
		}
		$run2 = array();
		foreach($run as $k=>$v){
			$log_pay_num = 0;
			$stop_player_num = 0;
			$log_pay_money = 0;
			$player_num = 0;
			$num = count($v);
			foreach($v as $kk=>$obj){
				$average_use_time += $obj["average_use_time"];
				$log_pay_num += $obj["log_pay_num"];
				$stop_player_num += $obj["stop_player_num"];
				$log_pay_money += $obj["log_pay_money"];
				$player_num += $obj["player_num"];
			}
			$run2[$obj["lv"]] = array(
										"average_use_time"=>round($average_use_time,2)/$num,
										"create_time" =>$obj["create_time"],
										"log_pay_num"=> round($log_pay_num,2),
										"stop_player_num"=> round($stop_player_num,2),
										"log_pay_money"=> round($log_pay_money,2),
										"player_num"=> round($player_num,2),
										"mobile_platforms"=>$obj["mobile_platforms"],
										"lv"=>$obj["lv"],
										"stop_player_total_num" => $stop_player_total_num,
										);
		}
		return $run2;
	}
	
	//用户曲线
	function log_online()
	{
		$onarr=$this->phpmongo->find("log_online");
	
		return $onarr;
	
	}
	
	//用户曲线
	function newlog_online($date)
	{
		if($date==1){
			$onarr=$this->phpmongo->find("log_online",array("date"=>intval(date("Ymd",time()))))->sort(array("H"=>-1,"M"=>-1))->limit(24);
		}elseif($date==2){
			$onarr=$this->phpmongo->find("log_online",array("date"=>intval(date("Ymd",time()))))->sort(array("H"=>1,"M"=>1));
		}else{
			$onarr=$this->phpmongo->find("log_day_online",array("month"=>intval(str_replace("-","",$date))))->sort(array("date"=>1));
		}
		return $onarr;
	}
	
	//用户曲线2
	function dateonline($time1,$time2)
	{
		$q=array(
					"date"=>array('$gte'=>$time1,'$lte'=>$time2),"M"=>0,
				);
		$onarr=$this->phpmongo->find("log_online",$q)->sort(array("H"=>1,"M"=>1));
		return $onarr;
	}
	
	//用户地图统计
	function user_map_count()
	{
		$onarr=$this->phpmongo->group("user_info","in_map",array("is_copy" =>1));
		return $onarr;
	}
	
	//用户地图统计
	function user_main_map_count()
	{
		$onarr=$this->phpmongo->group("user_info","in_map",array("is_copy" =>0));
		return $onarr;
	}

	function base_map()
	{
		$base2_map1=dbarr("SELECT * FROM `base2_map1` WHERE 1");
		
		$newmap1=array();
		foreach($base2_map1 as $value)
		{
			$newmap1[$value["id"]]=$value;
		}
		
		
		$base2_map2=dbarr("SELECT * FROM `base2_map2` WHERE 1");
		
		$newmap2=array();
		foreach($base2_map2 as $value)
		{
			$newmap2[$value["id"]]=$value;
		}
		return array($newmap1,$newmap2);
	}

	//用户注册渠道
	function user_channel_count()
	{
		$onarr=$this->phpmongo->group("user_info","channel");

		return $onarr;
		
	}
	
	//统计模块进入点击次数
	function user_module_count()
	{
		    $module=dbarr("SELECT * FROM  `base_module` where 1");
		    $newdata=array();
		    foreach ($module as $key=>$value)
			    {
			    $newdata[$value["id"]]=$value["module_name"];
			    }
		    $oarr1=$this->phpmongo->find("module_count");

		    $countdata=array();
		  foreach($oarr1 as $key=>$value)
		    {
		    	

		    	if(isset($countdata[$value["module_id"]]))
		    	{
		    			$countdata[$value["module_id"]]["count"]+=$value["count"];
		    			
		    	}
		    			     
					else{
		    		$countdata[$value["module_id"]]=$value;
		    	}

		    }
   	    return array($countdata,$newdata); 
//		    return $oarr1; 

	}
	
	function delonlink()
	{
		$this->phpmongo->update("user_info",array("online"=>0),array("online"=>1));
	}

	function taskstop($post)
	{
		$q = array();
		$arr = array("today_3"=>3,"today_7"=>7,"today_14"=>14,"today_30"=>30);
		$day = 3;
		if(isset($post["field"])){
			$day = intVal($arr[$post["field"]]);
		}
		$q["create_time"] = array('$gte'=>intVal(date("Ymd",time()-$day*86400)));
		$r = $this->phpmongo->new_aggregate("log_ls_user_task",array("task_id"),array("task_name"=>array('$first'=>'$task_name')),array("player_num",
					  "ls_player_num",
					  ),$q, false, false, 0, 0, 2);
		$run = array();
		foreach($r as $k => $v){
			$run[] = array("task_id"=>$v["_id"]["task_id"],"task_name"=>$v["task_name"],"player_num"=>$v["player_num"],"ls_player_num"=>$v["ls_player_num"]);
		}
		return $run;
	}
	
	
	
	function checkpoint($type,$time1,$time2){			
		$query = array();
		if(isset($type) and $type != "All"){
			$query["copy_type"] = intval($type);
		}
		if($time1){
			if(!isset($query["time"])){
				$query["time"]=array();
			}
			$query["time"]['$gt']= $time1;
		}
		if($time2){
			if(!isset($query["time"])){
				$query["time"]=array();
			}
			$query["time"]['$lt']= $time2;
		}
		
//		print_r($query);
		$rsl = $this->phpmongo->find("log_copy",$query)->sort(array("copy_base_id"=>1));
		$run = array();
		$num1 = 0;//成功次数
		$num2 = 0;//失败次数
		$tpyeValue = array(1=>'大关卡',0=>'小关卡');
		$copy_base_id =0; 
		$copy_base_id_list = "(";
		foreach($rsl as $k => $value){
			unset($value["_id"]);
			if(isset($value[ $value["copy_base_id"] ])){
				$num1 = 0;
				$num2 = 0;
			}
			if($value["e"]=1){
				$num1++;
			}else{
				$num2++;
			}
			$value['copy_type'] = $tpyeValue[$value['copy_type']];
			if($copy_base_id != $value["copy_base_id"]){
				$copy_base_id_list .= strVal($value["copy_base_id"]).",";
				$run[strval($value["copy_base_id"])] = $value;
			}
			$copy_base_id = $value["copy_base_id"];
			$run[strval($value["copy_base_id"])]['cg'] = $num1;
			$run[strval($value["copy_base_id"])]['sb'] = $num2;
		}
		
		if(strlen($copy_base_id_list) >1){
			$copy_base_id_list = substr($copy_base_id_list,0,strlen($copy_base_id_list)-1);
		}
		
		$copy_base_id_list .=")";
		$sql = "SELECT name,id FROM  `base1_map_copy` where id in ".$copy_base_id_list;
		$copy_base_info = dbarr($sql);
//		echo $sql;
		foreach ($copy_base_info as $v){
			$run["copy_name"][$v["id"]] = $v["name"];
		}
		return $run;
	}
	
	
	function login2()
	{
		$time=date("Y-m-d",time());
		$time1=strtotime($time." 00:00:00");
		$time2=strtotime($time." 23:59:59");
		$q=array(
				"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
		);
		
		$todaymax=$this->phpmongo->find("user_info",$q)->count();
		
		$q=array(
				"downtime"=>array('$gt'=>$time1,'$lt'=>$time2),
		);
		$loginmax=$this->phpmongo->find("user_info",$q)->count();
		
		$regmax=$this->phpmongo->find("user_base")->count();
		return array($todaymax,$loginmax,$regmax);
	}
	
	function login2x()
	{		
		$two_login=$this->phpmongo->find("log_two_login")->sort(array("time"=>1));;
		return $two_login;
	}
	
	function actlog($nickname,$tab)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
		return $this->phpmongo->find($tab,array("user_id"=>$user_info["user_id"]))->sort(array("time"=>-1));
	}
	
	function globlist()
	{
		$user_info=$this->phpmongo->find("user_info",array())->sort(array("gold"=>-1))->limit(100);
		$user_idlist=array();
		$newuser_info=array();
		foreach($user_info as $value)
		{
			$user_idlist[]=$value["user_id"];
			$newuser_info[$value["user_id"]]=$value;
		}

		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$user_idlist),"main"=>1));
		$_user_hero=array();
		foreach($user_hero as $key=>$value)
		{
			$_user_hero[$value["user_id"]]=$value["name"];
		}
		
		foreach($newuser_info as $key=>$value)
		{
				$newuser_info[$key]["name"]=$_user_hero[$value["user_id"]];
		}
		return $newuser_info;
	}
	
	
	function operators_query($user_name,$type)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$user_name));
		$message=$this->phpmongo->find("message",array("get_id"=>$user_info["user_id"],"type"=>intval($type)));
		if(intval($type)==7)
		{
			$operators_activity=$this->phpmongo->find("operators_activity");
			$oplist=array();
			foreach($operators_activity as $value)
			{
				$newarr=$value["list"];
				unset($value["list"]);
				$oplist[$value["oid"]]=$value;
				foreach($newarr as $v)
				{
					$oplist[$value["oid"]]["newlist"][$v["id"]]=$v;
				}
			}
			
			$newmessage=array();
			foreach($message as $value)
			{
				//$value["name"]=$oplist[$value["info"]["oid"]][$value["info"]["subid"]]["condition_info"];
				$value["condition_info"]=$oplist[$value["info"]["oid"]]["newlist"][$value["info"]["subid"]]["condition_info"];
				$newmessage[]=$value;
			}
		}else{
			$newmessage=array();
			foreach($message as $value)
			{
				$newmessage[]=$value;
			}
		}
		
		$accuracy=array();
		foreach ($newmessage as $key => $row) 
		{
            $accuracy[$key] = $row["send_time"];
		}
		//SORT_ASC SORT_DESC
		if(count($accuracy)>1)
		{
			array_multisort($accuracy, SORT_DESC,$newmessage);
		}
		
		return $newmessage;
	}
	
	function ranking($type)
	{
		//force
		if($type==1){
			$user_info=$this->phpmongo->find("user_info",array(),array("user_id"=>1,"force"=>1,"user_lv"=>1))->sort(array("user_lv"=>-1))->limit(100);
		}else{
			$user_info=$this->phpmongo->find("user_info",array(),array("user_id"=>1,"force"=>1,"user_lv"=>1))->sort(array("force"=>-1))->limit(100);
		}
		
		$uid=array();
		$newinfo=array();
		foreach($user_info as $value)
		{
			$newinfo[]=	$value;
			$uid[]=$value["user_id"];
		}
		
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$uid),"main"=>1),array("user_id"=>1,"name"=>1));
		$newhero=array();
		foreach($user_hero as $value)
		{
			$newhero[$value["user_id"]]=$value["name"];
		}
		return array($newinfo,$newhero);
	}
	//*********************************************************************************//
	
	function user_habit($time1, $time2)
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$oneday = 24*60*60;
		$query = array("time"=>array('$gte'=>$time1,'$lte'=>$time2));
		$countkeys = array("today_LT", "today_login_sum", "today_login_count");
		$user_habit_info = $this->phpmongo->aggregate("log_two_login",array("today"),$countkeys,$query,false,false,0,0,0);
		
		$info_arr = array();
		for($time=$time1; $time<$time2; ) {
			if ($time + $oneday -1 <= $time2) {
				$date1=date('Ymd', $time);
				$info_arr[$date1]=array("today_LT"=>0, "today_login_sum"=>0, "today_login_count"=>0);
				foreach ($user_habit_info as $key=>$value) {
					if(strval($value["_id"]["today"]) == $date1) {
						$info_arr[$date1]=array("today_LT"=>$value["today_LT"], "today_login_sum"=>$value["today_login_sum"], "today_login_count"=>$value["today_login_count"]);
					}
				}
			}
			$time = $time+$oneday;
		}
		
		return  $info_arr;
	}
	
	function avearge_user_habit($time1, $time2)
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$oneday = 24*60*60;
		$query = array("time"=>array('$gte'=>$time1,'$lte'=>$time2));
		$countkeys = array("today_LT", "today_login_sum", "today_login_count");
		$user_habit_info = $this->phpmongo->aggregate("log_two_login",array("today"),$countkeys,$query,false,false,0,0,0);
		
		$total_lt = 0;
		$total_login_times = 0;
		$total_login_user_num = 0;
		foreach ($user_habit_info as $key=>$value) {
			$total_lt += $value["today_LT"];
			$total_login_times += $value["today_login_sum"];
			$total_login_user_num += $value["today_login_count"];
		}
		
		$days = ($time2 - $time1) / $oneday;
		
		$avearge_time = ($total_lt/$total_login_user_num) / intval($days);
		$avearge_times = ($total_login_times/$total_login_user_num)/ intval($days);
		
		return  array($avearge_time, $avearge_times);
	}
	
	function user_online_duration($time1, $time2)
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$date1 = intval(date("Ymd", $time1));
		$date2 = intval(date("Ymd", $time2));
		
		$user_online_duration = $this->phpmongo->find("log_online_statistics",array("date"=>array('$gte'=>$date1,'$lte'=>$date2)));
	
		return  $user_online_duration;
	}
	
	function novice_progress($time1, $time2){
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		return $this->phpmongo->find("log_user_step_today",array("time"=>array('$gte'=>$time1,'$lte'=>$time2)));
	}
	
}