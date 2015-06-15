<?php
define('D_R_',__FILE__ ?	getdirname_(__FILE__):'./');
require_once(D_R_."kernel/base/lib/public.php");

function getdirname_($path)
{
	if(strpos($path,'\\')!==false)
	{
		$dir=substr($path,0,strrpos($path,'\\'));
		
		$dir=str_replace('kernel\base\tool','',$dir);
		return $dir;
	}elseif(strpos($path,'/')!==false)
	{
		$dir=substr($path,0,strrpos($path,'/'));
		$dir=str_replace('kernel/base/tool','',$dir);
		return $dir;
	}else{
		return '/';
	}
}


//系统初始化将 base_map_data表装进mongo中
class initialization
{
	function start()
	{
		global $mongo;
		
		$arr=dbarr("SELECT * FROM `base_map_data` WHERE 1 order by id asc");
		foreach($arr as $key=>$value)
		{
			foreach($value as $k=>$v)
			{
				if(is_numeric($k))
				{
					unset($arr[$key][$k]);
				}
			}
		}
		
		foreach($arr as $key=>$value)
		{
			$mongo->mongo->insert("map_data",$value);
		}
	}
}
?>