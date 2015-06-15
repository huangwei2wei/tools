<?php
namespace kernel\base\sql\sql;
use kernel\base\base_class\test as _TEST;

interface allsql
{
	function conn($dbhost,$dbuser,$dbpw,$dbname);
	function close_db();
	function sql($sql);
	function dbarr($sql);
	function dbsum($row);
	function dbsum_($sql);
	function get_insert_id();
	function lastlyid($table,$field);
	function field($table,$field,$where,$e=true);
	function dbfield_note($table,$dbsel=false);
	function dbinsert($table, $field, $value,$e=1);
	function dbupdate($table, $field, $value, $where,$e=1);
	function dbselect($table, $select="*",$where="1",$e=1);
}

class mysql_i implements allsql
{
	var $conn;
	
	/**
	 * 打开php数据连接函数
	 * @access	Public
	 * @param	string	$dbhost		主机地址
	 * @param	string	$dbuser		用户名
	 * @param	string	$dbpw		密码
	 * @param	string	$dbname		数据库名
	 * @return	无
	 */
	function conn($dbhost,$dbuser,$dbpw,$dbname)
	{
		if(!$dbhost || !$dbuser || !$dbname)
		{
			return;
		}
		$conn = new \mysqli($dbhost,$dbuser,$dbpw,$dbname);
		$conn ->query("SET NAMES 'utf8'");
		$this->conn=$conn;
		return $conn;
	}
	
	/**
	 * 关闭数据库连接
	 * @access	Public
	 * @return	无
	 */
	function close_db()
	{
		$this->conn->close();
	}
	
	/**
	 * 执行sql语句
	 * @access	Public
	 * @param	string	$sql	sql语句	
	 * @return	bool	true|false
	 */
	function sql($sql)
	{
		if(BUG>0)
		{
			$runtime= new _TEST;
			$runtime->start();
		}
		
		$mysql_query=$this->conn->query($sql);
		
		if(BUG>0)
		{
			$runtime->stop();
			$runtime->mysql_log($sql,$runtime->spent());
		}
		
		return $mysql_query;
	}
	
