<?php 
namespace plugins\activation;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;
use kernel\base\base_class\mongopage as MONGOPAGE;

class iactivation extends module
{

	
	function getdata5($g_id=false,$g_name=false)
	{
		if($g_id){
	        $jihuo_ary =dbarr(	"select a.spree_id,a.cd_id,a.code,a.spree_id,a.status,b.id,b.goods_name from  base_goods  as b
	inner join base4_code as a  on b.id=a.spree_id where a.spree_id='".$g_id."'");
		}elseif($g_name){
	        $jihuo_ary =dbarr(	"select a.spree_id,a.cd_id,a.code,a.spree_id,a.status,b.id,b.goods_name from  base_goods  as b
	inner join base4_code as a  on b.id=a.spree_id where b.goods_name='".$g_name."'");
		}else{          
			$jihuo_ary =dbarr("select a.spree_id,a.cd_id,a.code,a.spree_id,a.status,b.id,b.goods_name from  base_goods  as b
	inner join base4_code as a  on b.id=a.spree_id where 1");
	    }
	    return $jihuo_ary;
	}
	
	function get_goods()
	{
			return dbarr(	"SELECT a.name,b.goods_ext_id  FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_base_id WHERE a.`goods_type` = 3");
	}
	
	function building_code($post)
	{
		if($this->phpmongo->ret_count("c_spree_list",array("spree_name"=>$post["spree_name"]))) {
			return false;
		}
		
		if(empty($post["time1"])){
			$time1=0;
			$time2=0;
		}else{
			$time1=strtotime($post["time1"].":00");
			$time2=strtotime($post["time2"].":59");
		}
			
		$arrs=array("a","b","H","c","d","A","e","J","f","M","9","F","g","G","h","Q","i","j","S","k","B","8","l","m","n","o","p","q","5","r","s","7","t","u","6","v","w","x","y","z","C","D","E","I","K","L","N","4","O","P","R","T","1","U","V","W","X","Y","Z","2","3","0");
		
		//array_rand（) 函数从数组中随机选出一个或多个元素，并返回。
		for($i=0;$i<intval($post["mymax"]);$i++)
		{
			if($post["type"]==1){
				$rand_keys = array_rand($arrs, 16);
				$strcoed="";
				for($j=0;$j<count($rand_keys);$j++)
				{
					$strcoed.=$arrs[$rand_keys[$j]];
				}
			}else{
				$strcoed="";
			}
			//dbinsert->（'表',字段数组,内容数组,1执行语句 0返回sql语句）
			//dbinsert('base4_code', array("operators_id","server_id","code","spree_name","spree_data","status"), array($_SESSION["operators_id"],$_SESSION["server_id"],$strcoed,$post["spree_name"],$post["info"],0),1);
			
			$newvalue=array(
				"spree_name"=>$post["spree_name"],
				"time1"=>$time1,
				"time2"=>$time2,
				"code"=>$strcoed,
				"type"=>$post["type"],
				"spree_data"=>$post["spree_data"],
				"status"=>0,
				"activation_user"=>0
			);
			$this->phpmongo->insert("c_spree_list",$newvalue);
		}	
		return true;
	}
	
	//显示激活码
	function get_code($page)
	{
		$info=array();					
		$newgoods=array();
		$base_goods=dbarr("SELECT a.`goods_name` as goods_name,b.goods_ext_id as goods_ext_id FROM `base_goods` as a inner join  base_goods_ext as b on a.id=b.goods_id  WHERE a.`goods_type`=3");
		foreach($base_goods as $key=>$value)
		{
			$newgoods[$value["goods_id"]]=$value["goods_name"];
		}
		
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$code_goods=$mongopage->mongodb($this->phpmongo,"code",array(),array("status"=>-1));
		$mypages=$mongopage->p(true);
		$user_id=array();
		foreach($code_goods as $key=>$value)
		{
			if($value["status"]==0){
				$value["status"]="正常";
			}else{
				$value["status"]="已使用";
				$user_id[]=intval($value["user_id"]);
			}
			
			if($value)
			{
				$info[]=array(
							"code"=>$value["code"],
							"goods_name"=>$newgoods[$value["goods_name"]],
							"spree_id"=>$value["spree_id"],
							"status"=>$value["status"],
							"user_id"=>$value["user_id"],
				);
				
			}
		}
		$hero=array();
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$user_id),"main"=>1),array("user_id"=>1,"name"=>1));
		foreach($user_hero as $value)
		{
			$hero[$value["user_id"]]=$value["name"];
		}
  		return array("page"=>$mypages,"content"=>$info,'hero'=>$hero);	
	}
	
