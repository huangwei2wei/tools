<?php
namespace kernel\base\base_class;

class test
{	
	var $StartTime = 0; 
    var $StopTime = 0; 
    
	function info($module,$methods,$obj)
	{
		$ms=$gs=$ps="";
		if(BUG==1 && $module!="test" && $module!="login"  && $methods!="novels_section_lsit"   && $methods!="add_video_group"
		&& $methods!="del_group" && $methods!="add_record_group" && $methods!="add_pic_group"&& 
		$methods!="novels_type" && $methods!="add_novels_title" && $methods!="video_verification" && 
		$methods!="pick_pic" && $methods!="w_showpic"  && $module!="picshow" && $module!="pic"
		 && $methods!="right_game_lsit"  && $methods!="ajaxdata")
		{
			$url=all_url();
			
			$mysql=$this->get_mysql_log();
			if($mysql)
			{
				foreach((array)$mysql as $key=>$value)
				{
					$ms.=' <tr>
						    <td width="20%" bgcolor="#FFFFFF">'.$value[1].'</td>
						    <td width="80%" bgcolor="#FFFFFF">'.$value[0].'</td>
						  </tr>';
				}
			}
			
			foreach((array)$obj->post as $key=>$value)
			{
				$ps.=' <tr>
					    <td width="20%" bgcolor="#FFFFFF">'.$key.'</td>
					    <td width="80%" bgcolor="#FFFFFF">'.$value.'</td>
					  </tr>';
			}
			
			foreach((array)$obj->get as $key=>$value)
			{
				$gs.=' <tr>
					    <td width="20%" bgcolor="#FFFFFF">'.$key.'</td>
					    <td width="80%" bgcolor="#FFFFFF">'.$value.'</td>
					  </tr>';
			}
			
			$ss=$this->session_trar($obj->session);
			
			return '<table width="100%" border="1">
				  <tr>
				    <td align="left" colspan="2">'.$obj->print.'</td>
				  </tr>
				  <tr>
				    <td align="left"  width="58">post</td>
				    <td align="left"  width="1098">
				    <table bgcolor="#0066FF" width="100%" border="0" cellpadding="0" cellspacing="1">
					  '.$ps.'
					</table>
				    </td>
				  </tr>
				  <tr>
				    <td align="left" >get</td>
				    <td align="left" >
				     <table bgcolor="#0066FF" width="100%" border="0" cellpadding="0" cellspacing="1">
					  '.$gs.'
					</table>
				    </td>
				  </tr>
				  <tr>
				    <td align="left" >session</td>
				    <td align="left" >
				    <table bgcolor="#0066FF" width="100%" border="0" cellpadding="0" cellspacing="1">
					  '.$ss.'
					</table>
				    </td>
				  </tr>
				  <tr>
				    <td align="left" >url</td>
				    <td align="left" >'.$url.'</td>
				  </tr>
				  <tr>
				    <td align="left" >mysql</td>
				    <td align="left" >
				     <table bgcolor="#0066FF" width="100%" border="0" cellpadding="0" cellspacing="1">
					  '.$ms.'
					</table>
				    </td>
				  </tr>
				</table>';
		}
	}
	
	public function session_trar($session)
	{
		$ss="";
		foreach((array)$session as $key=>$value)
		{
			$ss1="";
			if(substr($key,0,1)=="_" || $key=="_mysql"){continue;}
			if(is_array($value))
			{
				foreach((array)$value as $key1=>$value1)
				{
					if(is_array($value1))
					{
						$value1=nl2br(str_replace(" ","&nbsp;",var_export($value1,true)));
					}
					
					$ss1.=' <tr>
					    <td width="20%" bgcolor="#FFFFFF">'.$key1.'</td>
					    <td width="80%" bgcolor="#FFFFFF">'.$value1.'</td>
					  </tr>';
				}
				
				$ss1=' <table bgcolor="#0066FF" width="100%" border="0" cellpadding="0" cellspacing="1">
					  '.$ss1.'
					</table>';
			}
			$vs=isset($ss1)?$ss1:$value1;
			$ss.=' <tr>
				    <td width="20%" bgcolor="#FFFFFF">'.$key.'</td>
				    <td width="80%" bgcolor="#FFFFFF">'.$vs.'</td>
				  </tr>';
		}
		return $ss;
	}
	
	/*处理执行毫秒
	//例子
		$runtime= new test;
		$runtime->start();
		//你的代码结束
		$runtime->stop();
		echo "页面执行时间: ".$runtime->spent()." 毫秒";
	*/
	function get_microtime() 
    { 
        list($usec, $sec) = explode(' ', microtime()); 
        return ((float)$usec + (float)$sec); 
    } 
 
    function start() 
    { 
        $this->StartTime = $this->get_microtime(); 
    } 
 
    function stop() 
    { 
        $this->StopTime = $this->get_microtime(); 
    } 
 
    function spent() 
    { 
        return round(($this->StopTime - $this->StartTime) * 1000, 1); 
    }
    
    /*mysql日志信息*/
    function mysql_log($sql,$time)
    {
    	$u=md5(all_url());
    	$_SESSION["_mysql"][$u][]=array($sql,$time);
    	$mysqllog=@file_get_contents(D_R."/res/log/mysql/mysql.log");
    	file_put_contents(D_R."/res/log/mysql/mysql.log",$mysqllog."\n".$sql."->".$time."\n");
    }
    
	/*读取mysql日志信息*/
    function get_mysql_log()
    {
    	$u=md5(all_url());
    	$session=isset($_SESSION["_mysql"][$u])?$_SESSION["_mysql"][$u]:"";
    	unset($_SESSION["_mysql"]);
    	return $session;
    }
}
?>