<?php
namespace plugins\timer;
use controllers\controllers as CONT;
class timer{
	
	function management($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("timer");
		$management=$cont->obj->management();

		require_once parse_tmp("/macro_data/timer.html");//引入模版
	}
	
	function artificial($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("timer");
		$post=$obj->post;
		$get=$obj->get;
		$cont->obj->artificial($get);
		print("成功");
	}
	
	
}
?>