<?php
//ERROR_REPORTING(7);
session_start();
define('D_R',__FILE__ ?	getdirname(__FILE__):'./');

function getdirname($path)
{

	if(strpos($path,'\\')!==false)
	{
		$dir=substr($path,0,strrpos($path,'\\'));
		$dir=str_replace('kernel\base\lib','',$dir);
		return $dir;
	}elseif(strpos($path,'/')!==false)
	{
		$dir=substr($path,0,strrpos($path,'/'));
		$dir=str_replace('kernel/base/lib','',$dir);
		return $dir;
	}else{
		return '/';
	}
}

require_once("function.php");

require_once(D_R.'config/config_storage.php');

require_once(D_R.'kernel/config/config.php');

require_once(D_R.'config/config_module.php');

require_once(D_R.'config/config.php');

require_once(D_R.'kernel/base/sql/sql.php');

require_once(D_R.'kernel/open-flash-chart-2/OFC/open-flash-chart-object.php');

require_once(D_R."kernel/temprivate/parse_tmp.php"); //引入模板

use controllers\controllers as CONT;

if(isset($cont_x))
{
	$parse_tmp=new parse_tmp;
	
	$controllers=new CONT;
	
	$controllers->start();
}
?>