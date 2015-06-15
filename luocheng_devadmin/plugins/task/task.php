<?php
namespace plugins\task;
use controllers\controllers as CONT;
class task{
	
	function detect_task($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("task");
		$detect_task_list=$cont->obj->detect_task();
		$base3_task=$detect_task_list["base3_task"];
		$base3_task_err=array();
		foreach($base3_task as $key=>$value)
		{
			$detects=$this->detects($value["goods_award"]);
			if($detects)//有错误
			{
				$base3_task_err[$value["id"]]["goods_award"][]=$detects;
			}
			
			$detects=$this->detects($value["res_award"]);
			if($detects)//有错误
			{
				$base3_task_err[$value["id"]]["res_award"][]=$detects;
			}
		}
		
		$base3_task_condition=$detect_task_list["base3_task_condition"];
		$base3_task_condition_err=array();
		foreach($base3_task_condition as $key=>$value)
		{
			$detects=$this->detects($value["condition"]);
			if($detects)//有错误
			{
				$base3_task_condition_err[$value["id"]]["condition"][]=$detects;
			}
		}
		
		require_once parse_tmp("/task/task.html");//引入模版
	}
	
	function detects($str)
	{
		$tproperty=explode(",",$str);
		foreach ($tproperty as $key1=>$value1)
		{
			$tv1=explode(":",$value1);
			if(count($tv1)!=3 or $tv1[2]=="")
			{
				return str_replace($value1,"<font color='#FF0000'>".$value1."</font>",$str);
			}
		}
		return false;
	}
	
	function task_change($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("task");
		$detect_task_list=$cont->obj->task_change();
		print("转换成功");
	}
	
	//************************************************统计在线人数*********************************************//
	function index($obj)
	{
		/*
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$get["page"]=isset($get["page"])?intval($get["page"]):1;
		$get["page"]=$get["page"]<=0?1:$get["page"];
			
		$time=isset($get["time"])?$get["time"]:"";
		
		$user_list=$cont->obj->user_list($time,$get["page"]);
	
		require_once parse_tmp("/user/user_list.html");//引入模版
		*/
	}
	
	function uplv($lvdata,$user_lv,$user_exp,$exp)
	{
		$exp+=$user_exp;
		
		while(1){
			if($exp>=$lvdata[$user_lv])
			{
				$exp-=$lvdata[$user_lv];
				$user_lv+=1;
			}else{
				return array("lv"=>$user_lv,"exp"=>$exp);
			}
		}
	}
	
	function experience($obj)
	{
		$user_lv=1;
		$user_exp=0;
		
		$obj->_bug=0;
		$cont=new CONT("task");
		$data=$cont->obj->experience();
		$map=$data[0];
		$lvdata=$data[1];
		$monster_list=$data[2];
		$taskdata=$data[3];
		$echodata=array();//array("task_name"=>"起始","task_exp"=>100,"copy_name"=>"","copy_exp"=>100,"user_lv"=>1,"user_exp"=>100)
		foreach($taskdata as $value)
		{
			//副本处理
			if($value["condition_type"]==1)
			{
				$condition=arrayed($value["condition"]);
				$map_id=key($condition);
				if(isset($map[$map_id])==false)
				{
					$map_experience=0;
					$map_name="";
					echo $map_id."副本ID错误<br>";
				}else{
					$map_experience=$map[$map_id];
					
					$monster_id=arrayed($monster_list[$map_id]["monster_id"]);
					$maxlv=0;
					foreach($monster_id as $k=>$v)
					{
						if($v[1]>$maxlv)$maxlv=$v[1];
					}
					$map_name=$monster_list[$map_id]["city_name"]."->".$monster_list[$map_id]["monster_name"]." LV:".$maxlv;
				}
			}else{
				$map_experience=0;
				$map_name="";
			}
			
			$rundata=$this->uplv($lvdata,$user_lv,$user_exp,$value["experience_award"]+$map_experience);
			$user_lv=$rundata["lv"];
			$user_exp=$rundata["exp"];
			$echodata[]=array(
						"task_name"=>$value["name"],
						"task_exp"=>$value["experience_award"],
						"copy_name"=>$map_name,
						"copy_exp"=>$map_experience,
						"user_lv"=>$user_lv,
						"user_exp"=>$user_exp,
						"id"=>$value["id"],
				);	
		}
		
		
		require_once parse_tmp("/task/experience.html");//引入模版
	}
	
	function upexp($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("task");
		$run=$cont->obj->upexp($get["query_value"],$get["filed"],$get["data"]);
		if($run)
		{
			echo 1;
		}else{
			echo 0;
		}		
	}
}
?>