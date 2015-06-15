<?php
namespace module\security;
interface loginint
{
	function act($sign,$arr);
	function get($param,$type="post");
}

/*安全模型*/
class security implements loginint
{
	public function __construct()
	{
	}
		
	/**
	 * 操作器
	 * $sing 说明  
	 * 	*LOGIN[是否登录]  				@param $arr:string
	 *  *PWD[密码处理]	  				@param $arr:array("username"=>"","pwd"=>"","rand"=>"")
	 * 	*CODE[验证码是否正确]  			@param $arr:string
	 * 	*GET[以get形式获取数据]  		@param $arr:string
	 * 	*POST[以post形式获取数据]  		@param $arr:string
	 * 	*SESSION[以session形式获取数据]  @param $arr:string
	 * 	*REQUEST[全部模式获取数据]  								@param $arr:string
	 * 	*FILTER1[1级过滤字符串] 普通(正则剔除sql注入)  			@param $arr:string
	 * 	*FILTER2[2级过滤字符串]严格(普通+关键字过滤+html转换)  	@param $arr:string
	 * 	*FILTER3[3级过滤字符串]非常严格(严格+html过滤)  			@param $arr:string
	 * 	*FILTER4[4级过滤字符串]非非常严格(严格+html段抛弃)  		@param $arr:string
	 * 
	 * @access	Public
	 * @param	string	$sign	模块标记  LOGIN[是否登录] CODE[验证码是否正确]
	 * @param	string	$arr	根据模块提供不同参数 $arr|$arr 具体看下面函数参数确定
	 * @return	bool	true|false
	 */
	public function act($sign,$arr)
	{
		switch($sign)
		{
			case "LOGIN":
				return $this->login_detect($arr);
			break;
			
			case "PWD":
				
				return $this->pwds($arr);
			break;
			
			case "CODE":
				return $this->code_verify($arr);
			break;
			
			case "GET":
				return $this->get($arr,"get");
			break;
			
			case "POST":
				return $this->get($arr,"post");
			break;
			
			case "SESSION":
				return $this->get($arr,"session");
			break;
			
			case "REQUEST":
				return $this->get($arr,"");
			break;
			
			case "FILTER1":
				return $this->filter($arr,1);
			break;
			
			case "FILTER2":
				return $this->filter($arr,2);
			break;
			
			case "FILTER3":
				return $this->filter($arr,3);
			break;
			
			case "FILTER4":
				return $this->filter($arr,4);
			break;
			
			default:
				return 0;
			break;
		}
	}
	
	/**
	 * 检测用户是否登录
	 * @access	Public
	 * @param	intval	$username	用户名
	 * @return	bool	true|false
	 */
	public function login_detect($username=false)
	{
		$session=$this->get("user","session");
		
		if($username && isset($session["user_name"])&& $session["user_name"]==$username)
		{
			return true;
		}elseif($session){
			return true;
		}
		return false;
	}
	
	/**
	 * 验证码验证是否正确
	 * @access	Public
	 * @param	string	$code	验证码
	 * @return	bool	true|false
	 */
	public function code_verify($code)
	{
		$session=$this->get("code","session");
		if($session==$code)
		{
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取参数传递数据
	 * @access	Public
	 * @param	string	$param	参数名
	 * @param	string	$type	类型 post|get|session|""
	 * @return	unknown	根据实际情况而定
	 */
	public function get($param=false,$type="post")
	{
		$data="";
		switch($type)
		{
			case "post":
				$data=$param?(isset($_POST[$param])?$_POST[$param]:""):$_POST;
			break;
			
			case "get":
				$data=$param?(isset($_GET[$param])?$_GET[$param]:""):$_GET;
			break;
			
			case "session":
				$data=$param?(isset($_SESSION[$param])?$_SESSION[$param]:""):$_SESSION;
			break;
			
			default:
				$data1=$param?(isset($_POST[$param])?$_POST[$param]:""):$_POST;
				$data2=$param?(isset($_GET[$param])?$_GET[$param]:""):$_GET;
				$data3=$param?(isset($_SESSION[$param])?$_SESSION[$param]:""):$_SESSION;
				if($data1)
				{
					$data=$data1;
				}elseif($data2){
					$data=$data2;
				}elseif($data3){
					$data=$data3;
				}
			break;
		}
		
		return $data;
	}
	
	/**
	 * 字符串过滤(写入数据库的必须调用此函数过滤)
	 * @access	Public
	 * @param	string	$str	字符串
	 * @param	string	$level	过滤等级  1|2|3|4 普通(正则剔除sql注入)|严格(普通+关键字过滤+html转换)|非常严格(严格+html过滤)|非非常严格（严格+html段抛弃）
	 * @return	string	过滤完成的字符串
	 */
	public function filter($str,$level=1)
	{
		if(!get_magic_quotes_gpc())
		{
			$str=addslashes($str);
			$str=str_replace("_","\_",$str);
			$str=str_replace("%","\%",$str);
		}

		switch($level)
		{
			case 2:
				//关键字过滤
				$str=nl2br($str);
				$str=htmlspecialchars($str);
			break;
			
			case 3:
				//关键字过滤
				$str=nl2br($str);
				$str=strip_tags($str);
			break;

			case 4:
				//关键字过滤
				$str=nl2br($str);
				$str=$this->filterhtml($str);
			break;
		}
		
		return $str;
	}

	/**
	 * html代码段过滤
	 * @access	Public
	 * @param	string	$str	要过滤的字符串
	 * @return	string	$str
	 */
	public function filterhtml($str)
	{
		$str=htmlspecialchars_decode($str);
		$str = preg_replace("@<script(.*?)</script>@is","",$str); 
		$str = preg_replace("@<iframe(.*?)</iframe>@is","",$str); 
		$str = preg_replace("@<style(.*?)</style>@is","",$str); 
		$str = preg_replace("@<(.*?)>@is","",$str); 
		return $str;
	}
	
	/**
	 * 密码处理
	 * @access	Public
	 * @param	string	$username	用户名
	 * @param	string	$pwd		原始密码
	 * @param	string	$rand		随机码
	 * @return	string	$str
	 */
	function pwds($arr)
	{
		$username	=isset($arr["user_name"])?$arr["user_name"]:"";
		$pwd		=isset($arr["pwd"])?$arr["pwd"]:"";
		$rand		=isset($arr["rand"])?$arr["rand"]:"";
		return md5(md5($pwd)+md5($rand)+$username);
	}
}
?>
