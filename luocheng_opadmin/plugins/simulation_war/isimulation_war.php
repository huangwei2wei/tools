<?php 
namespace plugins\simulation_war;
use controllers\module as MODULE;

class isimulation_war extends module
{
	//模拟怪物列表
	function monster_list()
	{
		$base2_monster=dbarr("SELECT * FROM `base2_monster` as a inner join `base2_monster_lv` as b on a.`id`=b.`monster_id`  WHERE a.type=4");
		return $base2_monster;
	}
	
	//技能列表
	function skill_list()
	{
		$base3_skill=dbarr("SELECT * FROM `base3_skill` WHERE 1");
		return $base3_skill;
	}
	
	//剧情列表
	function plot_list()
	{
		$base3_skill=dbarr("SELECT * FROM `base2_plot` WHERE 1");
		return $base3_skill;
	}
}