<?php 
class check{
	/*判断email格式是否正确*/
	function check_email($email)
	{
	    if (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $email)) 
		{
	        return true;
	    } else {
	       return false;
	    }
	}
	
	/*判断手机号码是否正确*/
	function check_mobile($number)
	{
		$mobilephone=trim($number);   
		//手机号码的正则验证   
		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobilephone))
		{   
		    //验证通过 
		    return true;
		}else{   
		    //手机号码格式不对 
		    return false;  
		}
	}
	
	/*判断电话格式是否正确 '010-1234567'*/
	function check_phone($number)
	{
		$number=trim($number);   
		if (!preg_match('/^\d{3,4}-\d{7,8}$/',$number))
		{
			return false;
		}else{
			return true;
		}
	}
	
	/*判断QQ号是否正确*/
	function check_qq($number)
	{
		if(is_numeric($number) && strlen($number)>4 && strlen($number)<13)
		{
			return true;
		}else{
			return false;
		}
	}
}
?>