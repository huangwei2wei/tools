<?php 
//战斗力公式
function war_damage($my_attack,$other_defense)
{
	/**
	*设每增加1点防御值免伤提高K%， 设防御为N点。
	*防御值=对方防御力+忍术被动防御；
	*
	*防御免伤=1-（1-k%）^N                 （在这里K先设置为0.05）
	*
	*最终伤害=攻击方攻击力*(1-防御方的防御免伤)
	*
	*最终伤害=攻击方攻击力*(1-（1-0.9995^N ）)    （N为对方防御值）
	*最终伤害=攻击方攻击力*0.9995^N    （N为对方防御值）
	**/
	
	$defensive_avoidance=1-pow(1-AVOIDANCE,$other_defense);
	$eventually_damage=$my_attack*(1-$defensive_avoidance);
	return $eventually_damage;
}


//敏捷兑换闪避率公式
function agile_to_dodge($agile)
{
	/**
	* 设每增加1点敏捷值，闪避率提高K%， 设敏捷为N点。
	* 
	*闪避率=1-（1-k%）^N                 （在这里K先设置为0.05）
	*
	*闪避率=1-0.9995^N     （N为敏捷值）
	*/
	
	$dodge=1-pow(1-DODGE,$agile);
	return $dodge;
}

//忍术等级公式
function ninjutsu_lv($n_id,$lv)
{
	require(D_R."/res/data/base_ninjutsu.php");
	/*
	忍术伤害公式：
	
	设某个忍术基础伤害为A，忍术等级为X，忍术熟练度为K%;
	
	一、那么当前等级的忍术魔法伤害为：A*(1.05^(X-1))*k% ;   比如一级忍术就是  A*（1.05^0)*k%=A*k%
	例如：base_ninjutsu.xls表中 豪火球之术的基础攻击是100 默然熟练度是20%  那么一级豪火球的伤害就是 100*（1.05^1）*20%+人物的魔法伤害=21+人物魔法伤害。
	
	二、当前忍术的查克拉消耗为：A*(1.05^(X-1))*2       即是魔法伤害的2倍。   （1.05^(X-1)）表示1.05的X-1次方。
	
	三、当前忍术升级所需时间：X*X*5  (分钟)
	
	所有等级忍术的奇门遁甲需求和忍阶需求与初始等级相同。
	忍术熟练度为单独存在，忍术熟练度等于忍术等级增加的熟练度加上玩家提升的熟练度。
	计算方法为：设玩家累计提升的熟练度为 N%,玩家累计提升的熟练度为一个累积的数值，不会随着忍术等级变化而变化。
	
	四、当前级别忍术熟练度为：N%+X*1%+10%
	
	
	五、熟练度升级公式：成功率的 100%-当前熟练度。   比如默认熟练度20%那么熟练度升级的成功率是100%-20%=80%
	比如110%熟练度升级的成功率=100%-110%=-10%。 
	六、忍术升级的需求等级=向下取整：   当前忍术1级需求的等级+（100-当前忍术1级需求的等级）/28*（忍术升级前的级别）
	*/
	$attack=$base_ninjutsu[$n_id]["attack"];
	$skilled=$base_ninjutsu[$n_id]["skilled"];
	$passive=$base_ninjutsu[$n_id]["passive"];
	$ceiling_level=$base_ninjutsu[$n_id]["ceiling_level"];
	$user_grade=$base_ninjutsu[$n_id]["user_grade"];
	$user_grade=floor($user_grade+(100-$user_grade)/28*$lv-1);
	$m_attack=$attack*pow(1.05,$lv-1)*$skilled;
	
	$time=$lv*$lv*5;
	if($passive==1)
	{
		$fairyism=$attack*pow(1.05,$lv-1)*2*$skilled;
		$probability=1;
	}else{
		$fairyism=$attack*pow(1.05,$lv-1)*0.1*$skilled;
		$probability=0.14+$lv/200+$skilled/10;
	}
	
	$upprobability=1-$lv*3/100;
	
//	$up_bourn_lv=floor(($lv+1)/28*($ceiling_level-($user_grade+1)));
	
	return array(
					"attack"=>$m_attack,				//发动的攻击或防御
					"fairyism"=>$fairyism,			//发动攻击或防御所消耗的查克拉
					"time"=>$time,					//学习或升级所需要的时间
					"probability"=>$probability,	//被动忍术的触发概率
					"passive"=>$passive,			//忍术是被动还是主动 0被动 1主动
					"up_user_grade"=>$user_grade,	//学习忍术所需等级
					"upprobability"=>$upprobability,//忍术升级概率
				);
}

