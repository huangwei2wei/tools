<?php 
namespace plugins\money_manage;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class imoney extends module
{
	function add_money_manage_list($id)
	{
		if($id>0)
		{
			$rs=dbarr("SELECT * FROM `user` WHERE id=$id");
			return $rs[0];
		}
	}
	
	function money_manage_list()
	{
		$rs=dbarr("SELECT * FROM `user` WHERE 1 ORDER BY `id` ASC ");
		return $rs;
	}
	
	function checklist()
	{
			$hero=dbarr("SELECT * FROM `base1_hero` WHERE 1 ORDER BY `id` ASC ");
			$goods=dbarr("SELECT b.goods_name,a.* FROM `base_goods_ext` a join base_goods b on a.goods_id=b.id  WHERE 1 ORDER BY a.`goods_ext_id` ASC");
			
			$monster=dbarr("SELECT * FROM `base2_monster` WHERE 1 ORDER BY `id` ASC ");

			return array(
					"hero"=>$hero,
					"goods"=>$goods,
					"monster"=>$monster,
			);
	}
	
	function get_money_manage_lv($monster_id)
	{
		$rs=dbarr("SELECT * FROM `base2_monster_lv` WHERE monster_id=".$monster_id." ORDER BY `lv` ASC ");
		return $rs;
	}
	
	function add_money_manage_list($obj)
	{
		$filed=array("base_user_id","gold","copper","jade","createtime");
		$value=array($obj["base_user_id"],$obj["gold"],$obj["jade"],$obj["createtime"]);
		
		if($obj["edit"]>0)
		{		
			dbupdate("user", $filed, $value, "id=".$obj["edit"]);
		}else{
			dbinsert("user",$filed,$value);
		}
	}
	
	function del_money_list($base_user_id)
	{
		sql("DELETE FROM `user` WHERE id=$base_user_id");
	}
	
	function ao($money_id,$money_lv)
	{
		$rs=dbarr("SELECT property FROM `base2_monster_lv` WHERE monster_id=".$monster_id." and lv=".$monster_lv);
		if(!$rs)
		{
			return FALSE;
		}
		$property=arrayed($rs[0]["property"]);
		return $property["ao"][1];
	}
}
		