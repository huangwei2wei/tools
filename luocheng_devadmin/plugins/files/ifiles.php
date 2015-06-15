<?php 
namespace plugins\files;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\tool\dbtoxml as DBTOXML;
use kernel\base\tool\xmlfun as XMLFUN;
use kernel\base\base_class\api_file as AF;
	//***********************************更新基表转文件*******************************************//
set_time_limit(300);

class ifiles extends module
{
	function xmlandpy($table,$xmldata,$pydata)
	{
		$dbtoxml=new DBTOXML;
		$arr=array(
					"base3_novice"=>"xml",
					"base_user_data"=>"py",
				);
		
		$xmlfun=new XMLFUN;
		$dbtoxml->xml=$xmlfun->array_to_xml($xmldata);
		
		
		if(isset($arr[$table]) and $arr[$table]=="xml" and $xmldata){
			$dbtoxml->wpath(D_R."/res/".$this->getmysqldb()."/xml/".$table.".xml");
			return "xml";
		}
		
		if(isset($arr[$table]) and $arr[$table]=="py" and $pydata){
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy($pydata));
			$this->w("/res/".$this->getmysqldb()."/jsondata/$table.json",$this->tojson($pydata));
			return "py";
		}
		
		if($xmldata){
			$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/".$table.".xml");
		}
		
