<?php 
namespace plugins\timer;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class itimer extends module
{
	function object_array($array)
	{
	   if(is_object($array))
	   {
			$array = (array)$array;
	   }
	   
	   if(is_array($array))
	   {
			foreach($array as $key=>$value)
			{
			 $array[$key] = object_array($value);
			}
	   }
	   return $array;
	}

	function management()
	{
		$server_id=$_SESSION["server_id"];
		$time=intval(str_replace("-","",$post["time"]));
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		
		$tmp_mongo=new TMP_MONGO;
		$mongo_db="all_user";
        $tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],$mongo_db,$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"],  $operators[0]["mongo_auth"]);
		
		$mongo_dbk="s_".intval($_SESSION["userid"])."_".$server_id;

		$command=$tmp_mongo->find("command",array("mongoname"=>$mongo_dbk)); 
		$listcomm=array();
		foreach($command as $value)
		{
			$data=(array)json_decode($value["data"]);
			$value["fun"]=$data["func"];
			
			if($data["func"]=="boss_clearing"){
				$value["typename"]="boss结算";
			}elseif($data["func"]=="sports_clearing"){
				$value["typename"]="竞技场结算";
				
			}elseif($data["func"]=="clearing"){
				$value["typename"]="阵营战结算";
			}elseif($data["func"]=="buying_goods"){
				$value["typename"]="限时物品生成";
			}elseif($data["func"]=="start_league_war"){
				$value["typename"]="启动军团战";
			}elseif($data["func"]=="rank_operators_activity"){
				$value["typename"]="精彩活动结算";
			
			}else{
				$value["typename"]=$data["func"];
			}
			
			
			if($value["todayH"]==0){
					$value["e"]="失败";
			}else{
				if(substr($value["todayH"],0,8)==date("Ymd",time())){
					$value["e"]="成功";
				}else{
					$value["e"]="失败";
				}
			}
			
			$listcomm[]=$value;	
		}
		return $listcomm;
	}
	
	function artificial($get)
	{
		$server_id=$_SESSION["server_id"];
		$time=intval(str_replace("-","",$post["time"]));
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		
		$server_list=dbarr("SELECT * FROM  `server_list` WHERE  `operators_id` =".intval($_SESSION["userid"])." and server_id=".$server_id." ORDER BY  `server_id` DESC",0,"operators");
		$tmp_mongo=new TMP_MONGO;
		$mongo_db="all_user";
        $tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],$mongo_db,$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"],$operators[0]["mongo_auth"]);
		
		$mongo_dbk="s_".intval($_SESSION["userid"])."_".$server_id;

		$command=$tmp_mongo->findone("command",array("md5"=>$get["idmd5"])); 
		if($command){
			$data=(array)json_decode($command["data"]);
			if($data["func"]=="boss_clearing"){
				if($command["H"]==12){
					$f=array('$unset'=>array('boss10'=>1,"boss10_time"=>1));
					$q=array("md5"=>$get["idmd5"]);
					
					$this->phpmongo->update($f,$q);
				}elseif($command["H"]==16){
					
				}elseif($command["H"]==22){
					
				}
			}
		}
	}
}
?>