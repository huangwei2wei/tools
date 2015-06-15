<?php 
namespace plugins\macro_data;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class imacro_data extends module
{	

	//用户数据修改
	function query_user_info($table,$nickname,$user_name)
	{
		if($user_name)
		{
			$user_base=$this->phpmongo->findone("user_base",array("user_name"=>$user_name));

			$user_info=$this->phpmongo->findone("user_info",array("base_user_id"=>$user_base["id"]));

			$user_id=$user_info["user_id"];
		}else{
			$dbfield_note_ext=$dbfield_notearr=array();
			$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
			$user_id=$user_info["user_id"];
		}
			
		if($table=="user_info")
		{
			if($user_id)
			{
				$data=$this->mongo->user_info($user_id);	
				$dbfield_note=dbfield_note("base_user_data");
				$dbfield_note_ext=dbfield_note("base_user_ext");
			}
		}elseif($table=="user_ext_ft" || $table=="user_task" || $table=="login_user"){
			$data=$this->phpmongo->findone($table,array("user_id"=>$user_id));
			$dbfield_note=dbfield_note($table);
		}else{
			$data=$this->phpmongo->findone($table,array("id"=>intval($nickname)));
			$dbfield_note=dbfield_note($table);
		}		
		if($dbfield_note)
		{
			foreach($dbfield_note as $key=>$value)
			{
				if($value["COLUMN_COMMENT"])
				{
					$dbfield_notearr[$value["COLUMN_NAME"]]=$value["COLUMN_COMMENT"];
				}
			}
		}		
		if(isset($dbfield_note_ext) && $dbfield_note_ext)
		{
			foreach($dbfield_note_ext as $key=>$value)
			{
				if($value["COLUMN_COMMENT"])
				{
					$dbfield_notearr[$value["COLUMN_NAME"]]=$value["COLUMN_COMMENT"];
				}
			}
		}		
		return array($data,$dbfield_notearr);
	}	
	//更新用户信息
	function update_user_info($table,$user_id,$arr)   
	{
		$str=new STRING;
		$fenleiarr=array(array(),array());
		foreach($arr as $key=>$value)
		{
			if(substr($key, strlen($key)-1,1)=="_")
			{
				$fenleiarr[1][$key]=$value;
			}else{
				$fenleiarr[0][$key]=$value;
			}
		}
		$newarr=array();
		
		
		
		
		foreach($fenleiarr[0] as $key=>$value)
		{
			if($value!=$fenleiarr[1][$key."_"])
			{
				if(substr($key, 0,4)=="ext_")
				{
					$newarr["ext"][str_replace("ext_", "",$key)]=$value;
				}else{
					$newarr[$key]=$value;
				}
			}
		}
		
		foreach($newarr as $key=>$value){
			if(is_array($value)){
				foreach($value as $k=>$v){
					$newarr[$key.".".$k]=$v;
				}
			}			
		}
		
		foreach($newarr as $key=>$value){
			if(is_array($value)){
				unset($newarr[$key]);
			}			
		}

 		$this->phpmongo->update($table,$newarr,array("user_id"=>intval($user_id)));		
	}
	//*************************************用户实时表**************************//

