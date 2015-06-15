<?php 
namespace plugins\mall;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;

class imall extends module
{
	
	//获取数据
	function mall($type,$page)
	{
		$base_goods=dbarr("SELECT * FROM `base_goods` WHERE 1");
		foreach($base_goods as $value)
		{
			$data[$value["id"]]=$value;
		}
   //SELECT * FROM `base_goods` a inner join base3_mall b on a.goods_type=b.type
		$base_goods_ext=dbarr("SELECT * FROM `base_goods_ext` WHERE 1");
		foreach($base_goods_ext as $value)
		{
			$data1[$value["goods_ext_id"]]=arrunite($value,$data[$value["goods_id"]]);
		}
		$mall=array();
		$sql="SELECT * FROM `base3_mall` WHERE mall_type=1 and type=".$type;
		
		$page=new PAGE;
		$res=$page->sql_p($sql,11);
		$p=$page->p(true);
		if($res)
		{
			foreach($res as $value)
			{
				$ttt=$data1[$value["goods_ext_id"]];
				$ttt["money"]=$value["money"];
				$ttt["money_type"]=$value["money_type"];
				$ttt["type"]=$value["type"];
				$ttt["mall_id"]=$value["id"];
				$mall[]=$ttt;
			}
		}
		return array($mall,$p);
	}
	
	function upmall($id,$field,$value)
	{
		return sql("UPDATE `base3_mall` SET  `$field` =  '$value' WHERE `id` =".$id);
	}
	
	function getextgoods($type)
	{
		$arr1=array(100,101,102);
		$arr2=array(6,7,8,12,13);
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
			
			if(isset($post["stint_sum"])){
				$field[]="stint_sum";
				$value[]=$post["stint_sum"];
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
			
			if(isset($post["stint_sum"])){
				$field[]="stint_sum";
				$value[]=$post["stint_sum"];
			}
			
			dbinsert("base3_mall", $field, $value);
		}
	}
	
	function delmall($gid)
	{
		sql("DELETE FROM `base3_mall` WHERE `id` = ".$gid);
	}
}
?>