//////////////////////////////////////////////////////////////////////////////////////

/**
 * 征收公式
 *
 * @param integer	$res		资源类
 * @param integer	$capital	是否首都
 * @param integer	$population 当前人口
 * @param integer	$city_sum	已有城池数城池数
 * @param integer	$luxury_sum 已有奢侈资源数
 * @param arr		$user_res	用户所有资源列表
 * @param string	$national_policy 用户国策
 * @return int
 */
function collection($res,$capital,$population,$city_sum,$luxury_sum,$user_res,$national_policy)
{
	$money_add=0;
	$base_national_policy=$res->base_national_policy();
	$national_policy_arr=arrayed($national_policy);
	
	$policy12=$national_policy_arr[12];//开启后征收额外获得2%的金币总量
	$policy14=$national_policy_arr[14];//所拥有的每个普通资源+1、3、5、7、9、12、15、18、21金币（每次征收获得）
	$policy15=$national_policy_arr[15];//沿海城池（距离海边3格内）+1、2、3、4、5%征收金币量
	$policy16=$national_policy_arr[16];//每座附属城池+100~1000金币（每次征收获得）
	$policy18=$national_policy_arr[18];//每开发黄金或白银资源+5、10、15、20、25、30、35、40、45、50金币（每次征收获得）
	
	if($policy14 && $policy14[1])
	{
		$value=json_decode($base_national_policy[14]["value"]);
		
		$money_add+=count($user_res)*$value[$policy14[1]-1];
	}
	
	//15搁浅 (沿海城池)
	
	if($policy16 && $policy16[1])
	{
		$value=json_decode($base_national_policy[16]["value"]);
		
		$money_add+=$city_sum*$value[$policy16[1]-1];
	}
	
	if($policy18 && $policy18[1])
	{
		$value=json_decode($base_national_policy[14]["value"]);
		foreach($user_res as $v)
		{
			if($v["bres_id"]==17 || $v["bres_id"]==18)
			{
				$money_add+=$value[$policy18[1]-1];
			}
		}
	}
	
	$x=0;
	$loyalty=loyalty_formula($res,$population,$city_sum,$luxury_sum,$national_policy);
	
	if($capital)
	{
		$money=600*($loyalty/100)+($x*200)+$money_add;
		//征收金币数=600*（(民忠)/100）+（本首都连接道路数*200）+国策加成
	}else{
		//征收金币数=200*（(民忠)/100）+（本城池连接道路数*100）+国策加成
		$money=200*($loyalty/100)+($x*100)+$money_add;
	}
	
	if($policy12 && $policy12[1])
	{
		$value=json_decode($base_national_policy[12]["value"]);
		return round($money+($money*$value[$policy12[1]-1]));
	}else{
		return round($money);
	}
}

/**
 * 城防升级消耗金币公式
 *
 * @param integer $urban_defense	城防等级
 * @return int
 */
function city_defense($urban_defense)
{
	$gold=1000*$urban_defense+($urban_defense*$urban_defense)*10;
	//城防升级消耗金币公式:消耗金币=1000*(城防等级)+(城防等级*城防等级)*10
    return $gold;
}

/**
 * 民忠公式
 *
 * @param integer $res 		  资源类
 * @param integer $population 当前人口
 * @param integer $city_sum 已有城池数城池数
 * @param integer $luxury_sum 已有奢侈资源数
 * @param string $national_policy 用户国策
 * @return bool
 */
