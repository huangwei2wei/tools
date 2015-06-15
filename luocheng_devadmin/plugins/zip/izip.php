<?php 
namespace plugins\zip;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\tool\dbtoxml as DBTOXML;
use kernel\base\tool\xmlfun as XMLFUN;
use kernel\base\tool\phpzip as PHPZip;
use kernel\base\base_class\api_file as AF;

class izip extends module
{
	//生成ZIP
	function b_zip()
	{
		$zip=new PHPZip();
		$zip->Zip(D_R."res/".$this->getmysqldb()."/xml/",D_R."res/".$this->getmysqldb()."/xml.zip");
		$zipa=new PHPZip();
		$zipa->Zip(D_R."res/".$this->getmysqldb()."/data/",D_R."res/".$this->getmysqldb()."/data.zip");
		
		$zipb=new PHPZip();
		$zipb->Zip(D_R."res/".$this->getmysqldb()."/pydata/",D_R."res/".$this->getmysqldb()."/pydata.zip");
		
		$zipb=new PHPZip();
		$zipb->Zip(D_R."res/".$this->getmysqldb()."/jsondata/",D_R."res/".$this->getmysqldb()."/jsondata.zip");
		
		return "<div><a href='/res/".$this->getmysqldb()."/xml.zip' >xml下载zip</a>
		<br><a href='/res/".$this->getmysqldb()."/data.zip' >data下载zip</a><br>
		<a href='/res/".$this->getmysqldb()."/pydata.zip' >pydata下载zip</a><br>
		<a href='/res/".$this->getmysqldb()."/jsondata.zip' >jsondata下载zip</div></a>";
	}
	
	//复制ZIP
	function copyzip()
	{
		$af=new AF;
		$res=$af->down("http://".ADMINWEBURL."/res/".$this->getmysqldb()."/xml.zip","/res/".$this->getmysqldb()."/config/xml.zip");
		if($res["res"])
		{
			echo "xml.zip下载成功<br>";
		}else{
			echo "xml.zip下载失败<br>";
		}
		$res=$af->down("http://".ADMINWEBURL."/res/".$this->getmysqldb()."/data.zip","/res/".$this->getmysqldb()."/config/data.zip");
		if($res["res"])
		{
			echo "data.zip下载成功<br>";
		}else{
			echo "data.zip下载失败<br>";
		}

		//发送一个解压动作
		$res=$af->unzip("/res/".$this->getmysqldb()."/config/data.zip","/res/".$this->getmysqldb()."/data/");
		if($res["res"])
		{
			echo "data.zip解压成功<br><pre>";
			print_r($res["msg"]);
		}else{
			echo "data.zip解压失败<br>";
			print_r($res["msg"]);
		}
		return D_R." copy to res/".$this->getmysqldb()."/config/xml.zip res/".$this->getmysqldb()."/config/data.zip";
	}
	
	function initialization_data($userid,$server_id)
	{
		$data=dbarr("SELECT b.platform, b.pay, b.login,b.identifier, a.operators_id, a.server_id, a.server_name,a.game_web, a.server_ip, a.server_mongo_port, a.mongo_username, a.mongo_password, a.mongo_db,a.server_mongo_ip FROM `server_list` a inner join operators b on a.operators_id=b.id  WHERE `operators_id`=$userid and `server_id`=$server_id",0,"operators");
		$data=$data[0];
		return $data;
	}
	
		//游戏初始化
	function initialization($mongo,$post,$session)
	{
					$string=new STRING;
					$af=new AF;
					
					$userid=$session["userid"];
					$server_id=$session["server_id"];
					if(!$userid || !$server_id){echo "登录失效！";exit;}
					
		
					if(!$post["isok"])
					{
						return "配置文件初始化成功";
					}
		
					$arr=array(
							"c_gag"=>"",
							 "c_id"=>"",
							 "c_ip"=>"",
							 "c_key"=>"",
							 "c_notice"=>"",
							 "equip"=>"",
							 "faction_staff"=>"",
							 "message"=>"",
							 "nickname"=>"base_nickname",
							 "personals"=>"",
							 "reg_conf"=>"",
							 "user_base"=>"",
							 "user_goods"=>"",
							 "user_goods_gem"=>"",
							 "user_hero"=>"",
							 "user_info"=>"",
							 "user_task"=>"",
							"world_boss"=>"",
							"world_boss_set"=>"",
							"log_online"=>"",
							"league"=>"",
							"league_user"=>"",
							"autoid_system"=>"",
							);
		
		foreach($arr as $key=>$value)
		{
			//删除原有的
			$mongo->mongo->delete($key);
			
			if($value)
			{
				$arr_data=$string->mongoarr($value);
				//注入新的数据
				if($arr_data)
				{
					foreach($arr_data as $v)
					{
						$mongo->mongo->insert($key,$v);
					}
				}
			}
		}
		
		return "配置文件初始化成功<br>游戏数据已经初始化完成";
	}
	//*******************************************************************************************************************************************//
	
	//配置文件config
	function inconfig($post,$data)
	{
		if($data["game_web"])
		{
			$game_web=$data["game_web"];
		}else{
			$game_web='"s'.$data["server_id"].'.qq.'.$data["platform"].'"';
		}
		
		$str='<?php
define("START_MYSQL",'.intval($post["START_MYSQL"]).');		//1启用 0不启用
define("START_MEMCACHE",'.intval($post["START_MEMCACHE"]).');		//1启用 0不启用
define("START_MONGO",'.intval($post["START_MONGO"]).');		//1启用 0不启用

//这个配置文件定义了整个游戏的基本全局常量和变量
define("REGSUM",'.intval($post["REGSUM"]).');   //注册上限人数

define("CHANNEL",'.$data["operators_id"].'); //充值渠道ID

define("SERVER",'.$data["server_id"].'); //本服务器开服ID

define("IDENTIFIER",'.var_export($data["identifier"],true).'); //运营商识别码

define("SERVICE_NAME",'.var_export($post["SERVICE_NAME"],true).');//服名称

define("SERVICE_LOCALHOST","'.$game_web.'");//WEB服务器地址

define("PLATFORM","'.$data["platform"].'");//平台地址Platform

define("SERVICE_IP","'.$data["server_ip"].'"); //WEB服务器IP

define("PT_URL_LOGIN",'.$data["login"].'); //与平台登录地址

define("RECHARGECENTRE",'.$data["pay"].'); //中心充值服务器

define("NOVICE_MAP",json_encode(array(16,17,18,19)));		//新手地图ID

define("POPU",'.intval($post["POPU"]).');//移民人口比率

define("LOG",'.intval($post["LOG"]).');//LOG==1开启日志 LOG==0关闭日志
?>';
		return $str;
	}
	
	//配置文件inconfig_storage
	function inconfig_storage($data)
	{
		$str='<?php
//数据库配置文件
$config_storage=array(
	"MONGO"=>array(
		"web"=>array("username"=>'.var_export($data["mongo_username"],true).',"password"=>'.var_export($data["mongo_password"],true).',"host"=>'.var_export($data["server_mongo_ip"],true).',"port"=>'.var_export($data["server_mongo_port"],true).',"db"=>'.var_export($data["mongo_db"],true).'),
	),
);
?>';
		return $str;
	}
	
	//配置文件config
	function inconfig_mysql($post,$data)
	{
		
	}
}
?>