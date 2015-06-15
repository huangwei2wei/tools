<?php
namespace plugins\api;
use controllers\controllers as CONT;

class api{
	function index($obj)
	{
		$title="接口::56uu充值中心";
	}
	
	/**
	 * API mysql跨服务器调度
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	function api_sql($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("api");
		$run=$cont->obj->api_sql($sql,$server);
		return $run;
	}

	/**
	 * API mysql跨服务器调度  分页
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	function api_sql_p($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("api");
		$run=$cont->obj->api_sql_p($sql,$server);
		return $run;
	}
	
	/**
	 * 执行API mysql跨服务器
	 * @access	Public
	 * @param string $obj		类参数
	 * @return	u		0 |array
	 */
	function api_get_mysql($obj)
	{
		$obj->_bug=0;
		$data=$obj->post["data"];
		$md5=$obj->post["md5"];
		
		$cont=new CONT("api");
		$run=$cont->obj->api_get_mysql($data,$md5);
		echo $run;
	}
	
	/**
	 * 执行API mysql跨服务器
	 * @access	Public
	 * @param string $obj		类参数
	 * @return	u		0 |array
	 */
	function api_get_file($obj)
	{
		$obj->_bug=0;
		$data=$obj->post["data"];
		$md5=$obj->post["md5"];
		
		$cont=new CONT("api");
		$run=$cont->obj->api_get_file($data,$md5);
		echo $run;
	}

	/**
	 * 执行API mysql跨服务器  分页
	 * @access	Public
	 * @param string $obj		类参数
	 * @return	u		0 |array
	 */
	function api_get_mysql_p($obj)
	{
		$obj->_bug=0;
		$data=$obj->post["data"];
		$md5=$obj->post["md5"];
		
		$page=$obj->post["page"];
		$length=$obj->post["length"];
		$url=$obj->post["url"];
		
		$cont=new CONT("api");
		$run=$cont->obj->api_get_mysql_p($data,$md5,$page,$length,$url);
		echo $run;
	}
	
	function res_data($obj)
	{
		$title="56uu充值中心";
		$str=file_get("http://www.".DOMAIN."/index.php?module=api&methods=game_list");
		$str=object_to_array(json_decode($str));
		//print_r($str["data"]);
		$cont=new CONT("api");
		$run=$cont->obj->game_list($str);
		if($run==100)
		{
			echo "ok";
		}else{
			echo "no";
		}
	}
}
?>