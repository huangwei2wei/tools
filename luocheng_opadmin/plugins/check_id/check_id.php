<?php
namespace plugins\check_id;
use controllers\controllers as CONT;

class check_id{

//	function check_list($obj)
//	{
//		$obj->_bug=0;
//		$cont=new CONT("check_id");
//		$get=$obj->get;
//		$get["page"]=isset($get["page"])?intval($get["page"]):1;
//		$get["page"]=$get["page"]<=0?1:$get["page"];
//		$check_list=$cont->obj->check_list($get["page"]);
//		$check=$cont->obj->getcheck($post['$nickname']);
//		
////		if($getcheck)
////		{ 
////		  $currentPage= $get['page'];
////		  if(!$currentPage or isset($post['$nickname']) )
////		  {
////		      $currentPage=1;
////		  }
////		$this->foo($check);
////		$check_page =spage($goods_ary5,16,$currentPage); 
////		$page_string=pageft($currentPage,$check_page['totalPage']);  
////		
//		
////		
////		
////		
////			if (is_array($goods_page))
////	{
////		foreach ($goods_page['content'] as $key => $data)
////		{
////		
//		$data=$check_list["content"];
//		$pa=pageft($get["page"], $check_list["totalPage"]);
////		}
////	}
////		}
//		require_once parse_tmp("/check/check.html");//引入模版
//	}
//	
//	
	
	
	
	function check_list($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("check_id");
		$get=$obj->get;
		$get["page"]=isset($get["page"])?intval($get["page"]):1;
		$get["page"]=$get["page"]<=0?1:$get["page"];
		$check_list=$cont->obj->check_list($get["page"]);
		$data=$check_list["content"];
		$pa=pageft($get["page"], $check_list["totalPage"]);
		require_once parse_tmp("/check/check.html");//引入模版
	}
}