function loyalty_formula($res,$population,$city_sum,$luxury_sum,$national_policy)
{
	$policyadd=$luxuryadd=0;
	$base_national_policy=$res->base_national_policy();
	$national_policy_arr=arrayed($national_policy);
	$policy=$national_policy_arr[5];
	$luxury=$national_policy_arr[13];
	//城池数赋值:1     人口数赋值:0.5   奢侈资源:1
	//民忠公式: 民忠值=10-（赋值*（已有城池数城池数-1））-（当前人口*赋值）+（赋值*已有奢侈资源数）+（国策加成值）
	$a=0.05;
	$b=0.5;
	$c=1;
	$d=1;
	
	if($luxury && $luxury[1]>0)
	{
		$value=$base_national_policy[13]["value"];
		$value=json_decode($value);
		$add=$value[$luxury[1]-1];
		$luxuryadd=$luxury_sum*$add;
	}
	
	if($policy && $policy[1]>0)
	{
		$value=$base_national_policy[5]["value"];
		$value=json_decode($value);
		$policyadd=$value[$policy[1]-1];
	}
	
	$loyalty=10-($a*($city_sum-1))-$population*$b+$luxuryadd*$c+$policyadd;
	return $loyalty;
}

/**
 * 人口上限
 *
 * @param integer $city_lv	首都等级
 * @param integer $city_sum	城市数量
 * @return int
 */
function population_top($city_lv,$city_sum)
{
	//人口上限=首都等级*1000+城池*500
	return $city_lv*1000+$city_sum*500;
}

/**
 * 英雄上限个数公司
 *
 * @param integer $city_sum	城市数量
 * @return int
 */
function hero_sum($city_sum)
{
	//拥有英雄数上限公式：玩家同时拥有英雄数量上限=5+(城池数*5)
	return 5+$city_sum*5;
}

/**
 * 征兵上限(收费)
 *
 * @param integer $user_lv	用户等级
 * @return int
 */
function soldier_top($user_lv)
{
//	兵力	预备兵上限值	=(玩家等级*4)*1000 
	return $user_lv*4*1000;
}

/**
 * 征兵上限(收费)
 *
 * @param integer $user_lv	用户等级
 * @return int
 */
function free_soldier_top($user_lv)
{
//	兵力	预备兵上限值	=(玩家等级*4)*1000 
	return round(soldier_top($user_lv)*1.5);
}

/**
 * 征兵消耗钱
 *
 * @param integer $res			资源类
 * @param integer $user_lv		用户等级
 * @param integer $soldier_sum	征兵数量
 * @param integer $population 	当前人口
 * @param integer $city_sum 	已有城池数城池数
 * @param integer $luxury_sum 	已有奢侈资源数
 * @param string  $national_policy	用户国策
 * @return int
 */
function soldier_money($res,$user_lv,$soldier_sum,$city_sum,$population,$luxury_sum,$national_policy)
{
//	金币	招募兵所需金币	=玩家等级*所招募兵数*3/1000*((100-(民忠-10))/100)
	$loyalty_formula=loyalty_formula($res,$population,$city_sum,$luxury_sum,$national_policy);
	return $user_lv*$soldier_sum*3/1000*((100-($loyalty_formula-10))/100);
}

/**
 * 免费征兵消耗的时间
 *
 * @param integer $c_sum	征兵次数
 * @return int
 */
function free_soldier_time($c_sum)
{
	return ($c_sum+2)*120;
}

/**
 * 强征元宝算法
 *
 * @param integer $q_sum	强制征兵次数
 * @return int
 */
function q_soldier_money($q_sum)
{
	if($q_sum>50){return 100;}
	if($q_sum>30 && $q_sum<=50){return 50;}
	if($q_sum>10 && $q_sum<=30){return 20;}
	return $q_sum*2;
}

/**
 * 城池上限数
 *
 * @param integer $user_lv	用户等级
 * @return int
 */
function city_top($user_lv)
{
	//城池数上限=Int(个人等级/3)
	return round($user_lv/3);
}

?>