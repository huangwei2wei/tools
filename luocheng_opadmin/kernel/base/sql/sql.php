<?php
use kernel\base\sql\sql\mysql as MYSQL;
use kernel\base\sql\sql\mysql_i as MYSQL_I;
use kernel\base\sql\sql\mysql_to_nosql as MTN;

use kernel\base\sql\nosql\mem as MEM;
use kernel\base\sql\nosql\mongo as MONGO;
use kernel\base\sql\nosql\advanced as ADVANCED;

$mton=$__sql=$mem=$mongo="";
//是否启动mysql 暂时不支持其他数据库配置文件做预留
if(_START_MYSQL==1)
{
	if(count($config_storage["MYSQL"])>=1)
	{
		foreach($config_storage["MYSQL"] as $key=>$value)
		{
			if(class_exists("mysqli",true))
			{
				$__sql[$key]=new MYSQL_I;
			}else{
				$__sql[$key]=new MYSQL;
			}
			
			if($key=="base" and isset($_SESSION["mysqldb"]))
			{
				$value["db"]=$_SESSION["mysqldb"];
			}
			$__sql[$key]->conn=$__sql[$key]->conn($value["host"],$value["user"],$value["pw"],$value["db"]);
		}
	}
	
	//启动mysql转nosql语法
	if(START_MYSQLTONOSQL==1)
	{
		$mton=new MTN();
	}
}

//是否启动memcache
if(_START_MEMCACHE==1)
{
	$mem=new MEM;
	if(isset($config_storage["MEMCACHED"]) && count($config_storage["MEMCACHED"])>=1)
	{
		foreach($config_storage["MEMCACHED"] as $key=> $value)
		{
			$hostport[]=$value["host"].":".$value["port"];
		}
		
		$options = array( 
					"servers" => $hostport, //memcached 服务的地址、端口，可用多个数组元素表示多个 memcached 服务 
					"debug" => false, //是否打开 debug 
					"compress_threshold" => 10240, //超过多少字节的数据时进行压缩 
					"persistant" => false //是否使用持久连接 
			);
			
		$mem->memconn($options);
	}
}

//是否启动mongo
if(_START_MONGO==1)
{
	//看运营商是否登录 这一段是特殊的
	if(isset($_SESSION["userid"]) && intval($_SESSION["userid"]))
	{
		$tmp=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		if($_SESSION["server_id"])
		{
			$mongodb="s_".intval($_SESSION["userid"])."_".$_SESSION["server_id"];
		}else{
			$mongodb="s_".intval($_SESSION["userid"])."_1";
		}
			
		//重置配置文件
		$config_storage["MONGO"]=array(
										"username"=>$tmp[0]["mongo_username"],
										"password"=>$tmp[0]["mongo_password"],
										"host"=>$tmp[0]["server_mongo_ip"],
										"port"=>$tmp[0]["server_mongo_port"],
										"db"=>$mongodb,
										"mongo_line"=>$tmp[0]["mongo_line"],
										"mongo_auth"=>$tmp[0]["mongo_auth"],
										);
	}
	
	$mongo=new ADVANCED;
	$connstr="";
	if(isset($config_storage["MONGO"])&&count($config_storage["MONGO"])>=1)
	{
		$mongo->mongo->mongoconn($config_storage["MONGO"]["host"],$config_storage["MONGO"]["port"],$config_storage["MONGO"]["db"],$config_storage["MONGO"]["username"],$config_storage["MONGO"]["password"],$config_storage["MONGO"]["mongo_line"],$config_storage["MONGO"]["mongo_auth"]);
	}
}

/**
 * 取得数据句柄别名
 * @access	Public
 * @param	string	$conn_alias	数据句柄别名
 * @return	无
 */
function getflag($__sql,$conn_alias="")
{
	if(!$conn_alias){$conn_alias=key($__sql);}
	return $conn_alias;
}

/**
 * 关闭数据库连接
 * @access	Public
 * @param	string	$conn_alias	数据句柄别名
 * @return	无
 */
function close_db($conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	$__sql[$key]->close_db();
}

/**
 * 搜寻全部结果集数组化
 * @access	Public
 * @param	string	$sql		sql语句	
 * @param	string	$sum		0返回 数据 1数据和总数
 * @param	string	$conn_alias	数据句柄别名
 * @return	array	全部结果集数组
 */
function dbarr($sql_,$sum=0,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbarr($sql_,$sum);
}

