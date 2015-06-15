<?php 
namespace kernel\base\base_class;
/*
静态资源类
*/

class res{
	
	function __construct()
	{ 
		
	}
	
	function __destruct() 
	{
		
	}

	function base_user($key=false,$key1=false)
	{
		require(D_R."res/data/base_user.php");
		$t=$base_user;
		unset($base_user);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_bourn_lv($key=false,$key1=false)
	{
		require(D_R."res/data/base_bourn_lv.php");
		$t=$base_bourn_lv;
		unset($base_bourn_lv);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_condition($key=false,$key1=false)
	{
		require(D_R."res/data/base_condition.php");
		$t=$base_condition;
		unset($base_condition);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_deliver($key=false,$key1=false)
	{
		require(D_R."res/data/base_deliver.php");
		$t=$base_deliver;
		unset($base_deliver);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_identification($key=false,$key1=false)
	{
		require(D_R."res/data/base_identification.php");
		$t=$base_identification;
		unset($base_identification);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_mine($key=false,$key1=false)
	{
		require(D_R."res/data/base_mine.php");
		$t=$base_mine;
		unset($base_mine);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_errormsg($key=false,$key1=false)
	{
		require(D_R."res/data/base_errormsg.php");
		$t=$base_errormsg;
		unset($base_errormsg);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_field($key=false,$key1=false)
	{
		require(D_R."res/data/base_field.php");
		$t=$base_field;
		unset($base_field);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_gift_pack($key=false,$key1=false)
	{
		require(D_R."res/data/base_gift_pack.php");
		$t=$base_gift_pack;
		unset($base_gift_pack);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_goods($key=false,$key1=false)
	{
		require(D_R."res/data/base_goods.php");
		$t=$base_goods;
		unset($base_goods);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_goods_ext($key=false,$key1=false)
	{
		require(D_R."res/data/base_goods_ext.php");
		$t=$base_goods_ext;
		unset($base_goods_ext);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_goods_rand($key=false,$key1=false)
	{
		require(D_R."res/data/base_goods_rand.php");
		$t=$base_goods_rand;
		unset($base_goods_rand);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_goods_suit_code($key=false,$key1=false)
	{
		require(D_R."res/data/base_goods_suit_code.php");
		$t=$base_goods_suit_code;
		unset($base_goods_suit_code);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	
	function base_mall($key=false,$key1=false)
	{
		require(D_R."res/data/base_mall.php");
		$t=$base_mall;
		unset($base_mall);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	
	function base_map_copy_info($key=false,$key1=false)
	{
		require(D_R."res/data/base_map_copy_info.php");
		$t=$base_map_copy_info;
		unset($base_map_copy_info);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_map_nexus($key=false,$key1=false)
	{
		require(D_R."res/data/base_map_nexus.php");
		$t=$base_map_nexus;
		unset($base_map_nexus);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_monster($key=false,$key1=false)
	{
		require(D_R."res/data/base_monster.php");
		$t=$base_monster;
		unset($base_monster);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_monster_list($key=false,$key1=false)
	{
		require(D_R."res/data/base_monster_list.php");
		$t=$base_monster_list;
		unset($base_monster_list);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_monster_lv($key=false,$key1=false)
	{
		require(D_R."res/data/base_monster_lv.php");
		$t=$base_monster_lv;
		unset($base_monster_lv);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_msg_word($key=false,$key1=false)
	{
		require(D_R."res/data/base_msg_word.php");
		$t=$base_msg_word;
		unset($base_msg_word);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_ninjutsu($key=false,$key1=false)
	{
		require(D_R."res/data/base_ninjutsu.php");
		$t=$base_ninjutsu;
		unset($base_ninjutsu);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_npc($key=false,$key1=false)
	{
		require(D_R."res/data/base_npc.php");
		$t=$base_npc;
		unset($base_npc);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_properties($key=false,$key1=false)
	{
		require(D_R."res/data/base_properties.php");
		$t=$base_properties;
		unset($base_properties);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_qmdj($key=false,$key1=false)
	{
		require(D_R."res/data/base_qmdj.php");
		$t=$base_qmdj;
		unset($base_qmdj);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_quality($key=false,$key1=false)
	{
		require(D_R."res/data/base_quality.php");
		$t=$base_quality;
		unset($base_quality);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_slaver_talk($key=false,$key1=false)
	{
		require(D_R."res/data/base_slaver_talk.php");
		$t=$base_slaver_talk;
		unset($base_slaver_talk);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_task($key=false,$key1=false)
	{
		require(D_R."res/data/base_task.php");
		$t=$base_task;
		unset($base_task);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_task_condition($key=false,$key1=false)
	{
		require(D_R."res/data/base_task_condition.php");
		$t=$base_task_condition;
		unset($base_task_condition);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	
	function base_user_lv($key=false,$key1=false)
	{
		require(D_R."res/data/base_user_lv.php");
		$t=$base_user_lv;
		unset($base_user_lv);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_hero($key=false,$key1=false)
	{
		require(D_R."res/data/base_hero.php");
		$t=$base_hero;
		unset($base_hero);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_map_word($key=false,$key1=false)
	{
		require(D_R."res/data/base_map_word.php");
		$t=$base_map_word;
		unset($base_map_word);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_map_area($key=false,$key1=false)
	{
		require(D_R."res/data/base_map_area.php");
		$t=$base_map_area;
		unset($base_map_area);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_bres($key=false,$key1=false)
	{
		require(D_R."res/data/base_bres.php");
		$t=$base_bres;
		unset($base_bres);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_soldier($key=false,$key1=false)
	{
		require(D_R."res/data/base_soldier.php");
		$t=$base_soldier;
		unset($base_soldier);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_technology($key=false,$key1=false)
	{
		require(D_R."res/data/base_technology.php");
		$t=$base_technology;
		unset($base_technology);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_loyalty($key=false,$key1=false)
	{
		require(D_R."res/data/base_loyalty.php");
		$t=$base_loyalty;
		unset($base_loyalty);
		sort_my($t,"rand",1);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_land_expansion($key=false,$key1=false)
	{
		require(D_R."res/data/base_land_expansion.php");
		$t=$base_land_expansion;
		unset($base_land_expansion);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_vip($key=false,$key1=false)
	{
		require(D_R."res/data/base_vip.php");
		$t=$base_vip;
		unset($base_vip);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_map_data_novice($key=false,$key1=false)
	{
		require(D_R."res/data/base_map_data_novice.php");
		$t=$base_map_data_novice;
		unset($base_map_data_novice);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_savvy($key=false,$key1=false)
	{
		require(D_R."res/data/base_savvy.php");
		$t=$base_savvy;
		unset($base_savvy);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_national_policy($key=false,$key1=false)
	{
		require(D_R."res/data/base_national_policy.php");
		$t=$base_national_policy;
		unset($base_national_policy);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	/**
	 * 阵形基表
	 *
	 * @param integer $key ID
	 * @param string $key1 字段
	 * @return arr
	 */
	function base_formation($key=false,$key1=false)
	{
		require(D_R."res/data/base_formation.php");
		$t=$base_formation;
		unset($base_formation);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
	
	function base_temper_division($key=false,$key1=false)
	{
		require(D_R."res/data/base_temper_division.php");
		$t=$base_temper_division;
		unset($base_formation);
		
		if($key and $key1){return $t[$key][$key1];}
		if($key){return $t[$key];}
		return $t;
	}
}
