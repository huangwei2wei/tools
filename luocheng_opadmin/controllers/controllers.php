<?php 
namespace controllers;
use kernel\base\base_class\res as RES;
use module\security\security as SECURITY;
use kernel\base\base_class\test as _TEST;

/*
 * 程序总控制器，下层程序必须有下面接口
 * 接口类第一个字母必须以i开头
 */
class controllers
{
	var $_bug=BUG;
	var $obj;
	var $res;
	var $security;
	var $funlist;
	var $classlist;
	var $arrayFileName;
	var $post;
	var $get;
	var $session;
	var $mysql;
	var $mem;
	var $mongo;
	var $print="<a href='/index.php?module=test' target='_blank'>测试【查看模型函数】</a>";
	
	var $username;
	var $userid;
	
	public function __construct($sign=false)
	{

		$this->username=isset($_SESSION["operators"])?$_SESSION["operators"]:"";
		$this->userid=isset($_SESSION["userid"])?$_SESSION["userid"]:0;
		
		$this->module($sign);
	}

	public function start()
	{
		global $_MODULE,$mton,$sql,$mem,$mongo;
		$this->security=new SECURITY;
		$this->res=new RES;
		
		$this->post=$this->security->get(false,"post");
		$this->get=$this->security->get(false,"get");
		$this->session=$this->security->get(false,"session");
		$this->mton=$mton;
		$this->mysql=$sql;
		$this->mem=$mem;
		$this->mongo=$mongo;
		
		$module=isset($this->get["module"])?$this->get["module"]:"";
		$module=$module?$module:"index";
		$methods=isset($this->get["methods"])?$this->get["methods"]:"";
		$methods=$methods?$methods:"index";	
		if(isset($_MODULE[$module]) && !$_MODULE[$module])
		{
			exit("此模块未开放");
		}
		
		
		if (!isset($_SESSION["operators"]) and in_array($module,array("login","p","test","boss_server","map"))==false)
		{
			message("您尚未登录，请先登录……","/?module=login");
		}
		
		//$class="views\\".$module."\\".$module;
		$class="plugins\\".$module."\\".$module;
		
		if($this->_bug)
		{
			$class_function=class_function($class,$methods);
			if(!$class_function["e"])
			{
				exit($class_function["error"]);
			}
		}
			
		$views_obj=new $class;
		$views_obj->$methods($this);
		
		if($this->_bug)
		{
			//测试类
			$_test=new _TEST;
			echo $_test->info($module,$methods,$this);
		}
		
		return $views_obj;
	}
	
	
	private function module($sign=false)
	{
		if($sign)
		{
			//$class="module\plugins\\".$sign."\\i".$sign;
			$class="plugins\\".$sign."\\i".$sign;
			$this->obj=new $class;
			
			if($this->_bug)
			{
				$this->funlist=$this->funlist($sign);
			}
		}
		
		if($this->_bug)
		{
			/*功能类列表及说明*/
			//$path=D_R."module/plugins/";
			$path=D_R."plugins/";
			$this->InDirFileInfo($path);
			$this->classlist=$this->classsm();
			unset($this->arrayFileName);
		}
		return $this->obj;
	}
	
	
	
	private function funlist($sign)
	{
		//$class=D_R."module/plugins/".$sign."/i".$sign.".php";
		$class=D_R."plugins/".$sign."/i".$sign.".php";
		$value = file_get_contents($class);
		$arr=$arr1=array();
		@preg_match_all('/\/\*\*(.+?)[function]+(.+?)\)/is', $value, $arr);
		
		foreach((array)$arr[0] as $key=>$value)
		{
			@preg_match_all('/function(.+?)\)/is', $value,$arr1);
			$use="use module\\plugins\\".$sign."\\i".$sign." as ".strtoupper($sign).";";
			$news='$'.$sign.'=new '.strtoupper($sign).";";
			$fu='$'.$sign."->".trim(str_replace("function","",$arr1[0][0])).";";
			$arr[0][$key].="\n"."call"."\n".$use."\n".$news."\n".$fu;
		}
		
		return $arr[0];
	}
	
	private function InDirFileInfo($dir,$type=".php")
	{ 
		if(!is_dir($dir))return	false; 
		$dirhandle=opendir($dir); 
		$arrayFileName=array(); 
		while(($file=readdir($dirhandle))!==false)
		{ 
			if($file!= "." && $file!= "..")
			{ 
				if(is_dir($dir."/".$file))
				{
					$this->InDirFileInfo($dir."/".$file,$type);
				}else{
					
					$typelen=0-strlen($type); 
					
					if(substr($file,$typelen)==$type)
					{
						if(substr($file,0,1)=="i")
						{
							$this->arrayFileName[]=array( 
								"FileName"=>$dir."/".$file
							);
						}
					}
				}
			}
		}
	}
	
	private function classsm()
	{
		$cm=array();
		foreach($this->arrayFileName as $value)
		{
			$info =file_get_contents($value["FileName"]);
			$arr=array();
			@preg_match_all('/\/\*(.+?)\*\//is', $info, $arr);
			$classname=str_replace(".php","",substr($value["FileName"],strripos($value["FileName"],"/")+1));
			$cm[] =array(
					"classname"=>substr($classname,1,strlen($classname)),
					"path"=>$value["FileName"],
					"info"=>isset($arr[0][0])?$arr[0][0]:""
			);
		}
		return $cm;
	}
}
?>