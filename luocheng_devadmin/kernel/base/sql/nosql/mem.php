<?php
namespace kernel\base\sql\nosql;
class mem
{
	var $error;
	var $phpmemc;
	var $timr=array(
						1000,
						1000,
						10000,
						10000,
						100000,
						1000000,
						1200000,
						1500000,
						1800000,
						2000000
					);
					
	/**
	 * 构造函数
	 */
	function __construct()
	{ 
	}
	
	/**
	 * 打开php数据连接函数
	 * @access	Public
	 * @return	无
	 */
	function memconn($options)
	{
		//return $this->phpmemc = new \Memcache($options); 
		 $mc = new \Memcache;
		 $servers=explode(":",$options["servers"][0]);
     	 $mc->connect($servers[0],$servers[1]);
     	 return $this->phpmemc=$mc;
	}
	
	/**
	 * 关闭数据库连接
	 * @access	Public
	 * @return	无
	 */
	function memclose_db()
	{
		
	}
	
	/**
	 * 析构函数
	 */
	function __destruct() 
	{
		unset($this->phpmemc);
	}
	
	/**
	 * 读取mem数据
	 *
	 * @param unknown_type $key mem键值
	 * @return string $str 存入的数据
	 */
	function get($key)
	{
		if(!$key)
		{
			return false;
		}
		
		$res=$this->phpmemc->get($key);
		return $res;
	}

	/**
	 * 读取mem数据
	 *
	 * @param unknown_type $key mem键值
	 * @return string $str 存入的数据
	 */
	function nojsonget($key)
	{
		if(!$key)
		{
			return false;
		}
		
		$res=$this->phpmemc->get($key);
		return $res;
	}
	
	/**
	 * 更新mem数据
	 *
	 * @param string $key 更新mem键值
	 * @param string $value 更新mem数据
	 * @return string 成功返回存入数据，失败返回 flase
	 */
	function replace($key,$value,$time=0)
	{
		return $this->phpmemc->replace($key,$value,0,$time);
	}
	
	/**
	 * 强制更新mem数据
	 *
	 * @param string $key 更新mem键值
	 * @param string $value 更新mem数据
	 * @return bool $bol 成功返回true，失败返回 flase
	 */
	function freplace($key,$value,$time=0)
	{
		return $this->phpmemc->replace($key,$value,0,$time);
	}

	/**
	 * 设置mem数据
	 *
	 * @param string $key 设置mem键值
	 * @param string $value 设置mem数据
	 * @return bool 成功返回true，失败返回 flase
	 */
	function set($key,$value,$time=0)
	{
		return $this->phpmemc->set($key,$value,0,$time);
	}

	/**
	 * 设置或更新，当不知道是用设置好还是更新好就用这个函数，他会为你解决 但更新为强制更新
	 *
	 * @param string $tab 	更新或存储的key
	 * @param string $info	更新或存储的内容
	 */
	function settab($tab,$info)
	{
		$re=$this->add($tab,$info);
		if(!$re)
		{
			$re=$this->replace($tab,$info);
		}
		return $re;
	}
	
	/**
	 * 添加mem数据 与set类似，唯一不通的就是set如果键值存在则返回flash 而add这覆盖
	 *
	 * @param string $key 添加mem键值
	 * @param string $value 添加mem数据
	 * @return bool 成功返回true，失败返回 flase
	 */
	function add($key,$value,$time=0)
	{
		return $this->phpmemc->add($key,$value,0,$time);
	}
	
	/**
	 * 删除mem中的数据
	 *
	 * @param string $key 删除mem键值
	 * @return bool 成功返回true，失败返回 flase
	 */
	function delete($key)
	{
		return $this->phpmemc->delete($key);
	}


	/*加锁函数*/
	function addlock($key)
	{
		if($mem->add($key))
		{
			return true;
		}else{
			return false;
		}
	}
	
	/*解锁函数*/
	function dellock($key)
	{
		return $mem->delete($key);
	}
}
?>