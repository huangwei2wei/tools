<?php
namespace plugins\mall;
use controllers\controllers as CONT;

class mall{
	function index($obj)
	{
		$arr=array(1=>"装备",2=>"材料",3=>"消耗",4=>"宝石",5=>"宝物",6=>"热卖",7=>"VIP礼包",8=>"限时抢购");
		$page=isset($obj->get["page"])?$obj->get["page"]:0;
		$type=isset($obj->get["type"])?$obj->get["type"]:2;
		$cont=new CONT("mall");
		$mall_=$cont->obj->mall($type,$page);
		$mall=$mall_[0];
		$p=$mall_[1];
		$goodsname=$mall_[2];
		require_once parse_tmp("/mall/mall.html");//引入模版
	}
	
	//修改商城数据
	function upmall($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("mall");
		$cont->obj->upmall($get);
		echo 1;
	}
	
	function uiaddmall($obj)
	{
 		$scriptarr="";
		$cont=new CONT("mall");
		$getextgoods=$cont->obj->getextgoods($obj->get["type"]);
		if($getextgoods)
		{
			foreach($getextgoods as $value)
			{
				$scriptarr.='js_goods_type['.$value['goods_ext_id'].']='.$value["goods_type"].';'."\n";
			}
		}
		$mall_id=intval($obj->get["mall_id"]);
 		//$ttttype=$obj->get["type"]>=100?2:1;
		$ttttype=$obj->get["type"];
		require_once parse_tmp("/mall/addmall.html");//引入模版
	}
	
	function addmall($obj)
	{
		$cont=new CONT("mall");
		$obj->post["mall_type"]=1;
		$getextgoods=$cont->obj->addmall($obj->post);
	}
	
	function delmall($obj)
	{
		$cont=new CONT("mall");
		$getextgoods=$cont->obj->delmall($obj->get["gid"]);
	}
	
	function sell_situation($obj)
	{
		$cont=new CONT("mall");
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m-d",time()-86400);
			$time2=date("Y-m-d",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();	
		}
		$goods_ext_list=$cont->obj->get_goods_ext_list();
		$sell_situation=$cont->obj->sell_situation($time1,$time2);
		require_once parse_tmp("/mall/sell_situation.html");//引入模版
	}
}
?> 