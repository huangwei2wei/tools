<?php
namespace kernel\base\tool;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\api_file as AF;

class dbtoxml{
	var $mem;
	var $xml;
	var $x;
	/**
	 * 构造函数
	 */
	function __construct()
	{ 
		global $mem;
		$this->mem=$mem;
		$this->x=new xmlfun;
	}
	/**
	 * 析构函数
	 */
	function __destruct() 
	{
		unset($this->mem);
	}
	/**
	 * 转换表为xml
	 *
	 * @param string $tab 数据库表名
	 * @return array $rs  返回数组或者BOOL False
	 */
      
	function tbtoxml($tab)
	{ 
		$string=new STRING;
		$rs=$string->mongoarr(trim($tab));
               
		if($rs)
		{
			$this->xml=$this->x->array_to_xml($rs,'utf-8',"data");
			//$this->xml=toutf8($this->x->array_to_xml($rs,'utf-8',"data"));
			return $rs;
		}else{
			return false;
		}
		
	}

	/**
	 * 将xml写入文件
	 *
	 * @param string $path 写入目的文件
	 * @return bool true 执行成功
	 */
	function  wpath($path)
	{
		//createDir($path);
		@file_put_contents($path,$this->xml);
//		$af=new AF;
//		$af->w($path,$this->xml);
		return true;
	}
}
?>