	//更新用户信息
	function update_user_ext_ft($table,$user_id,$arr)   
	{
		$str=new STRING;
		$newarr=array();
		foreach($arr as $key=>$value)
		{
			if(substr($key, 0,4)=="ext_")
			{
				$newarr["ext"][str_replace("ext_", "",$key)]=$value;
			}else{
				$newarr[$key]=$value;
			}
		}
		if($table=="user_info")
		{
			$user_type=$str->tabletype("user");
		}else{
			$user_type=$str->tabletype($table);
		}
		foreach($newarr as $key=>$value)
		{
			if(is_array($value))
			{
				$user_type_ext=$str->tabletype("user_ext");
				foreach($value as $k=>$v)
				{
					$newarr[$key][$k]=$str->typeto($user_type_ext[$k],$v);
				}
			}else{
				$newarr[$key]=$str->typeto($user_type[$key],$value);
			}
		}
		if(isset($newarr["user_id"]) && $newarr["user_id"])
		{
			$newarr["user_id"]=intval($newarr["user_id"]);
		}
		
		if($table=="user_info" || $table=="user_ext_ft" || $table=="user_task")
		{
			$query=array("user_id"=>intval($user_id));
		}else{
			$query=array("id"=>intval($newarr["id"]));
		}
		$newarr["user_id"]=intval($newarr["user_id"]);
		$this->phpmongo->update($table,$newarr,array("user_id"=>intval($user_id)));
		 echo "<script>alert('更新完成！');</script>";
   }
//*************************************禁言用户************************************//	
	//禁言列表
	function gag_list()
	{
		$gag_list=array();
		$c_gag=restoarray($this->phpmongo->find("c_gag"));
		if($c_gag)
		{
			foreach($c_gag as $value)
			{
				$value["stime"]=$this->timecs($value["time2"]);
				if($value["stime"]==false)
				{
					$this->phpmongo->delete("c_gag",array("user_id"=>$value["user_id"]));
					continue;
				}				
				$user_info=$this->mongo->user_info($value["user_id"]);
				$user_hero=$this->phpmongo->findone("user_info",array("user_id"=>$value["user_id"]));
				$value["nickname"]=$user_hero["nickname"];
				$value["user_grade"]=$user_hero["user_lv"];
				$value["online"]=$user_info["online"];
				$gag_list[]=$value;
			}
		}
		return $gag_list;
	}	
	function timecs($stime)
	{
		$newtime=$stime-time();
		if($newtime<=0)
		{//禁言过期
			return false;
		}		
		if($newtime/31536000>1)
		{
			return "永久";//超过1年的就永久
		}		
		if($newtime/2678400>1)
		{
			return round($newtime/2678400,1)."个月";
		}		
		if($newtime/86400>1)
		{
			return round($newtime/86400,1)."天";
		}		
		if($newtime/3600>1)
		{
			return round($newtime/3600,1)."小时";
		}
	}	
	//禁言用户
	function add_gag($arr)
	{
		$err=array();
		if(isset($arr["user_name"]))
		{
			$arr["user_name"]=str_replace("；", ";", $arr["user_name"]);
			$uname=explode(";",$arr["user_name"]);
			$time=$arr["time"];
			foreach($uname as $value)
			{
				
				$tmpu=$this->phpmongo->findone("user_info",array("nickname"=>trim($value)),array("user_id"=>true));
//				$tmpu=$this->phpmongo->findone("user_info",array("nickname"=>trim($value)),array("user_id"=>true));
				if($tmpu)
				{
					if(!$time)
					{
						$this->dele_gag(intval($tmpu["user_id"]));
					}else{
						$addarr=array(
								"user_id"=>intval($tmpu["user_id"]),
								"time1"=>time(),
								"time2"=>time()+intval($time),
						);
						$this->phpmongo->insert("c_gag",$addarr);
					}
				}else{
					
					$err[]=trim($value);
				}
			}
			$mongoname = "s_" . $_SESSION["operators_id"] ."_" . $_SESSION["server_id"];
			$this->phpmongo->selectdb("all_user");
			$this->phpmongo->selecttable("server_list");
			$this->phpmongo->update("server_list",array( "gag"=>1),  array("mongoname"=>$mongoname));
		}
		return $err;
	}
	
	//删除禁言用户
	function dele_gag($user_id)
	{
		$this->phpmongo->delete("c_gag", array("user_id"=>intval($user_id)));
	}

	//读取用户信息
	function ruserinfo($mongo,$nickname,$ydata)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
		
		$list['user_id']=$user_info["id"];
		$list['nickname'] =$user_info['nickname'];
		$list['user_grade'] =$user_info['user_grade'];
		$list['ingroup'] =$ingroup;
		$list['time'] =$ydata[0];
		$list['stime'] =abs((time()-($ydata[1]+($ydata[0]*3600)))/3600);
		$list['found_day'] =round((time()-$user_info['createtime'])/(60*60*24));
		
		if($this->logact($nickname,3))	
		{
			$list['action'] =1; //1未禁言 2禁言,
		}else{
			$list['action'] =2; //1未禁言 2禁言,
		}
	
		if($user_info["online"]==1)
		{
			$list['is_line'] =1; //在线
		}else{
			$list['is_line'] =0;
		}
		
		return $list;
	}
