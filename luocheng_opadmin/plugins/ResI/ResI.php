<?php
namespace plugins\ResI;
use controllers\controllers as CONT;
class ResI
{
	//列出用户资源
	function Resi($obj)
	{
		$cont=new CONT("ResI");
		$goods_list=$cont->obj->goodslist($post);
		$quality_color=array(0=>"",1=>"color:#999999",2=>"color:#009900",3=>"color:#0000FF",4=>"color:#CC00FF",5=>"color:#FF9900");
		require_once parse_tmp("/ResI/issuance.html");//引入模版
	}
	
	
	//更新用户资源
	function updateResi($obj)
	{
		$cont=new CONT("ResI");
		$post=$obj->post;
		$cont->obj->updateResi($post);
		header("Location: /?module=ResI&methods=Resi");
	}
	
	//发邮件
	function addmailRes($obj)
	{
		$cont=new CONT("ResI");
		$post=$obj->post;
		if($post["way"]==1){//邮箱发送
			$cont->obj->addmailRes($post);
		}else{//背包发送
			$cont->obj->addgoods($post);
		}

		//header("Location: /?module=ResI&methods=Resi");
	}
}