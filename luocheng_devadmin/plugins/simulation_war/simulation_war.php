<?php
namespace plugins\simulation_war;
use controllers\controllers as CONT;
class simulation_war{
	//************************************************统计在线人数*********************************************//
	function index($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("simulation_war");
		$get=$obj->get; 		
		$monster_list_tmp=$cont->obj->monster_list();
		$monster_list=array();
		foreach($monster_list_tmp as $key=>$value)
		{
			$property=arrayed($value["property"]);
			$value["af"]=$property["af"][1];
			$monster_list[$key]=$value;
		}
		$skill_list=$cont->obj->skill_list();
		$plot_list=$cont->obj->plot_list();
		require_once parse_tmp("/simulation_war/war.html");//引入模版
	}
	
	function warc($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("simulation_war");
		$post=$obj->post;
		//print_r($post);
// 		$post=Array
// 		(
// 				"xround" => 3
// 				,"A7" => 0
// 				,"A4" => 983
// 				,"A1" => 0
// 				,"A8" => 0
// 				,"A5" => 0
// 				,"A2" => 0
// 				,"A9" => 0
// 				,"A6" => 0
// 				,"A3" => 0
// 				,"B1" => 984
// 				,"B4" => 0
// 				,"B7" => 0
// 				,"B2" => 0
// 				,"B5" => 0
// 				,"B8" => 0
// 				,"B3" => 0
// 				,"B6" => 0
// 				,"B9" => 0
// 				,"index" => Array
// 				(
// 						"0" => 2
// 						,"1" => 3
// 						,"2" => 1
// 						,"3" => 2
// 						,"4" => 1
// 						,"5" => 2
// 						,"6" => 3
// 						,"7" => 1
// 				)
		
// 				,"plot_name" => Array
// 				(
// 						"0" => 9
// 						,"1" => 9
// 						,"2" => 9
// 				)
		
// 				,"plot_id" => Array
// 				(
// 						"0" => 1
// 						,"1" => 1
// 						,"2" => 1
// 				)
		
// 				,"place_name" => Array
// 				(
// 						"0" => 1
// 						,"1" => 1
// 				)
		
// 				,"place_act" => Array
// 				(
// 						"0" => 1
// 						,"1" => 2
// 				)
		
// 				,"place_a" => Array
// 				(
// 						"0" => 4
// 						,"1" => 4
// 				)
		
// 				,"place_d" => Array
// 				(
// 						"0" => 1
// 						,"1" => 1
// 				)
		
// 				,"war_name" => Array
// 				(
// 						"0" => 1
// 						,"1" => 3
// 						,"2" => 1
// 				)
		
// 				,"war_act" => Array
// 				(
// 						"0" => 1
// 						,"1" => 2
// 						,"2" => 1
// 				)
		
// 				,"war_a" => Array
// 				(
// 						"0" => 4
// 						,"1" => 4
// 						,"2" => 4
// 				)
		
// 				,"war_d" => Array
// 				(
// 						"0" => 1
// 						,"1" => 1
// 						,"2" => 1
// 				)
		
// 				,"war_skill_id" => Array
// 				(
// 						"0" => 0
// 						,"1" => 1
// 						,"2" => 0
// 				)
		
// 				,"war_af" => Array
// 				(
// 						"0" => 100
// 						,"1" => 200
// 						,"2" => 50
// 				)
		
// 				,"war_crit" => Array
// 				(
// 						"0" => 0
// 						,"1" => 0
// 						,"2" => 1
// 				),
// 		);
		
		$monster_list_tmp=$cont->obj->monster_list();
		$monster_list=array();
		foreach($monster_list_tmp as $key=>$value)
		{
			$property=arrayed($value["property"]);
			$monster_list[$value["id"]]=$value;
		}
		
		//战报结构
		$battlefield=array(
				"battlefield"=>array(),
				"start_obj"=>array(
									"attack"=>array(),
									"monster"=>array()
								)
				);
		
		//布置阵型
		for($i=1;$i<10;$i++)
		{
			if($post["A".$i]>0)
			{
				$A=$monster_list[$post["A".$i]];
				$property=arrayed($A["property"]);
				$property["af_top"]=$property["af"];
				$A["property"]=$property;
				$battlefield["start_obj"]["attack"][$i]=array(1,$A);
			}else{
				$battlefield["start_obj"]["attack"][$i]=array(0,0);
			}
			
			if($post["B".$i]>0)
			{
				$B=$monster_list[$post["B".$i]];
				$property=arrayed($B["property"]);
				$property["af_top"]=$property["af"];
				$B["property"]=$property;
				$battlefield["start_obj"]["monster"][$i]=array(1,$B);
			}else{
				$battlefield["start_obj"]["monster"][$i]=array(0,0);
			}
		}
		
		//index 1 战斗 2剧情 3高亮
		$i=$j=$k=0;
		foreach($post["index"] as $key=>$value)
		{
			if($value==1)//1 战斗
			{
				$attack=$battlefield["start_obj"]["attack"];
				$defend=$battlefield["start_obj"]["monster"];
				$p_a=$post["war_a"][$i];
				$p_d=$post["war_d"][$i];
				
				if($post["war_skill_id"][$i]>0)
				{
					$type=2;//1普通攻击 2技能攻击 3无双攻击
				}else{
					$type=1;//1普通攻击 2技能攻击 3无双攻击
				}
				
				$battlefield_data=array(
						"bout"=> 1,
						"who"=> $post["war_act"][$i],
						"skillid"=> $post["war_skill_id"][$i],
						"type"=> $type,
						"info"=> array(),
					);
				
				$fortype=array(
							2=>array(1,2,3),
							3=>array(4,5,6),
							4=>array(7,8,9),
							5=>array(1,4,7),
							6=>array(2,5,8),
							7=>array(3,6,9),
							8=>array(1,2,3,4,5,6,7,8,9),
						);
				
				if($post["war_name"][$i]==1)
				{
						$battlefield_data["info"][]=array(
											"attack_anger"=> 25,
											"defend_anger"=> 25,
											"attack_location"=> $p_a,
											"defend_location"=> $p_d,
											"attack_warriors"=> 100,
											"defend_warriors"=> 110,
											"hurt"=> $post["war_af"][$i],
											"at_af"=> $attack[$p_a][1]["property"]["af"][1],
											"de_af"=> $defend[$p_d][1]["property"]["af"][1]-$post["war_af"][$i],
										);

				}else{
					foreach($fortype[$post["war_name"][$i]] as $wkey =>$value)
					{
						if($post["war_act"][$i]==2){
							if($attack[$value][1]==0){
								continue;
							}
							
							$battlefield_data["info"][]=array(
									"attack_anger"=> 25,
									"defend_anger"=> 25,
									"attack_location"=> $value,
									"defend_location"=> $p_d,
									"hurt"=> $post["war_af"][$i],
									"at_af"=> $attack[$value][1]["property"]["af"][1],
									"de_af"=> $defend[$p_d][1]["property"]["af"][1]-$post["war_af"][$i],
							);
						}else{
							if($defend[$value][1]==0){
								continue;
							}
							$battlefield_data["info"][]=array(
									"attack_anger"=> 25,
									"defend_anger"=> 25,
									"attack_location"=> $p_a,
									"defend_location"=> $value,
									"hurt"=> $post["war_af"][$i],
									"at_af"=> $attack[$p_a][1]["property"]["af"][1],
									"de_af"=> $defend[$value][1]["property"]["af"][1]-$post["war_af"][$i],
							);
						}
							
							
					}
				}
				$i++;
			}elseif($value==2){//2剧情
				$battlefield_data=array("type"=> 9,"plot_id"=> $post["plot_id"][$j]);
				$j++;
			}else{//3高亮
				
				$battlefield_data=array("type"=> 8,"who"=> $post["place_act"][$k],"info"=>array());
				
				if($post["place_name"][$k]==1)
				{
				
					$battlefield_data["info"][]=array(
								          "attack_location"=> $post["place_a"][$k],
								          "defend_location"=> $post["place_d"][$k]
									);
				}else{//多个
					$attack=$battlefield["start_obj"]["attack"];
					$defend=$battlefield["start_obj"]["monster"];
					
					$fortype=array(
							2=>array(1,2,3),
							3=>array(4,5,6),
							4=>array(7,8,9),
							5=>array(1,4,7),
							6=>array(2,5,8),
							7=>array(3,6,9),
							8=>array(1,2,3,4,5,6,7,8,9),
					);
					
					foreach($fortype[$post["place_name"][$k]] as $pkey =>$value)
					{
						if($post["place_act"][$k]==2){
							if($attack[$value][1]==0){
								continue;
							}
							
							$battlefield_data["info"][]=array(
									"attack_location"=> $value,
									"defend_location"=> $post["place_d"][$k]
							);
							
						}else{
							if($defend[$value][1]==0){
								continue;
							}
							
							$battlefield_data["info"][]=array(
									"attack_location"=> $post["place_a"][$k],
									"defend_location"=> $value
							);
						}
						
					}
				}
				$k++;
			}
			
			$battlefield["battlefield"][$key]=$battlefield_data;
		}
		
		$battlefield["start_obj"]["attack"]=$this->yesdata($battlefield["start_obj"]["attack"],2);
		$battlefield["start_obj"]["monster"]=$this->yesdata($battlefield["start_obj"]["monster"],2);
		print_r(json_encode($battlefield));
	}
	
