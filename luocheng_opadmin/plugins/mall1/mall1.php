<?php
namespace plugins\mall1;
use controllers\controllers as CONT;

class mall1{
	function index($obj)
	{
		$arr=array(6=>"武器",1=>"防具",2=>"坐骑",3=>"披风",4=>"秘籍",5=>"兵符",7=>"稀有物品");
		$page=isset($obj->get["page"])?$obj->get["page"]:0;
		$type=isset($obj->get["type"])?$obj->get["type"]:6;
		$cont=new CONT("mall1");
		$mall_=$cont->obj->mall($type,$page);
		$mall=$mall_[0];
		$p=$mall_[1];
		require_once parse_tmp("/mall1/mall.html");//引入模版
	}
	
	//修改商城数据
	function upmall($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		
		$cont=new CONT("mall");
		$run=$cont->obj->upmall($get["id"],$get["field"],$get["value"]);
		if($run)
		{
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function uiaddmall($obj)
	{
		$scriptarr="";
		$cont=new CONT("mall1");
		$getextgoods=$cont->obj->getextgoods($obj->get["type"]);
		if($getextgoods)
		{
			foreach($getextgoods as $value)
			{
				$scriptarr.='tttttt['.$value['goods_ext_id'].']=new Array('.$value["gold"].",".$value["copper"].",".$value["sport"].",".$value["sub_type"].');'."\n";
			}
		}
		$mall_id=intval($obj->get["mall_id"]);
		$ttttype=$obj->get["type"]>=100?2:1;
		require_once parse_tmp("/mall1/addmall.html");//引入模版
	}
	
	function addmall($obj)
	{
		$cont=new CONT("mall1");
		$getextgoods=$cont->obj->addmall($obj->post);
	}
	
	function delmall($obj)
	{
		$cont=new CONT("mall1");
		$getextgoods=$cont->obj->delmall($obj->get["gid"]);
	}
}
?>