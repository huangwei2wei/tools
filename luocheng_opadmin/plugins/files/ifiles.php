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
					"base_loadres"=>"xml",
					"base2_monster_lv"=>"xml",
					"base2_plot"=>"xml",
					"base3_novice"=>"xml",
					"base3_secretary"=>"xml",
					"base3_skill_show"=>"xml",
					"base3_skill_state"=>"xml",
// 					"base3_vip_privilege"=>"xml",
					"base4_activity"=>"xml",
					"base4_event_driven"=>"xml",
					"base_user"=>"py",
					"base_updatelog"=>"xml",
				);
		
		$xmlfun=new XMLFUN;
		$dbtoxml->xml=$xmlfun->array_to_xml($xmldata);
		
		
		if(isset($arr[$table]) and $arr[$table]=="xml" and $xmldata){
			$dbtoxml->wpath(D_R."/res/".$this->getmysqldb()."/xml/".$table.".xml");
			return "xml";
		}
		
		if(isset($arr[$table]) and $arr[$table]=="py" and $pydata){
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy(var_export($pydata,true)));
			return "py";
		}
		
		if($xmldata){
			$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/".$table.".xml");
		}
		
		if($pydata){
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy(var_export($pydata,true)));
		}
		return "xmlpy";
	}
	
	function tableisok($table)
	{
		//1手工 2是generate_m函数 3是generate_mongo函数
		$arr=array(
				"user" =>1, 
				"base2_monster" =>1,
				"base2_monster_list" =>1, 
				"base3_task" =>1, 
				"base1_hero_addition"=>1,
				"base_module"=>1,
				
				"base4_diamond"=>2,
				"base3_reward_points"=>2,
				"base3_star_recorded"=>2,
				"base2_plot"=>2,
				"base_copys"=>2,
				"base_qihai_lv" =>2, 
				"base1_user_lv"  =>2, 
				"base_properties" =>2, 
				"base_quality" =>2, 
				"base_field" =>2, 
				"base2_mine" =>2, 
				"base2_copy_info" =>2, 
				"base2_map_copy"=>2,
				"base3_strengthen"=>2,
				"base4_warriors"=>2,
				"base_loadres"=>2,
				"base4_vips"=>2,
				"base_war"=>2,
				"base1_formation"=>2,
				"base2_map2"=>2,
				"base3_task_qq"=>2,
				"base3_mall"=>2,
				
				"base_reg_conf" =>3,
				"base4_code" =>3,
				
				"base_operators" =>4,
		);
		return $arr[$table];
	}
	
	function topy($data)
	{
		$data=str_replace("array (", "{", $data);
		$data=str_replace(")", "}", $data);
		$data=str_replace("=>", ":", $data);
//		$data=str_replace("\r\n", "<br\>", $data);
//		$data=str_replace("\n", "<br\>", $data);
		
		$ns="#!/usr/local/bin/python
# -*- coding: utf-8 -*-
# Copyright (c) The res Project.
# 资源数据
# See LICENSE.txt for details.\n\n
data=".$data;
		return $ns;
	}
	
	//生成静态数据文件
	function generate($table,$acc)
	{
		$dbtoxml=new DBTOXML;
		if(!$this->tableisok($table))
		{
			//自动化程序处理
			$arr=$dbtoxml->tbtoxml($table);
	               
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
					
					$xmldata["id".$value['id']]=$value;
					$xmldata["id".$value['id']]["info"]=str_replace("<br>","\r\n",$value["info"]);
					$pydata[$value['id']]=$value;
				}
			}
			//$xmldata,$pydata
			$this->xmlandpy($table,$xmldata,$pydata);
			/*$xmlfun=new XMLFUN;
			$dbtoxml->xml=$xmlfun->array_to_xml($new__);
			
			$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/".$table.".xml");
			$stryy='<? $'.$table.'='.var_export($new_,true).';?>';
//			file_put_contents(D_R."res/".$this->getmysqldb()."/data/".$table.".php",$stryy);
			$this->w("/res/".$this->getmysqldb()."/data/".$table.".php",$stryy);
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy(var_export($new_,true)));*/
		}
		
		if($this->tableisok($table)==2)
		{
			switch($table)
			{
				case "base_properties":
					$this->generate_m($table,"flag");
				break;
				
				case "base_qihai_lv":
					$this->generate_m($table,"lv");
				break;
				
				case "base1_user_lv":
					$this->generate_m($table,"lv");
				break;
				case "base_quality":
					$this->generate_m($table,"name_sign");
				break;
				
				case "base_field":
					$this->generate_m($table,"type","field");
				break;
				case "base2_mine":
					$this->generate_m($table,"map_id","id");
				break;
				
				case "base2_copy_info":
					$this->generate_m($table,"copy_id","copy_floor_id");
				break;
				
				
				case "base2_map_copy"://地图怪物表
					$this->generate_m($table,"map_id","[]");
				break;
				
				case "base3_strengthen"://地图怪物表
					$this->generate_m($table,"attribute","count");
				break;
				
				case "base_copys"://副本
					$this->generate_m($table,"type","layer");
				break;
				
				case "base4_warriors"://无双升级
					$this->generate_m($table,"section","order");
				break;
				
				case "base_loadres"://资源加载表
					$this->generate_m($table,"level","id");
				break;
					
				case "base3_star_recorded"://将星录
					$this->generate_m($table,"type","id");
				break;
				
				case "base2_plot"://剧情对话
					$this->generate_m($table,"copy_id","[]");
				break;
				
				case "base3_reward_points"://日常奖励
					$this->generate_m($table,"grade","integral");
				break;
				
				case "base4_vips"://VIP特权
					$this->generate_m($table,"module_id","vip_lvs");
				break;
				
				case "base_war"://战斗
					$this->generate_m($table,"lv","flag");
				break;
				
				case "base4_diamond"://战斗
					$this->generate_m($table,"type","lv");
				break;
				
				case "base1_formation"://阵型
					$this->generate_m($table,"profession","id");
				break;
					
				case "base2_map2"://地图
					$this->generate_m($table,"type","id");
				break;
				
				case "base3_task_qq"://腾讯交叉推广
					$this->generate_m($table,"app_contract_id");
				break;
				
				case "base3_mall":
					$this->generate_m($table,"mall_type","id");
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
			
			$this->w("/res/".$this->getmysqldb()."/pydata/$table.py",$this->topy(var_export($new_,true)));
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
			case "base2_monster":
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
				/*file_put_contents(GAME_DIR."res/".$this->getmysqldb()."/data/base_monster.php",'<? $base_monster='.var_export($newa,true).';?>');*/
				
				$this->xmlandpy($table,$xmldata,$pydata);
				/*
				$this->w("/res/".$this->getmysqldb()."/data/base2_monster.php",'<? $base_monster='.var_export($newa,true).';?>');
				$xmlfun=new XMLFUN;
				$dbtoxml->xml=$xmlfun->array_to_xml($newa_);
				$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/base2_monster.xml");
				$this->w("/res/".$this->getmysqldb()."/pydata/base2_monster.py",$this->topy(var_export($newa,true)));*/
			break;
			
			 case "base2_monster_list":
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base_monster");
				foreach((array)$arr as $key=>$value)
				{
					foreach((array)$arr1 as $k=>$v)
					{
						if($value["monster_name"]==$v["name"])
						{
							$value["row"]=$v["row"];
							$value["col"]=$v["col"];
							break;
						}
					}
					
					$pydata[$value['id']]=$value;
					$xmldata["id".$value['id']]=$value;
				}
				
				$this->xmlandpy($table,$xmldata,$pydata);
				
				/*file_put_contents(GAME_DIR."res/".$this->getmysqldb()."/data/base_monster_list.php",'<? $base_monster_list='.var_export($new_,true).';?>');*/
				/*
				$xmlfun=new XMLFUN;
				$dbtoxml->xml=$xmlfun->array_to_xml($new__);
				$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/base2_monster_list.xml");
				$this->w("/res/".$this->getmysqldb()."/data/base2_monster_list.php",'<? $base2_monster_list='.var_export($new_,true).';?>');
				$this->w("/res/".$this->getmysqldb()."/pydata/base2_monster_list.py",$this->topy(var_export($new_,true)));*/
			break;
			
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
					$pydata[$value['ingroup']][$value['id']]=$value;
					$xmldata["id".$value['ingroup']]["id".$value['id']]=$value;
				}
				
				$this->xmlandpy($table,$xmldata,$pydata);
				/*file_put_contents(GAME_DIR."res/".$this->getmysqldb()."/data/base_task.php",'<? $base_task='.var_export($new_,true).';?>');*/
				/*
				$xmlfun=new XMLFUN;
				$dbtoxml->xml=$xmlfun->array_to_xml($new__);
				$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/base3_task.xml");
				$this->w("/res/".$this->getmysqldb()."/data/base3_task.php",'<? $base3_task='.var_export($new_,true).';?>');
				$this->w("/res/".$this->getmysqldb()."/pydata/base3_task.py",$this->topy(var_export($new_,true)));*/
			break;
			
			case "base1_hero_addition":
				$narr_xml=$narr=array();
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("base1_hero_addition_lv");
				foreach((array)$arr as $key=>$value)
				{
					$pydata[$value["id"]]=$value;
					$xmldata["id".$value["id"]]=$value;
				}
				
				foreach($arr1 as $key=>$value)
				{
					$pydata[$value["ha_id"]]["lv"][$value["lv"]]=$value;
					$xmldata["id".$value["ha_id"]]["lv"]["id".$value["lv"]]=$value;
				}

				$this->xmlandpy($table,$xmldata,$pydata);
				
				/*file_put_contents(GAME_DIR."res/".$this->getmysqldb()."/data/base_hero_addition.php",'<? $base_hero_addition='.var_export($narr,true).';?>');*/
				/*
				$xmlfun=new XMLFUN;
				$dbtoxml->xml=$xmlfun->array_to_xml($narr_xml);
				$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/base1_hero_addition.xml");
				$this->w("/res/".$this->getmysqldb()."/data/base1_hero_addition.php",'<? $base1_hero_addition='.var_export($narr,true).';?>');
				$this->w("/res/".$this->getmysqldb()."/pydata/base1_hero_addition.py",$this->topy(var_export($narr,true)));*/
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
				
				foreach($arr1 as $key=>$value)
				{
					$pydata[$value["module_id"]]["sub"][$value["id"]]=$value;
					$xmldata["id".$value["module_id"]]["sub"]["id".$value["id"]]=$value;
				}
				
				$this->xmlandpy($table,$xmldata,$pydata);
			break;
			
			case "user":
				$arr=$dbtoxml->tbtoxml($table);
				$arr1=$dbtoxml->tbtoxml("user_ext");
				if(!$arr || !$arr1){return false;}
				$string=new STRING;
				$user_type=$string->tabletype($table);
				$user_type1=$string->tabletype("user_ext");
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
				
				$this->xmlandpy("base_user",false,$pydata);
				
				/*file_put_contents(GAME_DIR."res/".$this->getmysqldb()."/data/base_user.php",'<? $base_user='.var_export($newarr,true).';?>');*/
				/*$this->w("/res/".$this->getmysqldb()."/data/base_user.php",'<? $base_user='.var_export($newarr,true).';?>');
				$this->w("/res/".$this->getmysqldb()."/pydata/base_user.py",$this->topy(var_export($newarr,true)));*/
			break;
		}
	}

	/**
	 * 生成数据，复杂点点
	 *
	 */
	function generate_m($table,$key1,$key2=false)
	{
		$dbtoxml=new DBTOXML;
		$arr=$dbtoxml->tbtoxml($table);
		
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
		/*
		$xmlfun=new XMLFUN;
		$dbtoxml->xml=$xmlfun->array_to_xml($new_xml);
		$dbtoxml->wpath(D_R."res/".$this->getmysqldb()."/xml/".$table.".xml");
		$this->w("/res/".$this->getmysqldb()."/pydata/".$table.".py",$this->topy(var_export($new_arr,true)));*/
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
	
	function deletemysql()
	{
		sql("DELETE FROM `base4_code` WHERE `operators_id`=".$_SESSION["operators_id"]." and `server_id`=".$_SESSION["server_id"]);
	}
}
?>