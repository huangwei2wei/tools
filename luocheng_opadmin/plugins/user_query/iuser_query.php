<?php 
namespace plugins\user_query;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class iuser_query extends module
{
	function queryPlayer($post){
		
		$q = array();
		$run  = array();
		if(isset($post["nickname"])){
			$q =array("nickname"=> trim($post["nickname"]));
			$user_info = $this->phpmongo->findone("user_info",$q);
			$user_base = $this->phpmongo->findone("user_base",array("combined_user_id"=>intval($user_info["user_id"])));
			
			$guild_id = intval($user_info["guild_id"]);
			$guild_name = "";
			if($guild_id>0){
				$guild = $this->phpmongo->findone("guild",array("id"=>$guild_id));
				$guild_name = $guild["guild_name"];
			}
			
			if($user_info){
				#充值计算统计$user_base["user_name"]($table,$groupkey,$group=false,$countkeys=false,$query=false,$show=false,$sort=false,$skip=0,$limit=0,$mtype=1)
				$r = $this->phpmongo->new_aggregate("log_pay",array("openid"),false,array("money"),array("openid"=>"trda"), false, false, 0, 0, 2);
				
				$f = $this->phpmongo->find("friends",array("user_id"=>$user_info["user_id"]));
//				$f = $this->phpmongo->new_aggregate("friends",array("user_id"),false,array("goal_uid"),array("user_id"=>$user_info["user_id"]), false, false, 0, 0, 1);
				$d = array( 
					"user_id"=>$user_info["user_id"],
					"user_name"=>$user_base["user_name"],
					"mobile_activation"=>$user_base["mobile_activation"],
					"createtime"=>$user_base["createtime"],
					"channel_id"=>$user_base["channel_id"],
					"user_ip"=>$user_info["user_ip"],
					"nickname"=>$user_info["nickname"],
					"user_lv"=>$user_info["user_lv"],
					"money"=>$r[0]["money"],
					"total_LT"=>$user_base["total_LT"],
					"diamond"=>$user_info["diamond"],
					"guild_name"=>$guild_name,
					"friends_count"=>$f->count()
				);
				
				$tmp_mongo=new TMP_MONGO;
				$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
				$rsl = $tmp_mongo->findOne("user_list",array("user_name"=>$d["user_name"]));
				$s = "";
				if($rsl){
					foreach($rsl as $k=>$v){
						if($k != "all_server_id")continue;
						foreach($v as $v1){
							$s = strVal( $v1) ." 服, ";
						}
					}
				}
				$d["server"] = $s;
				$run[] = $d;
			}
		}
//		print_r($run);
		return $run;
	}
	
