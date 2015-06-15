<?php
namespace kernel\base\base_class;

class string
{	
	/**
	 * +--------------------------------------------------
	 * |   函数名：Encode($str)
	 * |   作用：转换html代码和转行等。
	 * |   参数：
	 * @param  $ : $str：要转换的字符串
	 * |   返回值：转换后的字符串。
	 * +--------------------------------------------------
	 */
	function encode($str)
	{
	    $str = addslashes($str);
	    $str = htmlspecialchars($str);
	    $str = str_replace("\r\n", "<br>", $str);
	    $str = str_replace("\r", "<br>", $str);
	    $str = str_replace("\n", "<br>", $str);
	    $str = str_replace("  ", "　", $str);
	    return $str;
	}
	
	/**
	 * +--------------------------------------------------
	 * |   函数名：Decode($str)
	 * |   作用：与Encode相反，用于修改时还原回本来的字符串
	 * |   参数：
	 * |
	 *
	 * @param  $ : $str：要转换的字符串。
	 * |   返回值：转换后的字符串。
	 * +--------------------------------------------------
	 */
	function decode($str)
	{
	    $str = str_replace("<br>", "\r\n", $str);
	    $str = str_replace("<br>", "\r", $str);
	    $str = str_replace("<br>", "\n", $str);
	    $str = str_replace("<", "&lt;", $str);
	    $str = str_replace(">", "&gt;", $str);
	    return $str;
	}
	
	/*反转义*/
	function RTM($str)
	{
		$str=str_replace("\'","'",$str);
		$str=str_replace('\"','"',$str);
		$str=str_replace("\\n","\n",$str);
		$str=str_replace("\\r","\r",$str);
		$str=str_replace("\\t","\t",$str);
		$str=str_replace("\\\\'","\\",$str);
		$str=str_replace("\\[","\[",$str);
		$str=str_replace("\\x","\x",$str);
		return $str;
	}
	
	/*截字*/
	function cstr($text,$len)
	{
		$text=strip_tags($text);
		$text=str_replace('　　','',$text);
		$text=trim($text);
	    $len=$len*2;
	    $count = strlen($text);
	    if($count >= $len){
	        for ($pos=$len;$pos>0 && ord($text[$pos-1])>=127;$pos--);
	        if (($len-$pos)%2 == 0)
	        $text = substr($text, 0, $len);
	        else
	        $text = substr($text, 0, $len+1);
	        return $text;
	    }else{
	        return $text;
	    }
	}
	
	function gbktoutf($str)
	{
		return mb_convert_encoding($str,"UTF-8","gb2312");
		
	}
	
	function utftogbk($str)
	{
		return mb_convert_encoding($str,"gb2312","UTF-8");
	}
	
	/**
	* 芒果需要强类型，这个函数就是将数据处理城强类型的
	*
	* @param string $table	表名
	* @param int $id	查询的ID
	* @return array
	*/
	function mongoarr($tab,$id=0)
	{
		$tmp=$newdata=array();
		if($id)
		{
			$tmp=dbarr("SELECT * FROM `$tab` WHERE `id`=".$user_id);
		}else{
			$tmp=dbarr("SELECT * FROM `$tab` WHERE 1");
		}
		
		$user_type=$this->tabletype($tab);
		if($tmp)
		{
			foreach($tmp as $key=>$value)
			{
				foreach($value as $k=>$v)
				{
					if(is_numeric($k))
					{
						unset($tmp[intval($key)][$k]);
					}else{
						$tmp[intval($key)][$k]=$this->typeto($user_type[$k],$v);
					}
				}
			}
		}
		
		return $tmp;
	}
	
	/**
	* 取得表字段类型
	*
	* @param string $table	表名
	* @return array
	*/
	function tabletype($table)
	{
		$newarr=array();
		$user_type=dbfield_note($table);
		if($user_type && is_array($user_type))
		{
			foreach($user_type as $key=>$value)
			{
				$newarr[$value["COLUMN_NAME"]]=$value["DATA_TYPE"];
			}
		}
		return $newarr;
	}
	
	/**
	* 根据类型转换数据
	*
	* @param string $type	类型
	* @param string $value	数据
	* @return unknown
	*/
	function typeto($type,$value)
	{
		if($type=="blob" || $type=="int" || $type=="smallint" || $type=="mediumint" || $type=="tinyint" || $type=="bigint")
		{
			return intval($value);
		}
		
		if($type=="varchar" || $type=="date" || $type=="datetime" || $type=="timestame" || $type=="time" || $type=="year")
		{
			return $value;
		}
		
		if($type=="float")
		{
			return floatval($value);
		}
		
		if($type=="double")
		{
			return floatval($value);
		}
		
		return $value;
	}
}
?>