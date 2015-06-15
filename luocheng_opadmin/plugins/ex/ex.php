<?php
namespace plugins\ex;
use controllers\controllers as CONT;

class ex{

	function index($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("ex");
		
		$on_user_sum=$cont->obj->addex();
	}
}
?>