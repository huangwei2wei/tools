<?php 
namespace kernel\base\sql\sql;
class mysql_to_nosql
{
	var $num;
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{ 
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct() 
	{
		unset($this->num);
	}
	
	/**
	 * 分析出标记符
	 * @access	Public
	 * @param	array	$value	要分析的字符串); 
	 * @return	array	res		标记符和字符串
	 */
	function flag($value)
	{
		$f1=substr($value,0,2);
		$f2=substr($value,0,1);
		switch($f1)
		{
			case "<=":
				return array("flag"=>"<=","str"=>substr($value,2,strlen($value)));
			break;
			
			case ">=":
				return array("flag"=>">=","str"=>substr($value,2,strlen($value)));
			break;
			
			case "!=":
				return array("flag"=>"!=","str"=>substr($value,2,strlen($value)));
			break;
		}
		
		switch($f2)
		{
			case "=":
				return array("flag"=>"=","str"=>substr($value,1,strlen($value)));
			break;
			
			case ">":
				return array("flag"=>">","str"=>substr($value,1,strlen($value)));
			break;
			
			case "<":
				return array("flag"=>"<=","str"=>substr($value,1,strlen($value)));
			break;
		}
		
		return array("flag"=>"=","str"=>$value);
	}
	
	/**
	 * 获取数据
	 * @access	Public
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	返回字段	$fields=array("title"=>true,"online"=>true);
	 * @param	string	$table	表名
	 * @param	string	$sort	排序array('age'=>-1,'type'=>1) -1倒序 1顺序
	 * @param	string	$skip	从查询结果的这个开始
	 * @param	string	$limit	连续读取这么多条
	 * @return	array	res		数据结果
	 */
	function get($query,$fields="*",$table,$sort=false,$limit=0,$skip=0)
	{
		$data=array();
		if(is_array($query))
		{
			$where="";
			foreach($query as $key =>$value)
			{
				$flag=$this->flag($value);
				$where.=$key.$flag["flag"]."'".$flag["str"]."' and ";
			}
			$where=substr($where,0,-4);
		}else{
			$where="1";
		}
		$select="";
		if(is_array($fields))
		{
			foreach($fields as $key =>$value)
			{
				if($value)
				{
					$select.=$key.",";
				}
			}
			$select=substr($select,0,-1);
		}else{
			$select="*";
		}
		$sort_one="";
		if($sort && is_array($sort))
		{
			$one=0;
			
			foreach($sort as $key =>$value)
			{
				if($one==0)
				{
					$ones="order by";
				}else{
					$ones="";
				}
				
				if($value==1)
				{
					$sort_one.=$ones." `".$key."` asc,";
				}elseif($value==-1){
					$sort_one.=$ones." `".$key."` desc,";
				}
				$one++;
			}
			$sort_one=substr($sort_one,0,-1);
		}
		
		$limit_="";
		if($limit)
		{
			$limit_="limit ".$skip.",".$limit;
		}
		
		$sql="select $select from $table where $where $sort_one $limit_";
		//echo $sql."<br>";
		$data=dbarr($sql,1);
		$this->num=$data["num"];
		return $data["data"];
	}
	
	/**
	 * 获取数据只有一条
	 * @access	Public
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	返回字段	$fields=array("title"=>true,"online"=>true);
	 * @param	string	$table	表名
	 * @param	string	$sort	排序array('age'=>-1,'type'=>1) -1倒序 1顺序
	 * @return	array	res		数据结果
	 */
	function getone($query,$fields="*",$table,$sort=false)
	{
		$data=array();
		$where=$select=$ones=$sort_one="";
		if(is_array($query))
		{
			foreach($query as $key =>$value)
			{
				$flag=$this->flag($value);
				$where.=$key.$flag["flag"]."'".$flag["str"]."' and ";
			}
			$where=substr($where,0,-4);
		}else{
			$where="1";
		}
		
		if(is_array($fields))
		{
			foreach($fields as $key =>$value)
			{
				if($value)
				{
					$select.=$key.",";
				}
			}
			$select=substr($select,0,-1);
		}else{
			$select="*";
		}
		
		if($sort && is_array($sort))
		{
			$one=0;
			foreach($sort as $key =>$value)
			{
				if($one==0)
				{
					$ones="order by";
				}else{
					$ones="";
				}
				
				if($value==1)
				{
					$sort_one.=$ones." ".$key." asc,";
				}elseif($value==-1){
					$sort_one.=$ones." ".$key." desc,";
				}
				$one++;
			}
			$sort_one=substr($sort_one,0,-1);
		}
		
		$sql="select $select from $table where $where $sort_one limit 1";
		$data=dbarr($sql,1);
		$this->num=$data["num"];
		return $data["data"][0];
	}
	
	
	/**
	 * 插入数据
	 * @access	Public
	 * @param	array	$fields	插入字段	$fields=array("title"=>true,"online"=>true);
	 * @param	string	$table	表名
	 * @return	array	res		插入信息
	 */
	function insert($fields,$table)
	{
		$ins="";
		foreach($fields as $key=>$value)
		{
			$ins.=$key."='".$value."',";
		}
		$ins=substr($ins,0,-1);	
		$sql="INSERT INTO $table set $ins";
		return sql($sql);
	}
	
	/**
	 * 更新数据
	 * @access	Public
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	更新字段	$fields=array("title"=>true,"online"=>true);
	 * @param	string	$table	表名
	 * @return	array	res		插入信息
	 */
	function update($query,$fields,$table)
	{
		$flag=$where=$updatas="";
		if(is_array($query))
		{
			foreach($query as $key =>$value)
			{
				if(is_array($value))
				{
					$where.="`".key($value)."`".$key."'".$value[key($value)]."',";
				}else{
					$flag=$this->flag($value);
					$where.=$key.$flag["flag"]."'".$flag["str"]."' and ";
				}
			}
			$where=substr($where,0,-4);
		}else{
			$where="1";
		}
		
		foreach($fields as $key=>$value)
		{
			if(is_array($value))
			{
				$updatas.="`".key($value)."`=`".key($value)."`".$key.$value[key($value)].",";
			}else{
				$updatas.=$key."='".$value."',";
			}
		}
		$updatas=substr($updatas,0,-1);	
		$sql="UPDATE $table set $updatas where $where";
//		echo $sql;exit;
		return sql($sql);
	}
	
	/**
	 * 删除
	 * @access	Public
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	string	$table	表名
	 * @return	array	res		插入信息
	 */
	function delete($query,$table)
	{
		$where="";
		if(is_array($query))
		{
			foreach($query as $key =>$value)
			{
				$flag=$this->flag($value);
				$where.=$key.$flag["flag"]."'".$flag["str"]."' and ";
			}
			$where=substr($where,0,-4);
		}else{
			$where="1";
		}
		
		$sql="delete from $table where $where";
		return sql($sql);
	}
	
	/**
	 * 数据表总数
	 * @access	Public
	 * @param	string	$table	表名
	 * @return	array	res		信息
	 */
	function count($table=false)
	{
		return $this->num; //返回table集合中文档的数量
	}
	
	/**
	 * 为了兼容nosql这里弄个空函数
	 * @access	Public
	 * @return	无
	 */
	function nowres()
	{
	}
	
	/**
	 * 数据转换，为了兼容这里弄个空函数，或根据需要在处理
	 * @access	Public
	 * @param	string	$res	将结果转换成数组
	 * @return	array	res		结果数组
	 */
	function restoarray($res)
	{
		
	}
}
?>