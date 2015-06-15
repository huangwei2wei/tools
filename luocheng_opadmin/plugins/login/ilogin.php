<?php 
namespace plugins\login;
use module\login\login as _LOGIN;
/*用户登录模型*/
class ilogin extends _LOGIN
{
	/**
	 * 用户登录
	 * @access	Public
	 * @param	string	$username	用户名
	 * @param	string	$pwd		密码
	 * @param	string	$code		验证码
	 * @return	int		对应数组
	 */
	public function login($arr)
	{
		
		return $this->_sys_login($arr);
	}
	
	/**
	 * 密钥用户登录
	 * @access	Public
	 * @param	string	$obj	对象
	 * @return	int		对应数组
	 */
	public function locallogin($arr)
	{
		return $this->_sys_locallogin($arr);
	}
	
	/**
	 * 注销用户登录
	 * @access	Public
	 * @param	string	$username	用户名
	 * @return	bool	true|false
	 */
	public function unlogin()
	{
		return $this->unloginfun();
	}
	
	/**
	 * 延续登录
	 * @access	Public
	 * @param	string	$username	用户名
	 * @return	bool	true|false
	 */
	public function login_last()
	{
		return $this->login_last_();
	}
	
}
?>