/**
 * 搜寻全部结果集数组化
 * @access	Public
 * @param	string	$sql		sql语句	
 * @param	string	$conn_alias	数据句柄别名
 * @return	array	全部结果集数组
 */
function sql($sql_,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->sql($sql_);
}

/**
 * 搜寻全部结果集数组化
 * @access	Public
 * @param	string	$sql		sql语句	
 * @param	string	$conn_alias	数据句柄别名
 * @return	array	全部结果集数组
 */
function mdbarr($sql_,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->mdbarr($sql_);
}

/**
 * 返回查找数据的总和
 * @access	Public
 * @param	string	$row		资源标识符
 * @param	string	$conn_alias	数据句柄别名
 * @return	int	数据总和
 */
function dbsum($row,$conn_alias="") //返回查找数据的总和
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbsum($row);
}


/**
 * 返回查找数据的总和
 * @access	Public
 * @param	string	$sql		sql查询语句
 * @param	string	$conn_alias	数据句柄别名
 * @return	int	数据总和
 */
function dbsum_($sql_,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbsum_($sql_);
}


/**
 * 返回上一步 INSERT 操作产生的 ID 推荐使用
 * @access	Public
 * @param	string	$conn_alias	数据句柄别名
 * @return	int	上一次自增长ID
 */
function get_insert_id($conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->get_insert_id();
}

/**
 * 返回上一步 INSERT 操作产生的 ID 不推荐使用
 * @access	Public
 * @param	string	$table		表名
 * @param	string	$field		字段名
 * @param	string	$conn_alias	数据句柄别名
 * @return	int	上一次自增长ID
 */
function lastlyid($table,$field,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->lastlyid($table,$field);
}


/**
 * 按条件取得字段的内容
 * @access	Public
 * @param	string	$table		表名
 * @param	string	$field		字段名
 * @param	string	$where		查询条件 可以是数字如 1或2 也可以是id=1
 * @param	string	$e			true反回sql语句 false返回查询内容
 * @param	string	$conn_alias	数据句柄别名
 * @return	string	查询内容
 */
function field($table,$field,$where,$e=true,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->field($table,$field,$where,$e);
}

function field2($table,$field,$e=true,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->field($table,$field,$e);
}



/**
 * 获取表的结构信息，包括注释、字段等
 * @access	Public
 * @param	string	$table		表名称
 * @param	string	$conn_alias	数据句柄别名
 * @return	array	全部结果集数组
 */
function dbfield_note($table,$conn_alias=false)
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbfield_note($table,$conn_alias);
}

/**
 * 插入mysql数据
 * @access	Public
 * @param	string	$table		表名称
 * @param	array	$field		字段数组 如:array('field1','field2')
 * @param	array	$value		内容数组 如:array('a','b')
 * @param	string	$e			1执行语句 0返回sql语句 这个是用来检查sql是否出错
 * @param	string	$conn_alias	数据句柄别名
 * @return	string	执行结果
 */
function dbinsert($table, $field, $value,$e=1,$conn_alias="") //插入数据
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbinsert($table, $field, $value,$e);
}


/**
 * 更新mysql数据
 * @access	Public
 * @param	string	$table	表名称
 * @param	array	$field		字段数组 如:array('field1','field2')
 * @param	array	$value		内容数组 如:array('a','b')
 * @param	string	$where		条件 如 "`id` ='3'"
 * @param	string	$e			1执行语句 0返回sql语句 这个是用来检查sql是否出错
 * @param	string	$conn_alias	数据句柄别名
 * @return	string	执行结果
 */
function dbupdate($table, $field, $value, $where,$e=1,$conn_alias="")  //更新数据
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbupdate($table, $field, $value, $where,$e);
}

/**
 * 查询mysql数据
 * @access	Public
 * @param	string	$table		表名称
 * @param	array	$select		返回字段 可以是数组也可以是 * 但不能为空
 * @param	array	$where		查询条件 如："`id` ='3'"
 * @param	string	$e			1执行语句 0返回sql语句 这个是用来检查sql是否出错
 * @param	string	$conn_alias	数据句柄别名
 * @return	string	执行结果
 */
function dbselect($table, $select="*",$where="1",$e=1,$conn_alias="")
{
	global $__sql;
	$key=getflag($__sql,$conn_alias);
	return $__sql[$key]->dbselect($table, $select,$where,$e);
}
?>