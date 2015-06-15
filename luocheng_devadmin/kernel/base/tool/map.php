<?php
namespace kernel\base\tool;

class map
{
	var $mem;
	var $path;
	var $x;
	var $xml;
	var $data;
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
	 * 加入地图xml路径
	 *
	 * @param string $path 路径
	 */
	function setmapxml($path)
	{
		$this->path=$path;
	}
	
	/**
	 * 将数据库数据转换成xml
	 *
	 * @return bool
	 */
	function dbtoxml()
	{
		$rs=dbarr("SELECT * FROM `base_map` WHERE 1");
		$rs1=dbarr("SELECT * FROM `base_map_info` WHERE 1");
		
		foreach($rs as $k=>$v)
		{
				foreach($v as $k1=>$v1)
				{
					if(is_numeric($k1))
					{
						unset($rs[$k][$k1]);
					}
				}
		}
		
		foreach($rs1 as $k=>$v)
		{
				foreach($v as $k1=>$v1)
				{
					if(is_numeric($k1))
					{
						unset($rs1[$k][$k1]);
					}
				}
		}
		
		$data['map']=$rs;
		$data['map_info']=$rs1;
		$this->xml=$this->x->array_to_xml($data,'utf-8',"data");
		return $this->xml;
	}

	/**
	 * 将dbtoxml函数转换的xml写入文件，文件路径由setmapxml函数传入
	 *
	 * @return bool
	 */
	function  wpath()
	{
		@file_put_contents($this->path,$this->xml);
		return true;
	}
	
	/**
	 * 将数组添加到数据库中
	 *
	 *  @return bool
	 */
	function arr_to_db()
	{
			$data=$this->data;
			//////////////////////////////////////////////////////
			
			foreach($data['map'] as $key=>$value)
			{
					foreach($value as $key1=>$value1)
					{
								$fieldx[]=$key1;
								$valuex[]=$value1;
					}
					
					dbinsert('map', $fieldx, $valuex);
					unset($fieldx,$valuex);
			}
			
			foreach($data['map_info'] as $key=>$value)
			{
			
					foreach($value as $key1=>$value1)
					{
							if($key1=='npc' or $key1=='monster')
							{
									$fieldx[]=$key1;
									$valuex[]=$this->zh($value1);
							}else{
								$fieldx[]=$key1;
								$valuex[]=$value1;
							}
					}
					
					dbinsert('map_info', $fieldx, $valuex);
					unset($fieldx,$valuex);
			}
	}
	
	/**
	 * 将xml转换成数组
	 *
	 * @return array 
	 */
	function xml_to_array()
	{
		$xml=@file_get_contents($this->path);
		$data=$this->x->xml_to_array($xml);
	//	foreach((array)$data['data'] as $ka=>$va){$data['data'][$ka]['area']=$va['area']['data'];}
		$data['map']=$data['map']['item'];
		$data['map_info']=$data['map_info']['item'];
		$this->data=$data;
		return $data;
	}
	
	function zh($str)
	{
		$str1=arrayed($str);
		foreach($str1 as $key=>$value)
		{
			if(!$key){unset($str1[$key]);continue;}
			if(intval(trim($value))<=0)
			{
				$str1[$key]=1;
			}
		}
		
		return stred($str1);
	}
	
	/*****************************************************
	描述：将目录封装从zip
	参数：
			 $path   路径 
			$name 压缩名含扩展名比如out3.zip
	返回值 true
	******************************************************/
	/**
	 * 将目录封装从zip
	 *
	 * @param string $path 路径 
	 * @param string $name 压缩名含扩展名比如out3.zip
	 * @return bool
	 */
	function filezip($path,$name='resources/map.zip')
	{
		wzip(D_R.$path,D_R.$name);
		return true;
	}
}
?>