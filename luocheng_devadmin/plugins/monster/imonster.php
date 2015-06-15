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
	
	function monster_views()
	{
		//1-虎卫，2-霸枪，3-神射，4-谋士
		$arr=array(1=>"虎",2=>"霸",3=>"神",4=>"谋");
		$rs=dbarr("SELECT a.id,a.profession,b.lv FROM `base2_monster` a inner join base2_monster_lv b on a.id=b.monster_id WHERE 1 ORDER BY a.`id` ASC");
		$rundata=array();
		foreach($rs as $key=>$value)
		{
			if(isset($rundata[$value["id"]])==false)
			{
				$rundata[$value["id"]]=array();	
			}
			$rundata[$value["id"]][$value["lv"]]=$arr[$value["profession"]];	
		}
		return $rundata;
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
	
	function ao($monster_id,$lv)
	{
		$aos=array(1=>1,2=>2,3=>3,4=>3);
		//$rs=dbarr("SELECT `monster_id` FROM `base2_monster_lv` WHERE `id`=".$monster_lv_id);
		$rs=dbarr("SELECT `profession` FROM `base2_monster` WHERE `id`=".$monster_id);

		if(!$rs)
		{
			return false;
		}
		return $aos[$rs[0]["profession"]];
	}
	
	function upformation($id,$field,$value)
	{
		$rs=dbarr("SELECT `formation` FROM `base2_monster_list` WHERE `id`=".$id);
		$data=$rs[0];
		$formation=arrayed($data["formation"]);
		if($value>0){
			$formation[$field]=array(1,$value);
		}else{
			$formation[$field]=array(0,0);
		}
		
		$strss=stred($formation);
		return sql("UPDATE `base2_monster_list` SET  `formation` =  '$strss' WHERE `id` =".$id);
	}
	
	function upformation1($id,$field,$value)
	{
		return sql("UPDATE `base2_monster_list` SET  `monster_id` =  '$value' WHERE `id` =".$id);
	}
	
	function combat($property)
	{
		$prolist=array("ab","ac","ad","ae","af","ah","ai","aj","ak","al","am","an");
		$property_coefficien=array("ah"=>0.6, "ai"=>0.6, "aj"=>0.6, "ak"=>0.6, "al"=>0.6, "am"=>0.6, "an"=>0.56, "ab"=>0.58, "ac"=>0.52, "ad"=>0.58, "ae"=>0.52, "af"=>0.10);
		foreach($prolist as $key=>$value)
		{
			if(intval($property[$key][0]==0)){
				$property[$key][1]=intval($property[$key][1]);
			}else{
				$property[$key][1]=floatval($property[$key][1]);
			}
		}

		return ceil(intval($property["ab"][1]*floatval($property_coefficien["ab"])+
				$property["ac"][1]*floatval($property_coefficien["ac"])+
				$property["ad"][1]*floatval($property_coefficien["ad"])+
				$property["ae"][1]*floatval($property_coefficien["ae"])+
				$property["af"][1]*floatval($property_coefficien["af"])+
				$property["ah"][1]*floatval($property_coefficien["ah"])+
				$property["ai"][1]*floatval($property_coefficien["ai"])+
				$property["aj"][1]*floatval($property_coefficien["aj"])+
				$property["ak"][1]*floatval($property_coefficien["ak"])+
				$property["al"][1]*floatval($property_coefficien["al"])+
				$property["an"][1]*floatval($property_coefficien["an"])+
				$property["am"][1]*floatval($property_coefficien["am"])
				)*1.1);
	}

	function combat_count($monster)
	{
		$combat_count=0;
		foreach($monster as $key=>$value)
		{
			$dbarr=dbarr("SELECT * FROM `base2_monster_lv` a inner join base2_monster b on a.`monster_id`=b.id WHERE b.id=".$value[0]." and a.lv=".$value[1]);
			
			foreach((array)$dbarr as $k=>$v)
			{
				$combat_count+=$this->combat(arrayed($v["property"]));
			}
		}
		return $combat_count;
	}
	
	function monster_combat()
	{
		$dbarr=dbarr("SELECT * FROM `base2_monster_list` ORDER BY `id` ASC");	
		foreach($dbarr as $key=>$value)
		{
			$monster_id=arrayed($value["monster_id"]);
			$formation=arrayed($value["formation"]);
			$monster=array();
			foreach($formation as $k=>$v)
			{
				if($v[0]>0){
					$monster[]=array($v[1],$monster_id[$v[1]][1]);
				}
			}
			
			$dbarr[$key]["monster_combat"]=$this->combat_count($monster);
		}
		
		return $dbarr;
	}
}
		