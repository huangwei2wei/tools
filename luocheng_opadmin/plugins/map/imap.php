<?php 
namespace plugins\map;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;

class imap extends module
{
	
	//setdata
	function set_copy_data($data)
	{
		/*
		{
			"map_id":101,
			"start_x":1000,
			"show_id":101,
			"monster_list":[
				{"monster_id"：100,“xy”:"20:20","w":"20","h":"100","order":1},
				{"monster_id"：100,“xy”:"20:20","w":"20","h":"100","order":2}
			],
			"npc_list":[
				{"npc_id":101,"xy":"100:100"}，
				{"npc_id":101,"xy":"100:100"},
				{"npc_id":101,"xy":"100:100"}
			]
		}*/
		
		$data=json_decode($data);
		$data=object_to_array($data);
		$map_id=$data["map_id"];
		$monster_list=$data["monster_list"];
		$npc_list=$data["npc_list"];
		$db=$data["db"];
		
		sql("DELETE FROM `base2_map_copy` WHERE `map_id` =".intval($map_id),$db);
		
		if(count($monster_list)>0)
		{
			foreach($monster_list as $key=>$value)
			{
				$field=array("map_id","npc_id","monster_id","xy","w","h","order");
				$value=array(intval($map_id),0,intval($value["monster_id"]),$value["xy"],intval($value["w"]),intval($value["h"]),intval($value["order"]));
				dbinsert("base2_map_copy", $field, $value,1,$db);
			}
		}
		
		if(count($npc_list)>0)
		{
			foreach($npc_list as $key=>$value)
			{
				$field=array("map_id","npc_id","monster_id","xy","w","h","order");
				$value=array(intval($map_id),intval($value["npc_id"]),0,$value["xy"],0,0,0);
				dbinsert("base2_map_copy", $field, $value,1,$db);
			}
		}
		
		sql("UPDATE  `base2_map2` SET  `start_x` = ".$data["start_x"].",`show_id`='".$data["show_id"]."' WHERE `id` =".$map_id,$db);
		return true;
	}
	
	function set_info_data($data)
	{
		/*
		{
			"map_id":101,
			"start_x":1000,
			"show_id":101,
			"monster_list":[
				{"monster_id"：100,“xy”:"20:20","w":"20","h":"100","order":1},
				{"monster_id"：100,“xy”:"20:20","w":"20","h":"100","order":2}
			],
			"npc_list":[
				{"npc_id":101,"xy":"100:100"}，
				{"npc_id":101,"xy":"100:100"},
				{"npc_id":101,"xy":"100:100"}
			]
		}*/
	
		$data=json_decode($data);
		$data=object_to_array($data);
		$map_id=$data["map_id"];
		$monster_list=$data["monster_list"];
		$npc_list=$data["npc_list"];
		$db=$data["db"];
	
		sql("DELETE FROM `base2_map_info` WHERE `map_id` =".$map_id,$db);
	
		if(count($monster_list)>0)
		{
			foreach($monster_list as $key=>$value)
			{
				$field=array("map_id","npc_id","monster_id","xy","w","h","order");
				$value=array(intval($map_id),0,intval($value["monster_id"]),$value["xy"],intval($value["w"]),intval($value["h"]),intval($value["order"]));
				dbinsert("base2_map_info", $field, $value,1,$db);
			}
		}
	
		if(count($npc_list)>0)
		{
			foreach($npc_list as $key=>$value)
			{
				$field=array("map_id","npc_id","monster_id","xy","w","h","order");
				$value=array(intval($map_id),intval($value["npc_id"]),0,$value["xy"],0,0,0);
				dbinsert("base2_map_info", $field, $value,1,$db);
			}
		}
		sql("UPDATE  `base2_map1` SET  `start_x` = ".$data["start_x"].",`show_id`='".$data["show_id"]."' WHERE `id` =".$map_id,$db);
		
		return true;
	}
	
	function get_copy_map($map_id,$db)
	{
		$base2_map1=dbarr("SELECT id,show_id,start_x FROM `base2_map2`  WHERE id=".intval($map_id),0,$db);
		$base2_map_info=dbarr("SELECT * FROM `base2_map_copy`  WHERE map_id=".intval($map_id),0,$db);
		//$base_goods=dbarr("SELECT a.*,b.show_id,b.start_x FROM `base2_map_copy` as a inner join `base2_map2` as b on a.map_id=b.id WHERE  a.map_id=".intval($map_id));
		return array($base2_map1,$base2_map_info);
		//return $base_goods;
	}
	
	function get_info_map($map_id,$db)
	{
		$base2_map1=dbarr("SELECT id,show_id,start_x FROM `base2_map1`  WHERE id=".intval($map_id),0,$db);
		$base2_map_info=dbarr("SELECT * FROM `base2_map_info`  WHERE map_id=".intval($map_id),0,$db);
		//$base_goods=dbarr("SELECT a.*,b.show_id,b.start_x FROM `base2_map_info` as a inner join `base2_map1` as b on a.map_id=b.id WHERE map_id=".intval($map_id));
		return array($base2_map1,$base2_map_info);
		//return $base_goods;
	}
	
	function copy_list_name($db)
	{
		$base_goods=dbarr("SELECT * FROM `base2_map2` WHERE 1",0,$db);
		return $base_goods;
	}
}
?>