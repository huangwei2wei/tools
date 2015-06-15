<?php 
namespace plugins\mall1;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;

class imall1 extends module
{
	
	//获取数据
	function mall($type,$page)
	{
		$base_goods=dbarr("SELECT * FROM `base_goods` WHERE 1");
		foreach($base_goods as $value)
		{
			$data[$value["id"]]=$value;
		}
		
		$base_goods_ext=dbarr("SELECT * FROM `base_goods_ext` WHERE 1");
		foreach($base_goods_ext as $value)
		{
			$data1[$value["goods_ext_id"]]=arrunite($value,$data[$value["goods_id"]]);
		}
		
		
		
		$mall=array();
		$sql="SELECT * FROM `base3_mall` WHERE type=".$type;
		$page=new PAGE;
		$res=$page->sql_p($sql,12);
		$p=$page->p(true);
		if($res)
		{
			foreach($res as $value)
			{
				$ttt=$data1[$value["goods_ext_id"]];
				$ttt["money"]=$value["money"];
				$ttt["mall_type"]=$value["mall_type"];
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
		if($type==7)
		{
			return dbarr("SELECT a.goods_name,a.sub_type,a.gold,a.copper,a.sport,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE  a.is_sale=2 and a.`goods_type`=3 and a.`sub_type`=".$type);
		}else{
			return dbarr("SELECT a.goods_name,a.sub_type,a.gold,a.copper,a.sport,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE a.`goods_type`=1 and a.is_sale=2 and a.`sub_type`=".$type);
		}	
	}
	
	function addmall($post)
	{
		if($post["mall_id"])
		{
			$field=array("goods_ext_id","type","money","money_type");
			$value=array($post["wupid"],$post["ttttype"],$post["money_kk"],$post["jiaget"]);
			dbupdate("base_mall", $field, $value, "id=".$post["mall_id"]);
		}else{
			$field=array("goods_ext_id","type","money","money_type");
			$value=array($post["wupid"],$post["ttttype"],$post["money_kk"],$post["jiaget"]);
			dbinsert("base_mall", $field, $value);
		}
	}
	
	function delmall($gid)
	{
		sql("UPDATE `base3_mall` SET  'mall_type'='0',`goods_ext_id` =  '0',`money`=0,`money_type`=0 WHERE  `id` =".$gid);
	}
}
?>