//*************************************词语过滤************************************//
	//屏蔽关键字
	function key_list($post)
	{
		$n=array();
		if($post)
		{
			$key=str_replace(";", ";",$post["key"]);
			$key=explode(";",$key);
			if($key)
			{
				foreach($key as $k=>$v)
				{
					$info_arr=$this->phpmongo->findone("c_key",array("key"=>$v));
					if(!$info_arr)
					{
						$addarr=array(
						"key"=>$v,
						"replace"=>$post["replace"],
						);
						$this->phpmongo->insert("c_key",$addarr);
					}
				}
			}
		}
		$info_arr=restoarray($this->phpmongo->find("c_key"));
		
		if($info_arr)
		{
			foreach($info_arr as $value)
			{
				$n[]=$value;
			}
		}
			
		return $n;
	}	
    //解除封鎖key
	function del_key($ip)
	{
		$this->phpmongo->delete("c_key",array("key"=>$ip),true);
	}
	
	
	//邮件列表 type 1系统邮件 2玩家邮件 3运营奖励 4GM邮件 5 玩家建议邮件
	function email_list($type=5,$nickname,$sTime,$eTime){
		$user_id = 0;
		if(strlen(trim($nickname)) > 0){
			$user_info = $this->phpmongo->findone("user_info",array("nickname"=>$nickname));
			if(!$user_info){
				echo "无此玩家！！";
				return;
			}
			$user_id = $user_info["user_id"];
		}
		
		$query = array();
		if(!$type)$type=5;
		if($type){
			$query["type"] = $type;
		}
		if($type != 5 && $user_id > 0){
			$query["get_id"] = $user_id;
		}
		if($type==5 && $user_id > 0){
			$query["send_id"] = $user_id;
		}
		$query["send_time"] = array('$gt'=>intval($sTime),'$lt'=>intval($eTime));
		$e = array();
		$email_arr = restoarray($this->phpmongo->find("message",$query));
		if($email_arr){
			foreach($email_arr as $value){
				unset( $value["_id"]);
				$value["stop_time"]=$this->timecs($value["time"]);
				if($value["get_id"] >0){
					$user_info = $this->phpmongo->findone("user_info",array("user_id"=>intval($value["get_id"])));
					$value["nickName"] = $user_info["nickname"];
				}
				if($value["send_id"] > 0){
					$user_info = $this->phpmongo->findone("user_info",array("user_id"=>intval($value["send_id"])));
					$value["sendnickName"] = $user_info["nickname"];
				}
				$e[] = $value;
			}
		}
		return $e;
	}
	
	function send_email($type,$playerId,$title){
		$adddata=array( 'type'=>5,
						'send_id'=>0,
						'get_id'=>int($playerId),
						'title'=>$title,
						'info'=>'',
						'text'=>'',
						'send_time'=>time(),
						'ip_from'=>"0.0.0.0",
						'read_state'=>0,
						"module_sub_id"=>0,
					);
					
		$this->phpmongo->insert("message",$adddata);
		print("发送成功");
	}
	
	