		if($pydata){
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy($pydata));
			$this->w("/res/".$this->getmysqldb()."/jsondata/$table.json",$this->tojson($pydata));
		}
		return "xmlpy";
	}
	
	function tableisok($table)
	{
		//1手工 2是generate_m函数 3是generate_mongo函数
		$arr=array(
				"base_user_data" =>1, 
				//"base2_monster" =>1,
				//"base2_monster_list" =>1, 
				"base3_task" =>1, 
				"base_module"=>1,
				"base1_map_copy_drop"=>1,
				"base1_map_copy_drop_global"=>1,
				"base1_treasure_drop"=>1,
				
				"base1_map1"  =>2,
				"base1_user_lv"  =>2, 
				//"base1_hero_lv"=>2,
				"base_properties" =>2, 
				"base1_map_info"=>2,
				"base3_mall"=>2,
				"base3_vips"=>2,
				"base1_hero_ext"=>2,
				//"base_team_lv"=>2,
				"base3_skill_lv"=>2,
				"base3_skill_properties"=>2,
				"base1_map_scene_conf"=>2,
				"base_guild_goods"=>2,
				"base_guild_skill"=>2,
				"base1_map_copy_guild"=>2,
				"base_online_worship"=>2,
				"base_retrieve"=>2,
				"base3_quartermaster"=>2,
				
				"base_reg_conf" =>3,
				"base4_code" =>3,

				"base_operators" =>4,
				
		);
		return $arr[$table];
	}
	
	function topy($data)
	{
		//var_export(json_encode($data),true);
		$data=var_export($data,true);
		
		$data=str_replace("array (", "{", $data);
		$data=str_replace("),", "},", $data);
		$data=str_replace("=>", ":", $data);
		$data=substr($data,0,-1)."\n}";

		$ns="#!/usr/local/bin/python3
# -*- coding: utf-8 -*-
# 资源数据
# See LICENSE.txt for details.\n\n
data=".$data;
		return $ns;
	}
	
	function tojson($data)
	{
		//var_export(json_encode($data),true);
		$data=var_export($data,true);
		
		$data=str_replace("array (", "{", $data);
		$data=str_replace("),", "},", $data);
		$data=str_replace("=>", ":", $data);
		$data=substr($data,0,-1)."\n}";
		return $data;
	}
	
	//生成静态数据文件
	function generate($table,$acc,$stor=false)
	{
		$dbtoxml=new DBTOXML;
		if(!$this->tableisok($table))
		{
			//自动化程序处理
			$arr=$dbtoxml->tbtoxml($table,$stor);
	        
			foreach((array)$arr as $key=>$value)
			{
				if($table=="base_goods_ext")
				{
					$xmldata["id".$value['goods_ext_id']]=$value;
					$pydata[$value['goods_ext_id']]=$value;
				}else{
					if($table=="base_msg_word")
					{
						$value["info"]=str_replace(array("\r\n","\r","\n"),array("<br>","<br>","<br>"),$value["info"]);
					}
					
					if($table=="base_parameter")
					{
						$value["parameter"]=str_replace(array("\r\n","\r","\n"),array("","",""),$value["parameter"]);
					}
					
					$xmldata["id".$value['id']]=$value;
					$xmldata["id".$value['id']]["info"]=str_replace("<br>","\r\n",$value["info"]);
					$pydata[$value['id']]=$value;
				}
			}
			
			//$xmldata,$pydata
			$this->xmlandpy($table,$xmldata,$pydata);

		}
		
		if($this->tableisok($table)==2)
		{
			switch($table)
			{
				case "base_properties":
					$this->generate_m($table,"flag");
					break;
				
				case "base1_hero_lv":
					$this->generate_m($table,"lv");
					break;
				
				case "base1_user_lv":
					$this->generate_m($table,"lv");
					break;

				case "base1_map_info"://地图怪物表
					$this->generate_m($table,"map_id","[]");
					break;
				
				case "base3_vips"://VIP特权
					$this->generate_m($table,"module_id","vip_lvs");
					break;
				case "base1_hero_ext"://英雄扩展
					$this->generate_m($table,"hero_ext_id");
					break;
				case "base1_map1"://地图
					$this->generate_m($table,"type","id");
					break;
				
				case "base3_mall":
					$this->generate_m2($table,"mall_type","lv","id");
					break;
				
				case "base1_map_copy_drop":
					$this->generate_m($table,"copy_id","[]");
					break;
				/*
				case "base_team_lv":
					$this->generate_m($table,"hero_lv_top");
				break;*/
				
				case "base3_skill_lv":
					$this->generate_m($table,"lv");
					break;
				case "base3_skill_properties":
					$this->generate_m($table,"skill_ext_id","[]");
					break;
				case "base1_map_scene_conf":
					$this->generate_m($table,"scene_id","[]");
					break;
				case "base_guild_goods":
					$this->generate_m($table,"store_lv","[]");
					break;
				case "base_guild_skill":
					$this->generate_m($table,"skill_base_id");
					break;
				case "base1_map_copy_guild":
					$this->generate_m($table,"id");
					break;
				case "base_online_worship":
					$this->generate_m($table,"week");
					break;
				case "base_retrieve":
					$this->generate_m($table,"copy_id");
					break;
				case "base3_quartermaster":
					$this->generate_m($table,"type","id");
					break;
			}
			if(isset($acc) && $acc){echo time();exit;}
		}
		
		//mongo
		if($this->tableisok($table)==3)
		{
			$this->generate_mongo($table);
			if(isset($acc) && $acc){echo time();exit;}
		}
		
		if($this->tableisok($table)==4)
		{
			$arr=dbarr("SELECT `id`,`identifier` FROM `operators` WHERE 1 ORDER BY  `id` ASC",0,"operators");
			
			foreach((array)$arr as $key=>$value)
			{
				$new_[$value['identifier']]=$value['id'];
			}
			
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy($new_));
			$this->w("/res/".$this->getmysqldb()."/jsondata/$table.json",$this->tojson($new_));
			if(isset($acc) && $acc){
				echo time();exit;
			}
		}
		
		//手工处理
		$this->handwork($table);
		
		if(isset($acc) && $acc){echo time();exit;}
	}
	
	//手工处理
	function handwork($table)
	{
		$dbtoxml=new DBTOXML;
		switch($table)
		{
/*			case "base2_monster":
				$arr=$dbtoxml->tbtoxml("base2_monster");
				$arr1=$dbtoxml->tbtoxml("base2_monster_lv");
				foreach((array)$arr as $key=>$value)
				{
					$value["lv"]=array();
					$pydata[$value['id']]=$value;
					$xmldata["id".$value['id']]=$value;
				}
				
				foreach ((array)$arr1 as $k=>$v)
				{
					$pydata[$v["monster_id"]]["lv"][$v["lv"]]=$v;
					$xmldata["id".$v["monster_id"]]["id".$v["lv"]]=$v;
				}
				
				
				$this->xmlandpy($table,$xmldata,$pydata);

			break;
			
			 case "base2_monster_list":
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base_monster");
				foreach((array)$arr as $key=>$value)
				{
					$pydata[$value['id']]=$value;
					$xmldata["id".$value['id']]=$value;
				}
				
				$this->xmlandpy($table,$xmldata,$pydata);
				

			break;*/
			
			case "base3_task":
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base3_task_condition");
				foreach((array)$arr1 as $key=>$value)
				{
					$tc[$value["task_id"]][$value["order"]]=$value;
				}
			
				foreach((array)$arr as $key=>$value)
				{
					$value["condition"]=$tc[$value['id']];
					$pydata[$value['id']]=$value;
					$xmldata["id".$value['id']]=$value;
				}
				
				$this->xmlandpy($table,$xmldata,$pydata);
			break;
			
			case "base_module":
				$narr_xml=$narr=array();
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base_module_sub");

				foreach((array)$arr as $key=>$value)
				{
					$pydata[$value["id"]]=$value;
					$xmldata["id".$value["id"]]=$value;
				}
				
				$module_id=array();
				foreach($arr1 as $key=>$value)
				{
					$module_id[$value["id"]]=$value["module_id"];
					
					$pydata[$value["module_id"]]["sub"][$value["id"]]=$value;
					$xmldata["id".$value["module_id"]]["sub"]["id".$value["id"]]=$value;
				}
	
				
				$this->xmlandpy($table,$xmldata,$pydata);
			break;
			
			case "base_user_data":
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base_user_ext");
				if(!$arr || !$arr1){return false;}
				$string=new STRING;
				$user_type=$string->tabletype($table);
				$user_type1=$string->tabletype("base_user_ext");
				$user_type["ext"]=$user_type1;
				$newarr=array();
				
				foreach($arr[0] as $key=>$value)
				{
					$pydata[$key]=$user_type[$key];
				}
				
				foreach($arr1[0] as $key=>$value)
				{
					$pydata["ext"][$key]=$user_type["ext"][$key];
				}
				
				$this->xmlandpy("base_user_data",false,$pydata);
				break;
			case "base1_map_copy_drop":
				$this->generate_m($table,"copy_id","id",true);
				
			break;
			case "base1_map_copy_drop_global":
				$this->generate_m($table,"id",false,true);
				
			break;
			case "base1_treasure_drop":
				$this->generate_m($table,"id",false,true);
			break;
			
		}
	}

	/**
	 * 生成数据，复杂点点
	 *
	 */
	function generate_m($table,$key1,$key2=false,$stor=false)
	{
		$dbtoxml=new DBTOXML;
		$arr=$dbtoxml->tbtoxml($table,$stor);
		
		foreach((array)$arr as $key=>$value)
		{
			if($key2=="[]")
			{
				$pydata[$value[$key1]][]=$value;
				$xmldata["id".$value[$key1]]["id".$value["id"]]=$value;
			}elseif($key2){
				$pydata[$value[$key1]][$value[$key2]]=$value;
				$xmldata["id".$value[$key1]]["id".$value[$key2]]=$value;
			}else{
				$pydata[$value[$key1]]=$value;
				$xmldata["id".$value[$key1]]=$value;
			}
		}

		$this->xmlandpy($table,$xmldata,$pydata);

		return $new_arr;
	}
	
	function generate_m2($table,$key1,$key2=false,$key3=false,$stor=false)
	{
		$dbtoxml=new DBTOXML;
		$arr=$dbtoxml->tbtoxml($table,$stor);
		
		foreach((array)$arr as $key=>$value)
		{
			$pydata[$value[$key1]][$value[$key2]][$value[$key3]]=$value;
			$xmldata["id".$value[$key1]]["id".$value[$key2]]["id".$value[$key3]]=$value;
		}

		$this->xmlandpy($table,$xmldata,$pydata);

		return $new_arr;
	}
	
	
	
	/**
	 * 生成mongo数据，复杂点点
	 *
	 */
	function generate_mongo($table)
	{
		$string=new STRING;
		global $mem,$mongo;
		$mongo->mongo->delete($table,array());
		if($table=="base4_code")
		{
			$row=sql("SELECT * FROM `base4_code` WHERE 1");
			if($row)
			{
				while($value=$row->fetch_array())
				{
					$newvalue=array("code"=>$value["code"],"spree_id"=>intval($value["spree_id"]),"status"=>intval($value["status"]));
					$mongo->mongo->insert(str_replace(array("base_","base1_","base2_","base3_","base4_"), "",$table),$newvalue);
				}
			}
		}else{
			$arr=$string->mongoarr($table);
			if($arr)
			{
				foreach($arr as $value)
				{
					$mongo->mongo->insert(str_replace(array("base_","base1_","base2_","base3_","base4_"), "",$table),$value);
				}
			}
		}
	}
	
	function w($path,$data)
	{
		$af=new AF;
		$af->w($path,$data);
	}
	
	function deletecode()
	{
		global $mem,$mongo;
		$mongo->mongo->delete("code");
	}
}
?>