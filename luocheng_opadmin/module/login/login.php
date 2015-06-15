<?php 
namespace module\login;
use controllers\module as MODULE;
use kernel\base\base_class\cookie as COOKIE;
use kernel\base\base_class\iptoaddress as IPTO;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

interface loginint
{
	function _sys_login($parameter);
	function _sys_locallogin($username);
	function unloginfun();
	function loginlog($arr);
}

/*用户登录模型*/
class login extends MODULE implements loginint
{
	/**
	 * 用户登录
	 * @access	Public
	 * @param	string	$sign	模块标记  LOGIN[是否登录] CODE[验证码是否正确]
	 * @param	string	$arr	根据模块提供不同参数 $arr["username"]|$arr["code"] 具体看下面函数参数确定
	 * @return	bool	1[用户已登录]|2[验证码错误]|100[登录成功]|4[密码错误]
	 */
	public function _sys_login($parameter)
	{
		$_SESSION["purview"]=-1;
		$operators=trim($parameter["operators"]);
		$login_user=trim($parameter["login_user"]);
		$pwd=trim($parameter["password"]);
		//$purview=trim($parameter["purview"]);
		//$mysqldb=trim($parameter["mysqldb"]);
		$code= true;
		
		if(isset($_SESSION["operators"]) && $_SESSION["operators"]==$operators)
		{
			$res=1;
		}else{
			$arr=dbarr("SELECT `id` ,`purview`,`default_mysql_db` FROM `operators` WHERE `operators`='".$operators."'",0,"operators");
			if($arr==false)exit("运营商错误!!");
			$operators_id=intval($arr[0]["id"]);
			$mypurview=(array)json_decode($arr[0]["purview"]);
			$mysqldb=$arr[0]["default_mysql_db"];
			
			//判断密码是否正确
			if($this-> pwdcheck($mypurview,$login_user,md5($pwd)))
			{
				$_SESSION["operators"]=$operators;	//运营商
				$_SESSION["operators_id"]=$operators_id;	//
				$_SESSION["loginuser"]=$login_user;	//登录人员
				$_SESSION["userid"]=$operators_id;
				$_SESSION["server_id"]=0;
				$_SESSION["purview"]=$this->mypurview($mypurview,$login_user);//99超级管理员 1运营权限 2财务权限 3运营+财务权限
				$_SESSION["mysqldb"]=$mysqldb;
				$res=100;
			}else{
				$res=4;
				return $res;
			}
				
				
			$tmp_mongo=new TMP_MONGO;
			$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
			
			$server_list=$tmp_mongo->find("server_list",array ('operators_id' =>intval($operators_id)))->sort(array("server_id"=>-1))->limit(1);
			foreach($server_list as $value){
				if($this-> pwdcheck($mypurview,$login_user,md5($pwd)))
				{
					$_SESSION["server_id"]=$value["server_id"];
				}
			}
		}
		return $res;
	}
	
	function pwdcheck($mypurview,$login_user,$pwd)
	{
		if(isset($mypurview[$login_user]))
		{
			$userdata=(array)$mypurview[$login_user];
			if($pwd==$userdata["pwd"]){
				return true;
			}else{
				return false;	
			}
		}else{
			return false;	
		}
	}
		
	function mypurview($mypurview,$login_user)
	{
		if(isset($mypurview[$login_user]))
		{
			$userdata=(array)$mypurview[$login_user];
			return intval($userdata["purview"]);
		}else{
			return -1;
		}
	}
	
	

	/**
	 * 用户密钥登录
	 * @access	Public
	 * @param	string	$sign	模块标记  LOGIN[是否登录] CODE[验证码是否正确]
	 * @param	string	$arr	根据模块提供不同参数 $arr["username"]|$arr["code"] 具体看下面函数参数确定
	 * @return	bool	1[用户已登录]|2[验证码错误]|100[登录成功]|4[密码错误]
	 */
	public function _sys_locallogin($arr)
	{
		$__sys_user_local=isset($_COOKIE["__sys_user_local"])?$_COOKIE["__sys_user_local"]:"";
		if(isset($__sys_user_local) && $__sys_user_local && ($arr["m"]==$__sys_user_local))
		{
			$url="http://login.".DOMAIN."/index.php?module=login&methods=local";
			
			$data=file_post($url,$arr);
			if(json_decode($data))
			{
				$data=object_to_array(json_decode($data));
				if($data["e"]==100)
				{//登录成功
					return $data;	//登录成功
				}else{//登录失败
					return array("e"=>0); //-2密钥不正确 请将地址转向到首页
				}
			}
		}else{
			return -1;//用户进本信息不对
		}
	}
	
