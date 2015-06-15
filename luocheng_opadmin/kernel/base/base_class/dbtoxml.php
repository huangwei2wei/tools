<?php
namespace kernel\base\base_class;
use kernel\base\tool\xmlfun as XMLFUN;

class dbtoxml{
	var $x;
	var $xmldata;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{ 
		$this->x=new XMLFUN;
	}
	
	/**
	 * 析构函数
	 */
	function __destruct() 
	{
		unset($this->x);
	}
	
	/**
	 * 转换表为xml
	 *
	 * @param string $tab 数据库表名
	 * @return array $rs  返回数组或者BOOL False
	 */
      
	function dbtoxml($tab)
	{ 
		$rs=dbarr("SELECT * FROM `".trim($tab)."` WHERE 1");
               
		if($rs)
		{
			foreach($rs as $key=> $value)
			{
					foreach($value as $k=>$v)
					{
							if(is_numeric($k))
							{
									unset($rs[$key][$k]);
							}
					}
			}
			$this->xmldata=$this->x->array_to_xml($rs,'utf-8',"data");
			//$this->xmldata=toutf8($this->x->array_to_xml($rs,'utf-8',"data"));
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
		@file_put_contents($path,$this->xmldata);
		return true;
	}
}
?>