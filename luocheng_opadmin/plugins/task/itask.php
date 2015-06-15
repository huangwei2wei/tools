<?php 
namespace plugins\task;
use controllers\module as MODULE;

class itask extends module
{
	function detect_task()
	{
		$base3_task=dbarr("SELECT id,goods_award,res_award FROM `base3_task` WHERE 1");
		$base3_task_condition=dbarr("SELECT id,condition FROM `base3_task_condition` WHERE 1");
		return array("base3_task"=>$base3_task,"base3_task_condition"=>$base3_task_condition);
	}
	
	function task_change()
	{
		/*
		* 帮我去刘备哪里找
		* 你应该强化装备
		* 你去通关南阳城东}哈哈
		* 你搜集XX物品
		* */
		$goods_idlist=array();
		$actgoodsarr=array();
		$base2_map=$base3_task_condition=dbarr("SELECT `id`, `goods_ext_id` FROM `base2_map2` WHERE 1");
		foreach($base2_map as $key=>$value)
		{
			$goods_ext_id=arrayed($value["goods_ext_id"]);
			if($goods_ext_id)
			{
				foreach($goods_ext_id as $k=>$v)
				{
					if(iseet($actgoodsarr[$k])==false)
					{
						$actgoodsarr[$k]=$value["id"];
					}
				}
			}
		}
		
		$base_module_list=array();
		$base_module=$base3_task_condition=dbarr("SELECT `id`, `module_name` FROM `base_module` WHERE 1");
		foreach($base_module as $key=>$value)
		{
			$base_module_list[$value["id"]]=$value["module_name"];
		}
		
		$base_goods=$base3_task_condition=dbarr("SELECT id,goods_name FROM `base_goods` WHERE 1");
		$base2_npc=$base3_task_condition=dbarr("SELECT id,nickname,name FROM `base2_npc` WHERE 1");
		$base2_map2=$base3_task_condition=dbarr("SELECT id,info FROM `base2_map2` WHERE 1");
		
		foreach($base2_map2 as $key=>$value)
		{
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =CONCAT('<a href=\'event:MAP|".$value["info"]."\'>',condition_name,'</a>') WHERE `condition_name` ='%".$value["info"]."%'");
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =REPLACE(condition_name,'".$value["info"]."','<u><font color='#fffc00'>".$value["info"]."</font></u>') WHERE `condition_name` ='%".$value["info"]."%'");
		}
		
		
		foreach($base2_npc as $key=>$value)
		{
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =CONCAT('<a href=\'event:NPC|".$value["id"]."\'>',condition_name,'</a>') WHERE `condition_name` ='%".$value["nickname"]."%'");
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =REPLACE(condition_name,'".$value["nickname"]."','<u><font color='#fffc00'>".$value["name"]."</font></u>') WHERE `condition_name` ='%".$value["nickname"]."%'");
		}
		

		foreach($base_module_list as $key=>$value)
		{
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =CONCAT('<a href=\'event:MOD|".$key."\'>',condition_name,'</a>') WHERE `condition_name` ='%".$value."%'");
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =REPLACE(condition_name,'".$value."','<u><font color='#fffc00'>".$value."</font></u>') WHERE `condition_name` ='%".$value."%'");
		}
		
		foreach($base_goods as $key=>$value)
		{
			if(isset($actgoodsarr[$value["id"]]))
			{
				$mapid=$actgoodsarr[$value["id"]];
			}else{
				$mapid=0;
			}
			
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =CONCAT('<a href=\'event:MAP|".$mapid."\'>',condition_name,'</a>') WHERE `condition_name` ='%".$value["goods_name"]."%'");
			sql("UPDATE  `base3_task_condition` SET  `condition_name` =REPLACE(condition_name,'".$value["goods_name"]."','<u><font color='#fffc00'>".$value["goods_name"]."</font></u>') WHERE `condition_name` ='%".$value["goods_name"]."%'");
		}
		
	}
	
	
}
?>