	/**
	 * 注销用户登录
	 * @access	Public
	 * @return	bool	true|false
	 */
	public function unloginfun()
	{
		$cookie =new COOKIE;
		unset($_SESSION["user"]);
		$cookie->del("__sys_automatic_login");
		$cookie->del("__sys_user_local");
		$sid=sid();
		$url="http://login.".DOMAIN."/index.php?module=login&methods=unlogin&sid=".$sid;
		$data=file_get($url);
		return true;
	}
	
	/**
	 * 用户名密码验证
	 * @access	Public
	 * @param	string	$username	用户名
	 * @param	string	$pwd		密码
	 * @return	bool	100[登录成功]|4[密码错误]
	 */
	public function loginpwd($username,$pwd,$security)
	{
		$querydata=$this->mton->getone(array("user_name"=>$username),"*","user");
		
		$pwd_str=$security->act("PWD",array("username"=>$username,"pwd"=>$pwd,"rand"=>$querydata["rand"]));
		
		if($querydata["pwd"]==$pwd_str)
		{
			$_SESSION["user"]=array(
									"id"=>$querydata["id"],
									"user_name"=>$querydata["user_name"],
		         	"purview"=>$querydata["purview"],
									"nickname"=>$querydata["nickname"],
									"city"=>$querydata["city"],
									"sex"=>$querydata["sex"],
									"email"=>$querydata["email"],
									"login_time"=>$querydata["login_time"],
									"login_ip"=>$querydata["login_ip"],
									"up_login_city"=>"",
									"login_city"=>"",
									"time"=>time(),
									"x_login_ago"=>fn(time()-$querydata["login_time"]),
								);
								
			$rand=rand_m(5);
			$ip=get_ip();
			$new_pwd=$security->act("PWD",array("username"=>$username,"pwd"=>$pwd,"rand"=>$rand));
			$query=array("id"=>$querydata["id"]);
			$fields=array("pwd"=>$new_pwd,"rand"=>$rand,"login_time"=>time(),"login_ip"=>$ip);
			$this->mton->update($query,$fields,"user");
			
			return 100;
		}else{
			return 4;
		}
	}
	
	/*登录日志记录*/
	public function loginlog($arr)
	{
		$fgf="-#-#-";
		$y=date("Y",time());
		$m=date("m",time());
		$d=date("d",time());
		$p=D_R."res/log/login/".$y."/".$m."/".$d;
		createDir($p);
		$p.="/user.log";
		//取得IP
		$ip=get_ip();
		$str=$arr["username"].$fgf.$ip.$fgf.time()."\n";
		$loginlog=@file_get_contents($p);
		@file_put_contents($p,$loginlog.$str);
	}
	
	//延续登录
	public function login_last_()
	{
		$cookie =new COOKIE;
		
		if(isset($_SESSION["user"]))
		{
			return array("e"=>100,"data"=>json_encode($_SESSION["user"]));
		}else{
			return array("e"=>0);
		}
	}
	
	/**
	* 密钥
	* @access	Public
	* @return	int		0[已经注册]|1|2|3...|999[注册成功]
	*/
	function userurl($user_name)
	{	
		$cookie =new COOKIE;
		$str1=$this->generate_keys($user_name);
	
		$str=r_encode($str1);
		//echo $this->r_decode($str);//逆向搓动算法
		return array("md5"=>$str1,"pwd"=>$str);
	}
	
	//生成密钥
	function generate_keys($userid)
	{
		$sid=sid();
		if(!$userid){$userid=$sid;}
		$m32=rand_m(32);
		$str=urlencode(base64_encode(md5($sid.$userid.$m32)."56uu".$userid));
		return $str;
	}
	
	function operators($id)
	{
		$operators=dbarr("SELECT * FROM `operators` WHERE `id`=$id",0,"operators");
		return $operators[0];
	}
	
	function addpurview($id,$post)
	{
			$operators=dbarr("SELECT * FROM `operators` WHERE `id`=$id",0,"operators");
			$purview=$operators[0]["purview"];
			$purview=(array)json_decode($purview);
			foreach($purview as $key=>$value)
			{
				$value=(array)$value;	
				if($key==$post["user"]){
					exit("此用户已经存在，不允许再添加！");	
				}
			}
			$purview[$post["user"]]=array("purview"=>$post["purview"],"pwd"=>md5($post["pwd"]));
			dbupdate('operators',array("purview"),array(json_encode($purview)),'id='.$id,1,'operators');
	}
	
	function delpurview($id,$login_user)
	{
			$operators=dbarr("SELECT * FROM `operators` WHERE `id`=$id",0,"operators");
			$purview=$operators[0]["purview"];
			$mypurview=(array)json_decode($purview);
			if(isset($mypurview[$login_user]))
			{
				unset($mypurview[$login_user]);
				dbupdate('operators',array("purview"),array(json_encode($mypurview)),'id='.$id,1,'operators');
				return true;
			}else{
				return false;	
			}
			
	}
}
?>