//*************************************禁用户ip************************************//
        //ip列表

		function iplist()
		{
			$iplist=array();
			$c_ip=restoarray($this->phpmongo->find("c_ip"));
			
			if($c_ip)
			{
				foreach($c_ip as $value)
				{
						$iplist[]=$value;
				}
			}
			return $iplist;
		}

	//添加屏蔽IP
	function add_ip($arr)
	{		
		$c_ip=restoarray($this->phpmongo->find("c_ip",array("ip"=>intval(ip2int($arr["ip"])))));
		if($c_ip)
		{
			return false;
		}
		if($arr["time"]==99999999999){
			$addarr=array(
				"ip"=>intval(ip2int($arr["ip"])),
				"time"=>0,
			);
		}else{
			$addarr=array(
				"ip"=>intval(ip2int($arr["ip"])),
				"time"=>intval(time()+$arr["time"]),
			);

		}
		$this->phpmongo->insert("c_ip",$addarr);
		return true;
	}

    //解除封鎖IP
	function del_ip($ip)
	{
		$this->phpmongo->delete("c_ip",array("ip"=>intval($ip)));
	}
	
    function del1_ip($ip1)
	  {

		$onarr=$this->phpmongo->delete("c_ip");

		return $onarr;
	  }

	
  //***********************************公告提示**************************************//
	
	//添加
	function addnotice($arr)
	{
		
		$addarr=array(
				"id"=>max_id($this->mongo,"c_notice"),
		        "type"=>strval($arr["type"]),
				"interval"=>intval($arr["interval"]),
				"up_time"=>time(),
				"time1"=>intval(strtotime($arr["time1"])),
				"time2"=>intval(strtotime($arr["time2"])),
				"info"=>$arr["info"],
		);

		$this->phpmongo->insert("c_notice",$addarr);
	}
	
	//公告列表
	function notice_list($id=0)
	{
		$qu=array();
		if($id)
		{
			$qu=array("id"=>intval($id));
		}
	    $info=array();
	    $info_arr=restoarray($this->phpmongo->find("c_notice",$qu));
		foreach((array)$info_arr as $key=>$value)
		{
			if($value)
			{
				$info[]=array(
					"id"=>$value["id"],
					"type"=>$value["type"],
					"interval"=>$value["interval"],
					"time1"=>date("Y-m-d H:i:s",$value["time1"]),
					"time2"=>date("Y-m-d H:i:s",$value["time2"]),
					"info"=>$value["info"],
				);
			}
		}
		return $info;
	}
	//编辑
	function edit_notice($post)
	{	
		$fields=array(
				"type"=>strval($post["type"]),
				"interval"=>intval($post["interval"]),
				"time1"=>intval(strtotime($post["time1"])),
				"time2"=>intval(strtotime($post["time2"])),
				"info"=>$post["info"],
			);
		$this->phpmongo->update("c_notice",$fields,array("id"=>intval($post["id"])));
	}
	
	//删除
	function del_notice($get)
	{
		$this->phpmongo->delete("c_notice",array("id"=>intval($get["id"])));
	}

   //*******************************封用户ID号******************************************//

    //被封ID列表

	function id_list()
	{
		$idlist=array();
		$c_id=restoarray($this->phpmongo->find("user_base",array("seal"=>array('$ne'=>0))));

		foreach($c_id as $value)
		{
			if($value["seal"]!=-1)
			{
				$time2=$value["seal"];
				if(time()-$time2>0)
				{
					$this->phpmongo->update("user_base",array("seal"=>0),array("user_id"=>$value["user_id"]));
					continue;
				}
				
				$value["stop_time"]=$this->timecs($time2);
			}else{
				$value["stop_time"]="永久";
			}
			
			$user_info=$this->phpmongo->findone("user_info",array("base_user_id"=>$value["id"]),array("nickname"=>1,"user_lv"=>1,"user_id"=>1,"money"=>1));
			
			$value["nickname"]=$user_info["nickname"];
			$value["user_grade"]=$user_info["user_lv"];
			$value["user_id"]=$user_info["user_id"];
			$value["money"]=$user_info["money"];
			$id_list[]=$value;
		}

		return $id_list;
	}
	
	function add_id($arr)
	{
		$err=array();
		if(isset($arr["user_name"]))
		{
			$arr["user_name"]=str_replace("；", ";", $arr["user_name"]);
			$uname=explode(";",$arr["user_name"]);
			$time=intval($arr["time"]);
			foreach($uname as $key=>$value)
			{
				$tmp=$this->phpmongo->findone("user_info",array("nickname"=>$value),array("base_user_id"=>1));
				if($tmp)
				{      
					if($time==-1)
					{
						$this->phpmongo->update("user_base", array("seal"=>-1), array("id"=>intval($tmp["base_user_id"])));
					}else{
						$this->phpmongo->update("user_base", array("seal"=>time()+intval($time)), array("id"=>intval($tmp["base_user_id"])));
					}

				}else{
					
					$err[]=trim($value);
				}
			}
		}
		return $err;
	}
	
	
	//删除禁言用户
	function open_id($base_user_id)
	{
		//$this->phpmongo->delete("c_gag", array("user_id"=>intval($user_id)));
		$this->phpmongo->update("user_base", array("seal"=>0), array("id"=>intval($base_user_id)));
	}
	
	function deleteuser_act($nickname)
	{
		//user_task user_info user_hero user_goods user_base equip
		
//		$user_hero=$this->phpmongo->findone("user_hero",array("name"=>$nickname,"main"=>1));
//		$user_id=$user_hero["user_id"];
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
		
		$this->phpmongo->delete("user_task",array("user_id"=>intval($user_id)));
		$this->phpmongo->delete("user_info",array("user_id"=>intval($user_id)));
		$this->phpmongo->delete("user_hero",array("user_id"=>intval($user_id)));
		$this->phpmongo->delete("user_goods",array("user_id"=>intval($user_id)));
		$this->phpmongo->delete("user_base",array("id"=>intval($user_info["base_user_id"])));
		$this->phpmongo->delete("equip",array("user_id"=>intval($user_id)));
	}
	
	function querynickname($nickname)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
//		$user_id=$user_hero["user_id"];
//		$user_info=$this->phpmongo->findone("user_info",array("user_id"=>intval($user_id)));
		$user_base=$this->phpmongo->findone("user_base",array("id"=>intval($user_info["base_user_id"])));
