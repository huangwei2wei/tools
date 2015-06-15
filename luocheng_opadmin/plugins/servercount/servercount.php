<?php
namespace plugins\servercount;
use controllers\controllers as CONT;
class servercount{
	//运营统计
	function operations($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("servercount");
		$post=$obj->post;
		$get=$obj->get;
		
		$server_list=$cont->obj->server_list();
		
		$operations=array();
		if($get["time"]==""){
			$get["time"]=date("Y-m",time());
			$get['server_id']=$server_list[0]["server_id"];
		}
		
		if(isset($post["time"]))
		{
			$get["time"]=$post["time"];
			$get['server_id']=$post["server_id"];
		}
		
		$operations=$cont->obj->operations($get);
		
		
		require_once parse_tmp("/servercount/operations.html");//引入模版
	}
	
	//充值统计
	function pay($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("servercount");
		$post=$obj->post;
		$get=$obj->get;
		
		$server_list=$cont->obj->server_list();
		if($get["time"]==""){
			$get["time"]=date("Y-m",time());
		}
		
		if(isset($post["time"]))
		{
			$get["time"]=$post["time"];
		}
		
		$paylist=$cont->obj->pay($get);
		$maxdate=0;
		foreach($paylist as $key=>$value)
		{
			foreach($value as $k=>$v)
				if($k>$maxdate)$maxdate=$k;
		}
		
		require_once parse_tmp("/servercount/pay.html");//引入模版
	}
	
	//推广费用录入
	function spread($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("servercount");
		$post=$obj->post;
		$get=$obj->get;
		if(isset($post["money"]) and $post["money"]>0)
		{
			if($post["_timecount"]==0){
				$cont->obj->add_spread($post);
			}else{
				$cont->obj->up_spread($post);
			}
			header("Location: /?module=servercount&methods=spread");
		}
		$spreadlist=$cont->obj->spread();
		require_once parse_tmp("/servercount/spread.html");//引入模版
	}
}
?>