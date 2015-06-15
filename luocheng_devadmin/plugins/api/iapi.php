<?php 
namespace module\plugins\api;
use controllers\module as MODULE;
use kernel\base\base_class\api_mysql as AM;
use kernel\base\base_class\api_file as AF;

/*充值模型*/
class iapi extends MODULE
{
	/**
	 * API mysql跨服务器调度
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	public function api_sql($sql,$server)
	{
		$am=new AM;
		return $am->api_sql($sql,$server);
	}
	
	/**
	 * API mysql跨服务器调度 分页
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	public function api_sql_p($sql,$server,$page,$length)
	{
		$am=new AM;
		return $am->api_sql_p($sql,$server,$page,$length);
	}
	
	/**
	 * 执行API mysql跨服务器
	 * @access	Public
	 * @param string $data	密码体
	 * @param string $md5	校验码
	 * @return	u		0 |array
	 */
	public function api_get_mysql($data,$md5)
	{
		$am=new AM;
		return $am->api_get_mysql($data,$md5);
	}

	/**
	 * 执行API mysql跨服务器 分页
	 * @access	Public
	 * @param string $data	密码体
	 * @param string $md5	校验码
	 * @return	u		0 |array
	 */
	public function api_get_mysql_p($data,$md5,$page,$length,$url)
	{
		$am=new AM;
		return $am->api_get_mysql_p($data,$md5,$page,$length,$url);
	}
	
	/**
	 * 执行API mysql跨服务器 分页
	 * @access	Public
	 * @param string $data	密码体
	 * @param string $md5	校验码
	 * @return	u		0 |array
	 */
	public function api_get_file($data,$md5)
	{
		$af=new AF;
		return $af->api_get_file($data,$md5);
	}
	
	/**
	 * 充值
	 * @access	Public
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	public function game_list($arr)
	{
		$newdata=array();
		$e=file_put_contents(D_R."/res/arr/game_list.php",'<? $game_list='.var_export($arr["data"],true).'; ?>');
		$e=file_put_contents(D_R."/res/arr/game_config.php",'<? $game_config='.var_export($arr["config"],true).'; ?>');
		
		$arr=file_get("http://pay.".DOMAIN."/index.php?module=api&methods=put_channel");
		$e=file_put_contents(D_R."/res/arr/channel.php",'<? $channel='.var_export(object_to_array(json_decode($arr)),true).'; ?>');
		
		if($e)
		{
			return 100;
		}else{
			return 0;
		}
	}
}
?>