	function yesdata($data,$mtype)
	{
        /**
        * @info 取出合法的属性
        * @param int data 战斗数据
        * @param dict mtype 类型 1人物 2怪物
        * @return 奖励数据
        */
        $newprodata=array();
        foreach($data as $key =>$value)
        {
            if($data[$key][1]){
                $newprodata[$key]=array(
                                "lv"=>intval($data[$key][1]["lv"]),
                                "name"=>$data[$key][1]["name"],
                                "af_top"=>$data[$key][1]["property"]["af_top"][1],
                                "af"=>$data[$key][1]["property"]["af"][1],
                                "ag"=>$data[$key][1]["property"]["ag"][1],
                    );
                
                if($mtype==1){
                    $newprodata[$key]["base_hero_id"]=intval($data[$key][1]["base_hero_id"]);
                    if($data[$key][1]["main"]==1){
                        $newprodata[$key]["main"]=1;
                        $newprodata[$key]["quality"]=intval($data[$key][1]["quality"]);
                        $newprodata[$key]["warriors_id"]=intval($data[$key][1]["warriors_id"]);
                    }else{
                    	$newprodata[$key]["monster_id"]=intval($data[$key][1]["monster_id"]);
                    	$newprodata[$key]["skill"]=intval($data[$key][1]["skill"]);
                    }
                }else{
					$newprodata[$key]["monster_id"]=intval($data[$key][1]["monster_id"]);
                    $newprodata[$key]["skill"]=intval($data[$key][1]["skill"]);
				}
            }
        }
        return $newprodata;
	}
}
?>