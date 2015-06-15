<?php 
namespace controllers;
use kernel\base\base_class\res as RES;
use module\security\security as SECURITY;

/*
 * 程序总控制器，下层程序必须有下面接口
 * 接口类第一个字母必须以i开头
 */
class module
{
	var $res;
	var $security;
	
	var $mysql;
	var $mem;
	var $mton;
	var $mongo;
	var $phpmongo;
	var $mysqldb;
	
	public function __construct()
	{
		global $mton,$sql,$mem,$mongo;
		
		$this->res=new RES;
		$this->security=new SECURITY;
		
		$this->mysql=$sql;
		$this->mem=$mem;
		$this->mton=$mton;
		$this->mongo=$mongo;
		$this->phpmongo=$mongo->mongo;
		$this->mysqldb=isset($_SESSION["mysqldb"])?$_SESSION["mysqldb"]:"";
	}
	
	public function getmysqldb()
	{
		return 	$this->mysqldb;
	}
}
?>