	/**
	 * 搜寻全部结果集数组化
	 * @access	Public
	 * @param	string	$sql	sql语句	
	 * @param	string	$m		0返回 数据 1数据和总数
	 * @return	array	全部结果集数组
	 */
	function dbarr($sql,$m=0)  //执行搜索/删除SQL语句  //主要用这个
	{
		$num=0;
		$arr=array();
		$row=$this->sql($sql);
		if(strtolower(substr(trim($sql),0,6))!="select"){return $row;}
		if($row)
		{
			$num=$row->num_rows;
			if($num>0)
			{
				while($rs=$row->fetch_array(MYSQL_ASSOC ))
				{
					$arr[]=$rs;
				}
			}else{
				$row->free();
				$arr=false;
			}
		}
		
		if($m==0)
		{
			return $arr;
		}else{
			return array("data"=>$arr,"num"=>$num);
		}
	}
	
	
	/**
	 * 返回查找数据的总和
	 * @access	Public
	 * @param	string	$row	资源标识符
	 * @return	int	数据总和
	 */
	function dbsum($sql) //返回查找数据的总和
	{
		$row=$this->sql($sql);
		if($row)
		{
			return $row->num_rows;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * 返回查找数据的总和
	 * @access	Public
	 * @param	string	$sql	sql查询语句
	 * @return	int	数据总和
	 */
	function dbsum_($sql)
	{
		$sqlr="select count(*) ";
		
		$sql_a=strtolower(trim($sql));
		
		$from=strchr($sql_a,'from');
		
		$sqlr=$sqlr.$from;
		
		$row=$this->sql($sqlr);
		
		$rs=$row->fetch_array();
		
		return $rs[0];
	}
	
	
	/**
	 * 返回上一步 INSERT 操作产生的 ID 推荐使用
	 * @access	Public
	 * @return	int	上一次自增长ID
	 */
	function get_insert_id()
	{
		$row=$this->sql("SELECT ROW_COUNT()");
		$rs=$row->fetch_array();
		$sum=$this->conn->insert_id()+$rs[0];
		return $sum<=0?0:$sum;
	}
	
	/**
	 * 返回上一步 INSERT 操作产生的 ID 不推荐使用
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	string	$field	字段名
	 * @return	int	上一次自增长ID
	 */
	function lastlyid($table,$field)
	{
		$row=$this->sql("select ".$field." from ".$table." where `".$field."` is null");
		$rs=$row->fetch_array();
		return $rs[$field];
	}
	
	
	/**
	 * 按条件取得字段的内容
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	string	$field	字段名
	 * @param	string	$where	查询条件 可以是数字如 1或2 也可以是id=1
	 * @param	string	$e	true反回sql语句 false返回查询内容
	 * @return	string	查询内容
	 */
	function field($table,$field,$where,$e=true)
	{
		if(is_numeric($where))
		{
			$sql="select $field from $table where id='$where'";
		}else{
			$sql="select $field from $table where $where";
		}
		
		if(!$e)
		{
			return $sql;
		}else{
			$row=$this->sql($sql);
			$rs=$row->fetch_array();
			return $rs[$field];
		}
	}
	

	
	
	/**
	 * 获取表的结构信息，包括注释、字段等
	 * @access	Public
	 * @param	string	$table	表名称
	 * @return	array	全部结果集数组
	 */
	function dbfield_note($table,$dbsel=false)
	{
		require(D_R."/config/config_storage.php");
		if($table=="operators" or $table=="server_list"){
			$TABLE_SCHEMA="server_list";
		}else{
			$TABLE_SCHEMA=$_SESSION["mysqldb"];
		}
		$sql ="SELECT 
		COLUMN_NAME,COLUMN_COMMENT,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,
		CHARACTER_SET_NAME,IS_NULLABLE,COLUMN_DEFAULT,ORDINAL_POSITION 
		FROM INFORMATION_SCHEMA.COLUMNS 
		WHERE TABLE_SCHEMA='".$TABLE_SCHEMA."' and table_name = '".$table."'";
		$re =$this->dbarr($sql);
		return $re;
	}
	
	
	/**
	 * 插入mysql数据
	 * @access	Public
	 * @param	string	$table	表名称
	 * @param	array	$field	字段数组 如:array('field1','field2')
	 * @param	array	$value	内容数组 如:array('a','b')
	 * @param	string	$e		1执行语句 0返回sql语句 这个是用来检查sql是否出错
	 * @return	string	执行结果
	 */
	function dbinsert($table, $field, $value,$e=1) //插入数据
	{
	    if (count($field) != count($value) || !is_array($field) || !is_array($value)) 
	    {
	       return -1;//"生成SQL错误!"
	    }
	    $sql = "insert into ".$table ." (";
	
	    for($i = 0;$i < count($field);$i++) {
	        $sql = $sql . "`" . $field[$i] . "`";
	        if ($i != (count($field)-1)) {
	            $sql = $sql . ", ";
	        }
	    }
	    $sql = $sql . ") values (";
	
	    for($i = 0;$i < count($value);$i++) {
	        $sql = $sql . "'" . $value[$i] . "'";
	        if ($i != (count($field)-1)) {
	            $sql = $sql . ", ";
	        }
	    }
	    $sql = $sql . ");";
	
		if($e)
		{
			return $this->dbarr($sql);
		}else{
			return $sql;
		}
	}
	
	
	/**
	 * 更新mysql数据
	 * @access	Public
	 * @param	string	$table	表名称
	 * @param	array	$field	字段数组 如:array('field1','field2')
	 * @param	array	$value	内容数组 如:array('a','b')
	 * @param	string	$where	条件 如 "`id` ='3'"
	 * @param	string	$e		1执行语句 0返回sql语句 这个是用来检查sql是否出错
	 * @return	string	执行结果
	 */
	function dbupdate($table, $field, $value, $where,$e=1)  //更新数据
	{
	    if (count($field) != count($value) || !is_array($field) || !is_array($value)) 
	    {
	        return -1;//"生成SQL错误!"
	    }
	
	    $sql = "update " . $table . " set ";
	
	    for($i = 0;$i < count($field);$i++) 
	    {
	        $sql = $sql . " `" . $field[$i] . "` = '" . $value[$i] . "'";
	        if ($i != (count($field)-1)) {
	            $sql = $sql . ", ";
	        }
	    }
	
	    $sql = $sql . " where 1 and " . $where;

		if($e==1)
		{
			return $this->dbarr($sql);
		}else{
			return $sql;
		}
	}
	
	/**
	 * 查询mysql数据
	 * @access	Public
	 * @param	string	$table	表名称
	 * @param	array	$select	返回字段 可以是数组也可以是 * 但不能为空
	 * @param	array	$where	查询条件 如："`id` ='3'"
	 * @param	string	$e		1执行语句 0返回sql语句 这个是用来检查sql是否出错
	 * @return	string	执行结果
	 */
	function dbselect($table, $select="*",$where="1",$e=1)
	{
		if(!$table or !$select or !$where)
		{
			return -1;//"生成SQL错误!"
		}
		
		if(is_array($select))
		{
			for($i = 0;$i < count($select);$i++) 
		    {
		        $sql = $sql . " `" . $select[$i]."`";
		        if ($i != (count($select)-1)) 
		        {
		            $sql = $sql . ", ";
		        }
		    }
		    $select=$sql;
			//$select=implode($select);
		}
		
	    $sql = "select " .$select. " from  $table where $where";
	    
		if($e)
		{
			return $this->dbarr($sql);
		}else{
			return $sql;
		}
	}
}
?>