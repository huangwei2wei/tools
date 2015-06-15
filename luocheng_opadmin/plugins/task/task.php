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
}
?>