//		var_dump($user_base);
		return $user_base["user_name"];
	}
	
	function huodong()
	{
		return array(dbarr("SELECT * FROM `base4_operators_activity` WHERE sys=".$_SESSION["server_id"]." ORDER BY `condition_type` ASC"),dbarr("SELECT a.* FROM `base4_operators_activity_info` a inner join  `base4_operators_activity` b on b.id=a.oid WHERE b.sys=".$_SESSION["server_id"]." ORDER BY `a`.`id` ASC"));
	}
	
	
	function mongohuodong()
	{
		$operators_activity=$this->phpmongo->find("operators_activity");
		$oalist=array();
		foreach($operators_activity as $key=>$value)
		{
			$oalist[$value["oid"]]=$value["status"];
		}
		return $oalist;
	}
	
	
	function huodong_act($field, $value,$id)
	{
		if(intval($id)==0)
		{
			dbinsert("base4_operators_activity", $field, $value);
		}else{
			dbupdate("base4_operators_activity", $field, $value,"id =".$id);
		}
	}
	
	function huodong_act_x($field, $status,$id)
	{
		$user_base=$this->phpmongo->findone("operators_activity",array("oid"=>intval($id)));
		$base4_operators_activity=dbarr("SELECT * FROM `base4_operators_activity` WHERE id =".$id);
		$base4_operators_activity_info=dbarr("SELECT * FROM `base4_operators_activity_info` WHERE oid =".$id);
		if($base4_operators_activity_info==false)
		{
			return false;	
		}
		$info=array();
		foreach($base4_operators_activity_info as $key=>$value)
		{
			$info[]=array(
			"id"=>intval($value["id"]),
			"oid"=>intval($value["oid"]),
			"condition"=>intval($value["condition"]),
			"condition_info"=>$value["condition_info"],
			"award_goods_ext_id"=>$value["award_goods_ext_id"],
			"award_res"=>$value["award_res"],
			);
		}
		
		
		$insdata=array(
				"oid"=>intval($id),
				"title"=>$base4_operators_activity[0]["title"],
				"condition_type"=>intval($base4_operators_activity[0]["condition_type"]),
				"cycle"=>intval($base4_operators_activity[0]["cycle"]),
				"activity_time1"=>intval($base4_operators_activity[0]["activity_time1"]),
				"activity_time2"=>intval($base4_operators_activity[0]["activity_time2"]),
				"user_lv"=>intval($base4_operators_activity[0]["user_lv"]),
				"award_time1"=>intval($base4_operators_activity[0]["award_time1"]),
				"award_time2"=>intval($base4_operators_activity[0]["award_time2"]),
				"status"=>intval($status),
				"info"=>$base4_operators_activity[0]["info"],
				"list"=>$info,
		);
		

		if(!$user_base){
			$this->phpmongo->insert("operators_activity",$insdata);
		}else{
			$this->phpmongo->update("operators_activity",$insdata,array("oid"=>intval($id)));
		}
	}

	function user_goods_list_act($nickname,$goods_name)
	{
			$tmp=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
			
			$query=array("user_id"=>intval($tmp["user_id"]));
			if($goods_name)
			{
				$query["name"]=$goods_name;
			}
			return $this->phpmongo->find("user_goods",$query);
	}
	
	function huodongadd_act($field,$value)
	{
		dbinsert("base4_operators_activity_info", $field, $value);
	}
	
	function huodongdel($id)
	{
		sql("delete from  base4_operators_activity_info where id=".$id);
	}
	
	function gameupdatelist()
	{
		return dbarr("SELECT * FROM  `base_updatelog` WHERE 1 ORDER BY  `date` desc");
	}
	
	function updateinfo($id)
	{
		$bu=dbarr("SELECT * FROM  `base_updatelog` WHERE id=$id");
		return $bu[0];
	}
	
	function addactgameupdate($data)
	{
		if($data["id"]>0)
		{
			$field=array("date","title","info","img","imgline");
			$value=array(intval(strtotime($data["date"])),$data["title"],$data["info"],$data["img"],$data["imgline"]);
			dbupdate("base_updatelog", $field, $value,"id =".$data["id"]);
		}else{
			$field=array("date","title","info","img","imgline");
			$value=array(intval(strtotime($data["date"])),$data["title"],$data["info"],$data["img"],$data["imgline"]);
			dbinsert("base_updatelog", $field, $value);
		}
	}
	
	function query_info($post)
	{
		$name=$post["name"];
		$name=str_replace("，",",",$name);
		$name=split(",",trim($name));
		$n="";
		foreach($name as $key=>$value){
			$n.="'".$value."',";
		}
		
		$hero_base_id=array();
		$hero_base_name=array();
		$base1_hero=dbarr("SELECT * FROM `base1_hero` WHERE `name` in (".substr($n,0,-1).")");
		foreach($base1_hero as $value){
			$hero_base_id[]=intval($value["id"]);
			$hero_base_name[$value["id"]]=$value["name"];
		}
		
		$query=array("hero_base_id"=>array('$in'=>$hero_base_id));
		if(intval(strtotime($post["time"]))){
			$query["time"]=array('$lt'=>intval(strtotime($post["time"])));
		}

		$user_hero=$this->phpmongo->find("user_hero",$query);
		$user_idlist=array();
		$user_id=array();
		foreach($user_hero as $key=>$value)
		{
			if(isset($user_idlist[$value["user_id"]])==false)
			{
				$user_idlist[$value["user_id"]]=array();
			}
			$user_idlist[$value["user_id"]][]=array($hero_base_name[$value["hero_base_id"]],date("Y-m-d",$value["time"]));
			
			if(in_array($value["user_id"],$user_id)==false)
			{
				$user_id[]=$value["user_id"];
			}
		}
		$user_info=$this->phpmongo->find("user_info",array("user_id"=>array('$in'=>$user_id)));
		
		$user_hero3=array();
		foreach($user_info as $key=>$value)
		{
			$user_hero3[$value["user_id"]]=array($value["nickname"],$user_idlist[$value["user_id"]]);	
		}
		return $user_hero3;
		
	}
	
	function richanghuodong()
	{
		$base4_activity=dbarr("SELECT * FROM `base4_activity` WHERE 1");
		$sysconfig=$this->phpmongo->findone("sysconfig",array("id"=>1));
		if($sysconfig==false)
		{
			$sysconfig=array();	
		}
		return array($base4_activity,$sysconfig);
	}
	
	function signhuodong()
	{
		$base_sign = dbarr("SELECT * FROM `base_sign` order by id ASC");
		return $base_sign ;
	}
	function upsignhuodong($isAll = false){
		$base_sign = $this->signhuodong();
		if($isAll){
			$this->phpmongo->selectdb("all_user");
			$services = $this->phpmongo->find("server_list");
			foreach ($services as $service){
				$mongoname = $service['mongoname'];
				$this->phpmongo->selectdb($mongoname);
				$this->phpmongo->drop("base_sign");
				foreach ($base_sign as $k=>$v){
					$v["id"] = intval($v["id"]);
					$v["month"] = intval($v["month"]);
					$v["vip_lv"] = intval($v["vip_lv"]);
					$v["is_show"] = intval($v["is_show"]);
					$this->phpmongo->insert("base_sign",$v);
				}
			}
		}else{
			$this->phpmongo->drop("base_sign");
			foreach ($base_sign as $k=>$v){
				$v["id"] = intval($v["id"]);
				$v["month"] = intval($v["month"]);
				$v["vip_lv"] = intval($v["vip_lv"]);
				$v["is_show"] = intval($v["is_show"]);
				$this->phpmongo->insert("base_sign",$v);
			}
		}
	}
	
	function richanghuodong_up($id,$e)
	{
		$sysconfig=$this->phpmongo->findone("sysconfig",array("id"=>1));
		if(isset($sysconfig["double_activities"])==false)
		{
			$sysconfig["double_activities"]=array();
		}
		$sysconfig["double_activities"][$id.""]=$e;
		$this->phpmongo->update("sysconfig",array("double_activities"=>$sysconfig["double_activities"]),array("id"=>1));
	}
	
	
	function user_count_money($openid)
	{
		$initial = array("items" => array("money"=>1),"index"=>-1,"total"=>0);
		$reduce = "function (obj, prev) {prev.total+=obj.money;}";
		$gkeys = array("openid"=>1);
		$query2=array("openid"=>$openid);
		$this->phpmongo->selecttable("log_pay");		
		$countdata2 = $this->phpmongo->table->group($gkeys, $initial, $reduce,$query2);	
		return $countdata2["retval"];
	}
	
	function query_destiny($name)
	{
			$user_hero2=$this->phpmongo->findone("user_hero",array("name"=>$name));
			
			$user_info=$this->phpmongo->findone("user_info",array("user_id"=>intval($user_hero2["user_id"])),array("base_user_id"=>1));
			$user_base=$this->phpmongo->findone("user_base",array("id"=>intval($user_info["base_user_id"])));
			$user_goods=$this->phpmongo->find("user_goods",array("user_id"=>intval($user_hero2["user_id"]),"goods_type"=>6));
			$goods=array();
			foreach($user_goods as $value)
			{
				$goods[]=$value;
			}
			$money=$this->user_count_money($user_base["user_name"]);
			return array("destiny"=>$goods,"regname"=>$user_base["user_name"],"lv"=>$user_hero2["lv"],"money"=>$money[0]["total"]);
	}
	
	function hero_log($name,$time1=false,$time2=false)
	{
		if($name){
			$name=str_replace("，",",",$name);
			$name_arr=explode(",",$name);
			
			$user_info=$this->phpmongo->find("user_info",array("nickname"=>array('$in'=>$name_arr)));
			$user_hero_arr=array();
			$herousername=array();
			foreach($user_info as $value)
			{
				$user_hero_arr[]=intval($value["user_id"]);
				$herousername[$value["user_id"]]=$value["nickname"];
			}

			$user_hero_log=$this->phpmongo->find("log_user_hero",array("user_id"=>array('$in'=>$user_hero_arr)));
		}elseif($time1 and $time2){
			$user_hero_log=$this->phpmongo->find("log_user_hero",array("time"=>array('$gte'=>intval(strtotime($time1)),'$lte'=>intval(strtotime($time2)))));
		}elseif($time1){
			$user_hero_log=$this->phpmongo->find("log_user_hero",array("time"=>array('$gte'=>intval(strtotime($time1)))));
		}else{
			return array();	
		}
		$runhero=array();
		$sqlid="";
		$uid=array();
		foreach($user_hero_log as $value)
		{
			$runhero[]=$value;
			$sqlid.=$value["hero_ext_id"].",";
			$uid[]=$value["user_id"];
		}
		
		$newsqldata=array();
		if($sqlid){
			$sqldata=dbarr( "SELECT b.name,a.hero_ext_id FROM `base1_hero_ext` as a inner join `base1_hero` as b on a.`hero_base_id`=b.id WHERE a.`hero_ext_id` in (".substr($sqlid,0,-1).")");
			foreach($sqldata as $value)
			{
				$newsqldata[$value["hero_ext_id"]]=$value;
			}
		}
		
		if(isset($herousername)==false)
		{
			$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$uid)));
			$herousername=array();
			foreach($user_hero as $value)
			{
				$herousername[$value["user_id"]]=$value["name"];
			}
		}
		
		foreach($runhero as $key=>$value)
		{
			$runhero[$key]["nickname"]=$newsqldata[$value["hero_ext_id"]]["name"];
			$runhero[$key]["user_name"]=$herousername[$value["user_id"]];
		}
		
		return $runhero;
	}
	
	function equip_gem_list($nickname)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));
		$equip=$this->phpmongo->find("equip",array("user_id"=>intval($user_hero["user_id"])));
		$goods_user_id=array();
		foreach($equip as $value)
		{
			if(intval($value["equip1"])>0)$goods_user_id[]=	intval($value["equip1"]);
			if(intval($value["equip2"])>0)$goods_user_id[]=	intval($value["equip2"]);
			if(intval($value["equip3"])>0)$goods_user_id[]=	intval($value["equip3"]);
			if(intval($value["equip4"])>0)$goods_user_id[]=	intval($value["equip4"]);
			if(intval($value["equip5"])>0)$goods_user_id[]=	intval($value["equip5"]);
			if(intval($value["equip6"])>0)$goods_user_id[]=	intval($value["equip6"]);
			if(intval($value["equip7"])>0)$goods_user_id[]=	intval($value["equip7"]);
		}
		$user_goods=$this->phpmongo->find("user_goods",array("goods_user_id"=>array('$in'=>$goods_user_id)));
		$goods_gem_id=array();
		$goodsarr=array();
		foreach($user_goods as $value)
		{
			$goodsarr[]=$value;
			$gem_ids=arrayed($value["gem_ids"]);
			foreach($gem_ids as $k=>$v)
			{
				$gem_idsarr=explode("@",$v[1]);
				if(count($gem_idsarr)==2)
				{
					$goods_gem_id[]=intval($gem_idsarr[0]);
				}
			}
		}

		$user_goods_gem=$this->phpmongo->find("user_goods_gem",array("goods_gem_id"=>array('$in'=>$goods_gem_id)));
		
		$gem=array();
		foreach($user_goods_gem as $value)
		{
			$base_goods=dbarr("SELECT * FROM `base_goods` WHERE `id`=".intval($value["goods_id"]));
			$gem[$value["goods_gem_id"]]=1;
		}
		
		foreach($goodsarr as $value)
		{
			$isup=0;
			$gem_ids=arrayed($value["gem_ids"]);
			foreach($gem_ids as $k=>$v)
			{
				$varr=explode("@",$v[1]);
				if(count($varr)==2)
				{
					if(isset($gem[intval($varr[0])])==false)
					{
						$isup=1;
						print("删除:".$varr[0]."宝石<br>");
						$gem_ids[$k][1]=0;
					}
				}
			}
			if($isup){
				$this->phpmongo->update("user_goods",array("gem_ids"=>stred($gem_ids)),array("goods_user_id"=>intval($value["goods_user_id"])));
			}
		}
	}
	
	function roll_clothes()
	{
		//总创建人数
		$z_reg_count=$this->phpmongo->find("user_info")->count();
		//滚服创建人数
		$g_reg_count=$this->phpmongo->find("user_info",array("old_server"=>array('$gt'=>0)))->count();
		
		
		$pay_log=$this->phpmongo->find("log_pay",array(),array("openid"=>1,"money"=>1)); 
		$npay=array();
		$openid=array();
		foreach($pay_log as $value)
		{
			if(isset($npay[$value["openid"]]))
			{
				$npay[$value["openid"]]+=$value["money"];
			}else{
				$npay[$value["openid"]]=$value["money"];
			}
			
		}

		//总充值人数
		$z_pay_count=count($npay);
		
		$tmp_zc=0;
		foreach($npay as $key=>$value)
		{
			$openid[]=$key;
			$tmp_zc+=$value;
		}
		
		//总充值额
		$zc_pay_count=$tmp_zc;
		
		//滚服充值人数
		$user_base=$this->phpmongo->find("user_base",array("user_name"=>array('$in'=>$openid)),array("id"=>1)); 
		$base_user_id=array();
		foreach($user_base as $value)
		{
			$base_user_id[]=$value["id"];
		}
		
		$user_info=$this->phpmongo->find("user_info",array("base_user_id"=>array('$in'=>$base_user_id),"old_server"=>array('$gt'=>0)),array("base_user_id"=>1)); 
		$g_pay_count=$user_info->count();
		
		
		//滚服充值额
		$g_base_user_id=array();
		foreach($user_info as $value)
		{
			$g_base_user_id[]=$value["base_user_id"];
		}

		$n_user_base=$this->phpmongo->find("user_base",array("id"=>array('$in'=>$g_base_user_id)),array("user_name"=>1)); 
		$n_open_id=array();
		foreach($n_user_base as $value)
		{
			$n_open_id[]=$value["user_name"];
		}
		
		$tmp_gc=0;
		foreach($npay as $key=>$value)
		{
			if(in_array($key,$n_open_id))
			{
				$tmp_gc+=$value;
			}
		}
		$gc_pay_count=$tmp_gc;
		return array($z_reg_count,$g_reg_count,$z_pay_count,$g_pay_count,$zc_pay_count,$gc_pay_count);
	}
	
	function inithuodong()
	{
		$this->phpmongo->delete("operators_activity"); 
		$this->phpmongo->update("user_info",array("ext.operators_activity"=>""),array()); 
		
		$base4_operators_activity=dbarr("SELECT * FROM `base4_operators_activity` WHERE sys=".$_SESSION["server_id"]);
		
		foreach($base4_operators_activity as $value){
			$this->huodong_act_x("status", 1,$value["id"]);	
		}
		$this->phpmongo->delete("message",array("type"=>7)); 
	}
	
	function up01huodong($get)
	{
		dbarr("UPDATE `base4_operators_activity_info` SET `".$get["filed"]."` = '".$get["data"]."' WHERE `".$get["query_filed"]."` = ".$get["query_value"].""); 
	}
	
	function up02huodong($get)
	{
		dbarr("UPDATE `base4_operators_activity` SET `".$get["filed"]."` = '".$get["data"]."' WHERE `".$get["query_filed"]."` = ".$get["query_value"].""); 
	}
	
	function delhuodong($id)
	{
		$base4_operators_activity=dbarr("SELECT * FROM `base4_operators_activity` WHERE id=".$id);
		dbarr("DELETE FROM `base4_operators_activity` WHERE `id` =".$id); 
		dbarr("DELETE FROM `base4_operators_activity_info` WHERE `oid` =".$id); 
		$this->phpmongo->delete("operators_activity",array("oid"=>intval($id))); 
	}
	
	function importsyshuodong()
	{
		$arr1=dbarr("SELECT * FROM `base4_operators_activity` WHERE sys=0 ORDER BY `condition_type` ASC");
		$arr2=dbarr("SELECT a.* FROM `base4_operators_activity_info` a inner join  `base4_operators_activity` b on b.id=a.oid WHERE b.sys=0 ORDER BY `a`.`id` ASC");
		$base_id=array();
		foreach($arr1 as $value)
		{
			$value["sys"]=$_SESSION["server_id"];
			$f=array("sys","condition_type","cycle","activity_time1","activity_time2","user_lv","award_time1","award_time2","status","info");
			$v=array($value["sys"],$value["condition_type"],$value["cycle"],$value["activity_time1"],$value["activity_time2"],$value["user_lv"],$value["award_time1"],$value["award_time2"],$value["status"],$value["info"]);
			dbinsert("base4_operators_activity", $f, $v);
			$last_insert_id=dbarr("select last_insert_id() as id from base4_operators_activity limit 1");
			$base_id[$value["id"]]=$last_insert_id[0]["id"];
		}
		
		foreach($arr2 as $value)
		{
			$value["oid"]=$base_id[$value["oid"]];
			$f=array("oid","condition","condition_info","award_goods_ext_id","award_res");
			$v=array($value["oid"],$value["condition"],$value["condition_info"],$value["award_goods_ext_id"],$value["award_res"]);
			dbinsert("base4_operators_activity_info", $f, $v);
		}
	}
	
	function get_module()
	{
		$base_module_sub=dbarr("SELECT * FROM `base_module_sub` WHERE 1"); 	
		$module_name=array();
		foreach($base_module_sub as $value)
		{
			$module_name[$value["id"]]=$value["sub_module_name"];
		}
		return $module_name;
	} 
	
	function err_data()
	{
		$errdata=array();
		$log_err=$this->phpmongo->find("log_err");
		foreach($log_err as $value){
			$errdata[]=$value;
		}
		return $errdata;
	}
}
		