	function down_excel()
	{
		return $this->phpmongo->find("c_spree_list", array("type"=>"1"))->sort(array("spree_name"=>1));
		//return dbarr("SELECT * FROM `base4_code` WHERE `operators_id`=".$_SESSION["operators_id"]." and `server_id`=".$_SESSION["server_id"]);	
	}
	
	function user_code_count()
	{
		$group=array("id"=>array('$min'=>'$id'),"type"=>array('$first'=>'$type'),"time1"=>array('$first'=>'$time1'),"time2"=>array('$first'=>'$time2'));
		$countkeys = array("time1","time2");
		$spree_list=$this->phpmongo->new_aggregate("c_spree_list",array("spree_name"),$group,false,array("type"=>"1"),false,false,0,0,1);
		$total_count=$this->phpmongo->aggregate("c_spree_list",array("spree_name"),array("code"),array("type"=>"1"),false,false,0,0,1);
		$used_count=$this->phpmongo->aggregate("c_spree_list",array("spree_name"),array("code"),array("type"=>"1", "status"=>1),false,false,0,0,1);
		//$total_count=$this->phpmongo->find("c_spree_list",array("type"=>"1"));
		//print_r($total_count);
		$arr = array();
		$code_arr = array();
		foreach ($total_count as $key=>$value) {
	
			$arr[$value["_id"]["spree_name"]]["spree_name"] = $value["_id"]["spree_name"];
			$arr[$value["_id"]["spree_name"]]["total_count"] = $value["code"];
			$code_arr[$value["_id"]["spree_name"]] = $arr[$value["_id"]["spree_name"]];
		}
		foreach ($used_count as $key=>$value) {
			$arr[$value["_id"]["spree_name"]]["used_count"] = $value["code"];
			$code_arr[$value["_id"]["spree_name"]] = $arr[$value["_id"]["spree_name"]];
		}
		foreach ($spree_list as $key=>$value) {
			$arr[$value["_id"]["spree_name"]]["time1"] = $value["time1"];
			$arr[$value["_id"]["spree_name"]]["time2"] = $value["time2"];
			$arr[$value["_id"]["spree_name"]]["type"] = $value["type"];
			$code_arr[$value["_id"]["spree_name"]] = $arr[$value["_id"]["spree_name"]];
			
		}
		
		return $code_arr;	
	}
	
	function code_list($name) 
	{
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$spree_list=$mongopage->mongodb($this->phpmongo,"c_spree_list",array("spree_name"=>$name),array("status"=>1));
		$mypages=$mongopage->p(true);
		$user_ids = array();
		foreach ($spree_list as $key=>$value) {
			if ($value["activation_user"]) {
				$user_ids[]=intval($value["activation_user"]);
			}
			if ($value["spree_data"]){
				$value["spree_data"] = object_to_array(json_decode($value["spree_data"]));
			//	print_r($value["spree_data"]);
			}
		}
		 
		if (count($user_ids)) {
			$name_arr=array();
			$user_info=$this->phpmongo->find("user_info",array("user_id"=>array('$in'=>$user_ids)),array("user_id"=>1,"nickname"=>1));
			foreach($user_hero as $value)
			{
				$name_arr[$value["user_id"]]=$value["nickname"];
			}
		}
		
		return array($spree_list, $mypages, $name_arr);
	}
	
	function code_delete($name) 
	{
		$count = $this->phpmongo->ret_count("c_spree_list", array("spree_name"=>$name));
		
		if ($count > 0){
			return $this->phpmongo->delete("c_spree_list",array("spree_name"=>$name));
		}else { 
		 	return false;
		}
	}
	
	function code_append($name, $num)
	{
		$info = $this->phpmongo->findone("c_spree_list", array("spree_name"=>$name));
		$spree_info = array();
		$spree_info["mymax"] = $num;
		$spree_info["spree_name"] = $name;
		$spree_info["type"] = "1";
		
		$spree_info["time1"] = $info["time1"];
		$spree_info["time2"] = $info["time2"];
		$spree_info["spree_data"] = $info["spree_data"];
		
		$this->building_code($spree_info);
	}
}
?>