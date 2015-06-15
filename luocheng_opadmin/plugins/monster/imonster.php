<?php 
namespace plugins\monster;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class imonster extends module
{
	function add_monster_list_views($id)
	{
		if($id>0)
		{
			$rs=dbarr("SELECT * FROM `base2_monster_list` WHERE id=$id");
			return $rs[0];
		}
	}
	
	function monster_list_views()
	{
		$rs=dbarr("SELECT * FROM `base2_monster_list` WHERE 1 ORDER BY `id` ASC ");
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
	
	function get_monster_lv($monster_id)
	{
		$rs=dbarr("SELECT * FROM `base2_monster_lv` WHERE monster_id=".$monster_id." ORDER BY `lv` ASC ");
		return $rs;
	}
	
	function add_monster_list($obj)
	{
		$filed=array("monster_name","color","monster_id","formation","copper","experience","goods_ext_id","hero_id","sports","warriors_lv","warriors_rank");
		$value=array($obj["monster_name"],$obj["color"],$obj["monster_id"],$obj["formation"],$obj["copper"],$obj["experience"],$obj["goods_ext_id"],$obj["hero_id"],$obj["sports"],$obj["warriors_lv"],$obj["warriors_rank"]);
		
		if($obj["edit"]>0)
		{				
			dbupdate("base2_monster_list", $filed, $value, "id=".$obj["edit"]);
		}else{
			dbinsert("base2_monster_list",$filed,$value);
		}
	}
	
	function del_monster_list($monster_id)
	{
		sql("DELETE FROM `base2_monster_list` WHERE id=$monster_id");
	}
	
	function ao($monster_id,$monster_lv)
	{
		$aos=array(1=>1,2=>2,3=>3,4=>3);
		$rs=dbarr("SELECT `profession` FROM `base2_monster` WHERE `id`=".$monster_id);
		if(!$rs)
		{
			return false;
		};
		return $aos[$rs[0]["profession"]];
	}
}
		