<?php 
namespace plugins\mall;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;
use kernel\base\base_class\mongopage as MONGOPAGE;

class imall extends module
{
	
	//获取数据
	function mall($type,$page)
	{
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$code_goods=$mongopage->mongodb($this->phpmongo,"mall",array("mall_type"=>1,"type"=>intval($type)),array("sort"=>1));
		$mypages=$mongopage->p(true);
		
		$malllist=array();
		$goods_ext_id="";
		foreach($code_goods as $value)
		{
			$malllist[]=$value;
			$goods_ext_id.=$value["goods_ext_id"].",";
		}
		$idgoods=array();
		$base_goods=dbarr("SELECT a.goods_name,b.goods_ext_id FROM `base_goods` a inner join `base_goods_ext` b on a.id=b.goods_id WHERE b.`goods_ext_id` in (".substr($goods_ext_id,0,-1).")");
		foreach($base_goods as $value)
		{
			$idgoods[$value["goods_ext_id"]]=$value["goods_name"];
		}
		return array($malllist,$mypages,$idgoods);
	}
	
	function upmall($get)
	{
		if($get["filed"]=="rebate"){
			$get["data"]=floatval($get["data"]);
		}else{
			$get["data"]=intval($get["data"]);
		}
		$this->phpmongo->update("mall",array($get["filed"]=>$get["data"]),array($get["query_filed"]=>intval($get["query_value"])));
	}
	
	function getextgoods($type)
	{
		$arr1=array(100,101,102);
		$arr2=array(6,7,8);
		if(in_array($type,$arr1))
		{//商店
			//2=>"材料",3=>"消耗",1=>"装备",4=>"宝石",5=>"宝物",6=>"热卖",7=>"vip礼包"
			$marr=array(

					100=>array(1,2),
					101=>array(4),
					102=>array(3,5),
				);
			$s="";
			
			foreach($marr[$type] as $value)
			{
				$s.=' a.`goods_type`='.$value.' or';
			}
			return dbarr("SELECT a.goods_name,a.sub_type,a.goods_type,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE a.is_sale=1 and (".substr($s,-2).")");	
		}elseif(in_array($type,$arr2)){
			return dbarr("SELECT a.goods_name,a.sub_type,a.goods_type,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE 1");	
		}else{//商城
			return dbarr("SELECT a.goods_name,a.sub_type,a.goods_type,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE a.`goods_type`=".$type);	
		}
	}
	
	function addmall($post)
	{
		if($post["mall_id"]>0)
		{
			$field=array("mall_type","goods_ext_id","type","money","money_type");
			$value=array($post["mall_type"],$post["wupid"],$post["ttttype"],$post["money_kk"],$post["jiaget"]);
			if(isset($post["sum"])){
				$field[]="sum";
				$value[]=$post["sum"];
			}
			
			if(isset($post["rand_p"])){
				$field[]="rand_p";
				$value[]=$post["rand_p"];
			}
			dbupdate("base3_mall", $field, $value, "id=".$post["mall_id"]);
		}else{
			$field=array("mall_type","goods_ext_id","type","money","money_type");
			$value=array($post["mall_type"],$post["wupid"],$post["ttttype"],$post["money_kk"],$post["jiaget"]);
			
			if(isset($post["sum"])){
				$field[]="sum";
				$value[]=$post["sum"];
			}
			
			if(isset($post["rand_p"])){
				$field[]="rand_p";
				$value[]=$post["rand_p"];
			}
			
			dbinsert("base3_mall", $field, $value);
		}
	}
	
	function delmall($gid)
	{
		$this->phpmongo->delete("mall",array("id"=>intval($gid)));
	}
	
	function get_goods_ext_list()
	{
		$goods=dbarr("SELECT a.goods_name,b.goods_ext_id,b.goods_id FROM `base_goods` as a inner join `base_goods_ext` as b on a.id=b.goods_id  WHERE 1");	
		$goods_ext_list=array();
		foreach($goods as $value)
		{
			$goods_ext_list[$value["goods_ext_id"]]=$value;
		}
		return $goods_ext_list;
	}
	
	
	function sell_situation($time1,$time2)
	{
		$goods_act=$this->phpmongo->find("log_goods_act",array("module_sub_id"=>array('$in'=>array(67,36,63,88,103)),"e"=>"+"),array("goods_usre_name"=>true,"module_sub_id"=>true,"max"=>true,"goods_ext_id"=>true))->sort(array("module_sub_id"=>1));
		$godoslist=array();
		foreach($goods_act as $value)
		{
			if(isset($godoslist[$value["module_sub_id"]])==false)
			{
				$godoslist[$value["module_sub_id"]]=array();
			}
			
			if(isset($godoslist[$value["module_sub_id"]][$value["goods_ext_id"]])==false)
			{
				$godoslist[$value["module_sub_id"]][$value["goods_ext_id"]]=$value;
			}else{
				$godoslist[$value["module_sub_id"]][$value["goods_ext_id"]]["max"]+=$value["max"];
			}
			
		}
		return 	$godoslist;
	}
}
?>