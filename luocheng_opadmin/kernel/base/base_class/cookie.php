<?php 
namespace kernel\base\base_class;

class cookie
{
	var $domain=".56uu.com";
	var $path="/";
	var $secure=false;
	
 	public function __construct()
    {
    	if(defined('DOMAIN')) 
    	{
    		$this->domain=DOMAIN;
    	}
    }
    
	function set($key,$value,$time=0)
	{
		if(!$time){$time=0;}else{$time+=time();}
		setcookie($key,$value,$time, $this->path,$this->domain,$this->secure);
		//setcookie($key,$value,$time);
	}
	
	function get($key)
	{
		return isset($_COOKIE[$key])?$_COOKIE[$key]:false;
	}
	
	function del($key)
	{
		setcookie($key,false,time()-9999999,$this->path,$this->domain,$this->secure);
	}
}
?>