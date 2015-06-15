<?php 
namespace module\reg;
use controllers\module as MODULE;
use module\security\security as SECURITY;

interface loginint
{
	function regfun($arr);
}

/*用户登录模型*/
class reg extends MODULE implements loginint
{
	/**
	 * 用户注册
	 * @access	Public
	 * @param	string	$arr	注册数组
	 * @return	int		0[已经注册]|1|2|3...|999[注册成功]
	 */
	public function regfun($arr)
	{
		if($this->isreg($arr))
		{
			return $this->reg_c($arr);
		}else{
			return 2;
		}
	}
	
	/**
	 * 判断用户是否已经注册
	 * @access	Public
	 * @param	string	$arr	注册数组
	 * @return	bool	true|false
	 */
	public function isreg($arr)
	{
		$querydata=$this->mton->getone(array("user_name"=>$arr["user_name"]),array("id"=>true),"user");
		if(isset($querydata["id"])|| $querydata["id"])
		{
			return false;//注册失败，原因此用户已经存在
		}
		return true;
	}
	
	/**
	 * 开始用户注册
	 * @access	Public
	 * @param	string	$arr	注册数组
	 * @return	int		1|2|3...|999[注册成功]
	 */
	public function reg_c($arr)
	{
		$security=new SECURITY;
		if($arr["pwd"]!=$arr["pwd1"])
		{
			return 2;//两次密码不一致
		}
		
		$arrfield=array(
					"rand"=>1,
					"sex"=>1,
					"user_name"=>1,
					"nickname"=>1,
					"pwd"=>1,
					"age"=>1,
					"industry"=>1,
					"education"=>1,
					"city"=>1,
					"district"=>1,
					"address"=>1,
					"all_address"=>1,
					"lng"=>1,
					"lat"=>1,
					"addtime"=>1,
					"edittime"=>1,
					"login_time"=>1,
					"login_ip"=>1,
		);
		
		$arr["nickname"]=$arr["user_name"];
		
		$rand=rand_m(5);
		$ip=get_ip();
		$arr["rand"]=$rand;
		$arr["addtime"]=time();
		$arr["login_time"]=time();
		$arr["login_ip"]=$ip;
		
		$arr["pwd"]=$security->act("PWD",array("username"=>$arr["user_name"],"pwd"=>$arr["pwd"],"rand"=>$arr["rand"]));
		
		foreach($arr as $key=>$value)
		{
			if(!isset($arrfield[$key]))
			{
				unset($arr[$key]);
			}
		}
		
		if($this->mton->insert($arr,"user"))
		{
			return 100;//注册成功
		}else{
			return 1;//注册失败，原因位置
		}
	}
}
?>