	//用户设备统计
	function mPlayerQuery($post){
		$q = array();
 
		if(isset($post["time1"])  && isset($post["time2"]) && $post["time1"]){
			$time1=strtotime($post["time1"]." 00:00:00");
			$time2=strtotime($post["time2"]." 23:59:59");
			$q["createtime"] = array('$gte'=>$time1,'$lte'=>$time2);
		}
		if(isset($post["mobile_platforms"]) && $post["mobile_platforms"] && $post["mobile_platforms"]!="All"){
			$q["mobile_platforms"] = trim($post["mobile_platforms"]);
		}
		if(isset($post["channel_id"]) && $post["channel_id"]){
			$q["channel_id"] = intVal($post["channel_id"]);
		}
		
		$v_type = "mobile_model";
		if(isset($post["v_type"]) && $post["v_type"] ){
			$v_type = $post["v_type"];
		}
 		$r = $this->phpmongo->new_aggregate("user_base",array($v_type),false,array("id"),$q, false, false, 0, 0, 1);
 		$run= array();
 		$totla = 0;
 		foreach($r as $k => $v){
 			$totla += $v["id"];
 		}
 		foreach($r as $k => $v){
 			$run[] = array($v_type=>$v["_id"][$v_type],"num"=>$v["id"],"total" => $totla);
 		}
// 		print_r($run);
 		return $run;
	}
	
	
	//等级分布数据
	function userLvFbData($get){
		$q = array();
		if(isset($get["time1"])  && isset($get["time2"]) && $get["time1"]){
			$time1=strtotime($get["time1"]." 00:00:00");
			$time2=strtotime($get["time2"]." 23:59:59");
			$q["createtime"] = array('$gte'=>$time1,'$lte'=>$time2);
		}
		if(isset($get["channel_id"]) && intVal($get["channel_id"])>0){
			$channel_id = intVal($get["channel_id"]);
			$rsl = $this->phpmongo->find("user_base",array("channel_id"=>$channel_id));
			$combined_user_ids = array();
			foreach ($rsl as $k=>$v){
				$combined_user_ids[] = $v["combined_user_id"];
			}
			$q["user_id"]['$in'] = $combined_user_ids;
		}
		
//		print_r($q);
 		$r = $this->phpmongo->new_aggregate("user_info",array("user_lv"),false,array("user_id"),$q, false, array('user_lv'=>1), 0, 0, 1);
 		$run= array();
 		foreach($r as $k => $v){
 			$run[] = array("user_lv"=>$v["_id"]["user_lv"],"num"=>$v["user_id"]);
 		}
 		$run = $this->multi_array_sort($run,"user_lv",SORT_ASC);
 		return $run;
	}
	//流失分析 数据
	function userLSData($get){
		$q = array();
//		if(isset($get["time1"])  && isset($get["time2"]) && $get["time1"]){
//			$time1=strtotime($get["time1"]." 00:00:00");
//			$time2=strtotime($get["time2"]." 23:59:59");
//			$q["createtime"] = array('$gte'=>$time1,'$lte'=>$time2);
//		}
		
		if(isset($get["mobile_platforms"]) && $get["mobile_platforms"] && $get["mobile_platforms"] !="All"){
			$q["mobile_platforms"] = trim($get["mobile_platforms"]);
		}
		$field = "today_LV3";
		if(isset($get["field"]) && $get["field"]){
			$field = trim($get["field"]);
		}
		if(isset($post["channel_id"]) && $get["channel_id"] && $get["channel_id"] !="All" ){
			$q["channel_id"] = trim($post["channel_id"]);
		}
		$q["today"] = intVal(date("Ymd"));
//		print_r($q);
		
		$r = $this->phpmongo->find("log_outflow",$q);
//		$run = array();
		$f = array("today_LV3","today_LV7","today_LV14","today_LV30","today_Paid3","today_Paid7","today_Paid14","today_Paid30");
		
		foreach($r as $k1=>$v1){
//			print_r($v1);
			foreach($v1  as $k2 =>$v2){
					if(in_array($k2 ,$f)){
						if(!array_key_exists($k2,$run)){
							$run[$k2] = array();
						}
						foreach ($v2 as $k3=>$v3){
							if(!array_key_exists($k3,$run[$k2])){
								$run[$k2][$k3] =  $v3;
							}else{
								$run[$k2][$k3] +=  $v3;
							}
						}
					}else{
						$run[$k2] = $v2;
					}
			}
		}
//		print_r($run);
		return $run[$field];
	}
	
	function vip_query($obj){
		$q = array();
//		print_r($obj);
		if(isset($obj["channel_id"]) and $obj["channel_id"]!="All"){
			$q["channel_id"] = intVal($obj["channel_id"]);
		}
		
		if(isset($obj["time1"]) && $obj["time1"] ){
			$q["date"]['$gte'] = intVal(str_replace('-','', $obj["time1"]));
		}
		
		if(isset($obj["time2"]) && $obj["time2"] ){
			$q["date"]['$lte'] = intVal(str_replace('-','', $obj["time2"]));
		}
//		print_r($q);
		$rsl = $this->phpmongo->new_aggregate("log_vip",array("vip_lv","date"),false,array("vip_lv"),$q, array('date'=>1,"vip_lv"=>1), array('date'=>1,"vip_lv"=>-1), 0, 0, 1);
//		print_r($rsl);
		$run = array();
		foreach($rsl as $k=>$v){
			
			$date = $v["_id"]["date"];
			$vip_lv = $v["_id"]["vip_lv"];
			
			if(!isset($run[$date])){
				$run[$date] = array();
			}
			
			$run[$date]["total_num"] += $v['vip_lv'];
			$vip_lv = $v["_id"]["vip_lv"];
			$run[$date]["data"]["vip_lv_".$vip_lv] = $v['vip_lv'];
		}
//		print_r($run);
		return $run;
	}
	
