<?php
namespace kernel\base\tool;
class xmlfun{
	/*===========================================================
	= 版权协议：
	=	GPL (The GNU GENERAL PUBLIC LICENSE Version 2, June 1991)
	=------------------------------------------------------------
	= 文件名称：cls.xml_parser.php
	= 摘    要：XML 解析类 for PHP5
	= 版    本：1.0
	=------------------------------------------------------------
	= Script Written By 91wan项目组
	= 最后更新：xinge
	= 最后日期：2007-10-22
	============================================================*/

		public $xml;

		/**
		 * 构造函数
		 *
		 * @param mixed $xml 传入xml文件路径或者xml字符串
		 */
		public function __construct($xml = '') {
			$this -> getXml($xml);
		}

		/**
		 * 将XML数据转换成数组
		 *
		 * @param mixed $xml 传入xml文件路径或者xml字符串
		 * @return array 
		 */
		public function xml_to_array($xml = '') {
			$this -> getXml($xml);
			$result  =  simplexml_load_string($this -> xml);

			if($result !== false) {
				return $this -> objToArray($result);
			} else
				return false;
		}

		/**
		 * 加载xml数据
		 *
		 * @param mixed $xml xml文件路径或者xml字符串
		 */
		public function getXml($xml='') {
			if (!empty($xml)) {
				if (is_file($xml)) {
					$this -> xml = file_get_contents($xml);
				} elseif(is_string($xml)) {
					$this -> xml = $xml;
				}
			}
		}

		/**
		 * SimpleXMLElement 对象转数组
		 *
		 * @param object $object 传入对象
		 * @return array
		 */
		private function objToArray($object) {
			$return = NULL;

			if(is_array($object)) {
				foreach($object as $key => $value)
					$return[$key] = $this -> objToArray($value);
			} else {
				$var = get_object_vars($object);

				if ($var) {
					foreach($var as $key => $value)
						$return[$key] = ($key && !$value) ? NULL : $this -> objToArray($value);
				} else 
					return $object;
			}
			return $return;
		}

		/**
		 * 将数组转换成xml字符串
		 *
		 * @param array $arr
		 * @param string $encoding
		 * @param string $root
		 * @param string $item
		 * @return string
		 */
		function array_to_xml($arr,$encoding = 'utf-8',$root = "phpwms",$item=false) {
			$xml = '<?xml version="1.0" encoding="'.$encoding.'"?>';
			$xml .= '<'.$root.'>';
			$xml .= $this->setXml($arr,$item);   
			$xml .= '</'.$root.'>'; 
			return $xml;
		}

		function setXml($arr,$item) {
			if(is_object($arr)) {
				$data = get_object_vars($arr);
			}
			$xml = '';
			foreach((array)$arr as $key=>$val) {
				if($item){
					is_numeric($key) && $key = "item id=\"$key\"";
				}else{
					is_numeric($key) && $key = "item";
				}
				
				$xml .= "<$key>";
				$xml .= (is_array($val)||is_object($val))?$this->data_to_xml($val,$item):$val;
				list($key,) = explode(' ',$key);
				$xml .= "</$key>";
			}
			return $xml;
		}
		
		function data_to_xml($data,$item) 
		{
			if(is_object($data)) {
				$data = get_object_vars($data);
			}
			$xml = '';
			foreach($data as $key=>$val) {
				if($item){
					is_numeric($key) && $key="item id=\"$key\"";
				}else{
					is_numeric($key) && $key="item";
				}
				
				$xml.="<$key>";
				$xml.=(is_array($val)||is_object($val))?$this->data_to_xml($val,$item):$val;
				list($key,)=explode(' ',$key);
				$xml.="</$key>";
			}
			return $xml;
		}
}
?>