	function diamond_query($obj){
		$q = array();
		
		if(isset($obj["time1"]) && $obj["time1"] ){
			$q["date"]['$gte'] = intVal(str_replace('-','', $obj["time1"]));
		}
		
		if(isset($obj["time2"]) && $obj["time2"] ){
			$q["date"]['$lte'] = intVal(str_replace('-','', $obj["time2"]));
		}
		$rsl = $this->phpmongo->find("log_diamond_statistics",$q)->sort(array("date"=>-1));
		$run = array();
		foreach($rsl as $k=>$v){
			$run[] = $v;
		}
		
		return $run;
	}
	
	
	
	
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

	function goods_query($post)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>trim($post["nickname"])),array("user_id"=>1));
		$base_goods_ext=dbarr( "SELECT a.name,b.goods_ext_id FROM `base_goods` as a inner join `base_goods_ext` as b on a.`id`=b.goods_base_id WHERE a.`name`= '".trim($post["goods_name"])."'");
		
		$goods_act=$this->phpmongo->find("log_goods_act",array("user_id"=>intval($user_info["user_id"]),"goods_ext_id"=>intval($base_goods_ext[0]["goods_ext_id"])));
		$del=array();
		$add=array();
		$goodslist=array();
		foreach($goods_act as $value)
		{
			if($value["e"]=="-"){
				if(isset($del[$value["goods_user_id"]])){
					$del[$value["goods_user_id"]]["max"]+=$value["max"];
				}else{
					$del[$value["goods_user_id"]]=$value;
				}
			}else{
				if(isset($add[$value["goods_user_id"]])){
					$add[$value["goods_user_id"]]["max"]+=$value["max"];
				}else{
					$add[$value["goods_user_id"]]=$value;
				}
			}
			$goodslist[]=$value;
		}

		foreach($del as $key=>$value)
		{
			if(isset($add[$key])){
				$add[$key]["max"]-=$value["max"];
				if($add[$key]["max"]<=0){
					unset($add[$key]);	
				}
			}	
		}
		return array($add,$del,$goodslist);
	}
	
	function module_name_list()
	{
		$money=dbarr("SELECT * FROM `base_module_sub` WHERE 1");
		$newdata=array();
		foreach ($money as $key=>$value)
		{
			$newdata[$value["id"]]=$value["sub_module_name"];
		}
		return $newdata;
		/*
		$base_module=dbarr("SELECT * FROM `base_module` WHERE 1");
		$modulename=array();
		foreach($base_module as $value)
		{
			$modulename[$value["id"]]=$value["module_name"];
		}
		return $modulename;*/
	}
	
	function consumer_query($post)
	{
		$user_hero=$this->phpmongo->findone("user_info",array("nickname"=>trim($post["nickname"])));
		$money_act=$this->phpmongo->find("log_money_act",array("money_type"=>1,"user_id"=>intval($user_hero["user_id"]),"e"=>"-"));
		$money_actlist=array();
		foreach($money_act as $value)
		{
			if(isset($money_actlist[$value["module_sub_id"]])==false){$money_actlist[$value["module_sub_id"]]=array();}
			$money_actlist[$value["module_sub_id"]][]=$value;
		}
		
		foreach($money_actlist as $key=>$value)
		{
			$money_actlist[$key]=$this->multi_array_sort($value,"time");
		}
		asort($money_actlist);
		return $money_actlist;
	}
	
	function league_list_query($post)
	{
		$league = $this->phpmongo->findone("guild",array("guild_name"=>strVal(trim($post["leaguename"]))));
		$rsl = $this->phpmongo->find("guild_user",array("guild_id"=>intval($league["id"])))->sort(array("user_type"=>1));
 		$run = array();
		foreach ($rsl as $v){
			//user_info = $this->phpmongo->findone("guild_user",array("guild_id"=>intval($league["id"])))->sort(array("user_type"=>1));
			$user_info = $this->phpmongo->findone("user_info",array("user_id"=>intval($v["guild_user_id"])),array("nickname"=>1));
//			print_r(array("user_id"=>intval($v["guild_user_id"])));
//			var_dump($user_info["nickname"]);
			$v["nickname"] = $user_info["nickname"];
			$run[] = $v;
		}
		
		return $run;
	}
	
	function league_war_e()
	{
		return $this->phpmongo->find("league_war_map")->sort(array("b4_oid"=>1));
	}
	
	function module_update_query($post)
	{
		//$user_base=$this->phpmongo->findone("user_base",array("user_name"=>trim($post["user_name"])));
		$user_hero=$this->phpmongo->findone("user_hero",array("name"=>trim($post["user_name"])),array("user_id"=>1));
		return $this->phpmongo->findone("user_info",array("user_id"=>intval($user_hero["user_id"])));
	}
	
	
	function userdate($data)
	{
		if(isset($data["time1"]) and $data["time1"]){
			$time1=strtotime($data["time1"]." 00:00:00");
			$time2=strtotime($data["time2"]." 23:59:59");
			$q=array(
					"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
					"money" =>array('$gt'=>0)
				);
				
				$q1=array(
					"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
					"money" =>0,
				);
			
		}else{
			return false;
		}
		
		$user_info=$this->phpmongo->find("user_info",$q,array("base_user_id"=>1,"money"=>1,"createtime"=>1,"user_id"=>1));
		$user_info1=$this->phpmongo->find("user_info",$q1,array("base_user_id"=>1,"money"=>1,"createtime"=>1,"user_lv"=>1,"user_id"=>1));
		$user_id=array();
		$userdata1=array();
		$userdata12=array();
		foreach($user_info as $value)
		{
			$user_id[]=$value["user_id"];
			$userdata1[]=$value;
		}
		
		foreach($user_info1 as $value)
		{
			//$user_id[]=$value["user_id"];
			if(isset($userdata12[$value["user_lv"]]))
			{
				$userdata12[$value["user_lv"]]++;
			}else{
				$userdata12[$value["user_lv"]]=1;
			}
		}
		
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$user_id),"main"=>1),array("name"=>1,"user_id"=>1));
		
		$hero_name=array();
		foreach($user_hero as $value)
		{
			$hero_name[$value["user_id"]]=$value["name"];
		}
		krsort($userdata12);
		
		$accuracy=array();
		foreach ($userdata1 as $key => $row) 
		{
            $accuracy[$key] = $row["money"];
		}
		//SORT_ASC SORT_DESC
		if(count($accuracy)>1)
		{
			array_multisort($accuracy, SORT_DESC,$userdata1);
		}
		
		return array($userdata1,$userdata12,$hero_name);
	}
	
	
	function login_award($post)
	{
		//$user_base=$this->phpmongo->findone("user_base",array("user_name"=>trim($post["user_name"])));
//		$user_hero=$this->phpmongo->findone("user_info",array("nickname"=>trim($post["nickname"]) ),array("user_id"=>1));
		$tmplogin_award= $this->phpmongo->findone("user_info",array("nickname"=>trim($post["nickname"]) ) );
		$login_award=(array)json_decode($tmplogin_award["up_login_award"]);
		$godos_ext_idlist="";
		echo "up_login_award:".$tmplogin_award["up_login_award"]."<br>";
		echo "up_login_award_time:".$tmplogin_award["up_login_award_time"]."<br>";
		
		foreach($login_award as $key=>$value)
		{
			$godos_ext_idlist.=$value[0].",";
		}

		$goodsdata=dbarr("SELECT b.`goods_name`,a.goods_ext_id FROM `base_goods_ext` a inner join base_goods b on a.goods_id=b.id WHERE a.goods_ext_id in (".substr($godos_ext_idlist,0,-1).")");
		
		$goodsarr=array();
		foreach($goodsdata as $value)
		{
			$goodsarr[$value["goods_ext_id"]]=$value["goods_name"];
		}
		return array("up_login_award"=>$login_award,"up_login_award_time"=>$tmplogin_award["up_login_award_time"],"goods_name"=>$goodsarr);
	}
	
	function res_ori($post)
	{
		$user_hero=$this->phpmongo->findone("user_hero",array("name"=>trim($post["user_name"])),array("user_id"=>1));
		if(intval($post["types"])==0){
			$query=array("e"=>trim($post["act"]),"user_id"=>intval($user_hero["user_id"]));
		}else{
			$query=array("money_type"=>intval($post["types"]),"e"=>trim($post["act"]),"user_id"=>intval($user_hero["user_id"]));
		}
		
		$money_act=$this->phpmongo->find("log_money_act",$query)->sort(array("time"=>-1));
		return $money_act;
	}
	
	function invitelist($invite_list)
	{
		//处理出邀请人
		$user_base=$this->phpmongo->find("user_base",array("user_name"=>array('$in'=>$invite_list)),array("id"=>1));
		$l=array();
		foreach($user_base as $value)
		{
			$l[]=$value["id"];
		}
		
		$user_info=$this->phpmongo->find("user_info",array("base_user_id"=>array('$in'=>$l)),array("user_id"=>1));	
		$l1=array();
		foreach($user_info as $value)
		{
			$l1[]=$value["user_id"];
		}
		
		
		$user_hero=$this->phpmongo->find("user_hero",array("main"=>1,"user_id"=>array('$in'=>$l1)),array("user_id"=>1,"name"=>1));
		$l2=array();
		foreach($user_info as $value)
		{
			$l2[$value["user_id"]]=$value["name"];
		}
		return $l2;
		
	}
	
	function invite_user($nickname)
	{
		$iopenid="";
		if(strlen($nickname)==32)
		{
			$iopenid=$nickname;
		}elseif(strlen($nickname)>0){
//			$user_hero=$this->phpmongo->findone("user_hero",array("name"=>$nickname,"main"=>1),array("user_id"=>1,"name"=>1));
			$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname),array("base_user_id"=>1));	
			$user_base=$this->phpmongo->findone("user_base",array("id"=>$user_info["base_user_id"]));	
			$iopenid=$user_base["user_name"];
		}
		
		$tmptime=array();
		$invite_user_list=array();
		$invite_list=array();
		if($iopenid==""){
			$user_info=$this->phpmongo->find("user_info",array("iopenid"=>array('$ne'=>"")),array("user_id"=>1,"iopenid"=>1));
		}else{
			$user_info=$this->phpmongo->find("user_info",array("iopenid"=>$iopenid),array("user_id"=>1,"iopenid"=>1));
		}
		foreach($user_info as $key=>$value)
		{
			$invite_user_list[]=$value["user_id"];
			$invite_list[]=$value["iopenid"];
			$tmptime[$value["user_id"]]=$value["createtime"];
		}
		
		$invite_list=$this->invitelist($invite_list);
		
		$user_base=$this->phpmongo->find("user_info",array("user_name"=>array('$in'=>$invite_list)),array("id"=>1));
		
		$user_info=$this->phpmongo->find("user_hero",array("user"=>array('$in'=>$invite_user_list),"main"=>1),array("user_id"=>1,"name"=>1));
		$il=array();
		foreach($user_info as $key=>$value)
		{
			$il[$value["user_id"]]=array($value["name"],$tmptime[$value["user_id"]],$invite_list[$value["user_id"]]);
		}
		return $il;
	}
	
	function user_active_count($data)
	{
		if(isset($data["time1"]) and $data["time1"]){
			$time1=strtotime($data["time1"]." 00:00:00");
			$time2=strtotime($data["time2"]." 23:59:59");
			$q=array(
					"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
					"money" =>array('$gt'=>0)
				);
				
				$q1=array(
					"createtime"=>array('$gt'=>$time1,'$lt'=>$time2),
					"money" =>0,
				);
			
		}else{
			return false;
		}
	}
	
		//玩家等级分布
	function user_lv($obj){
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
		$r = $this->phpmongo->new_aggregate("user_info",array("lv"),false,array("user_id"),$q, false, array('today'=>-1), 0, 0, 1);

		$run = array();
		foreach($r as $k => $value){
			$value["today"]=$value["_id"]["today"];
			$run[] = $value;
		}
		return $run;
	}
	
	function getChannel(){
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$rsl = $tmp_mongo->find("channel")->sort(array("id"=>-1));
		$c = array();
		foreach($rsl as $k => $value){
			$c[] = $value;
		}
		return $c;
	}
	
	function getPlatforms(){
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$rsl = $tmp_mongo->findOne("operations_info");
		return $rsl["platforms"];
	}
	
	
}