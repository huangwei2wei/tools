<?php 
function __autoload($class)
{   
	$class =D_R.str_replace('\\', '/', $class) . '.php'; 
	require_once($class); 
}

/**
 * 模板引入函数
 * @access	Public
 * @param	string	$url	模板地址
 * @param	string	$newarr	动态模板地址 这个要注意，可以是字符串也可以是数组
 * @param	string	$e		0返回模板地址 1返回URL
 * @return	bool	true|false
 */
function parse_tmp($url,$newarr=false,$e=0)
{
	global $parse_tmp;
	$parse_tmp->temp="../template";		//重定义模版目录
	$parse_tmp->enforce="../enforce";	//重定义执行目录
	$parse_tmp->time=BUG?0:3600;		//缓存过期时间
	
	if(!defined('TEMPLATE'))
	{
		define("TEMPLATE","default");
	}
	
	$url_=$parse_tmp->new_temp_cms(TEMPLATE.$url,$newarr);
	if($e==0)
	{
		return $url_;
	}else{
		echo @file_get_contents($url_);
		exit;
	}
}

/**
 * 判断此类下面是否有函数
 *
 * @param string $class	要检查的类
 * @param string $methods 要检查的函数
 * @return array	返回一个数字 e表示状态 true正常，false不正常 ，不正常 会有error错误提示
 */
function class_function($class,$methods=false)
{
	$_f=0;
	
	$tmpclassfun=get_class_methods($class);
	if(!$tmpclassfun){return array("e"=>false,"error"=>$class." 此类不存在<br>");}
	
	if(!$methods)
	{
		return array("e"=>true,"error"=>"");
	}
	
	foreach((array)$tmpclassfun as $value)
	{
		if($methods==$value)
		{
			$_f++;break;
		}
	}
	
	if(!$_f)
	{
		return array("e"=>false,"error"=>$class." 此类的[".$methods."]函数不存在<br>");
	}else{
		return array("e"=>true,"error"=>"");
	}
}

/**
 * 浏览器语言
 *
 */
function language()
{
	$language=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
	$language=explode(',',$language);
	return strtolower($language[0]);
}

/**
 * 读取主URL
 *
 */
function main_url()
{  
	return "http://{$_SERVER['HTTP_HOST']}/"; 
}

/**
 * 读取完整的URL
 *
 */
function all_url()
{
	return "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}";
}

/**
 * 上一步地址URL
 *
 */
function back()
{
	return $_SERVER["HTTP_REFERER"];
}

//取得sid
function sid()
{
	return md5(session_id());
}

/**
 * 读取域，不包含WWW的前最
 *
 */
function domain()
{
	$url=parse_url($_SERVER['HTTP_HOST']);
	$host=isset($url["host"])?$url["host"]:$url["path"];
	$com=strrchr($host,".");
	$host1=str_replace($com,"",$host);
	$d=strrchr($host1,".");
	return substr($d.$com, 1,strlen($d.$com));
}

/**
 * 建立无极目录
 *
 * @param string $dir	目录地址
 * @return bool
 */
function createDir($dir)
{
	$tmp = strrpos($dir, "/");
	$pstr=substr($dir,$tmp,strlen($dir));
	$c=explode(".",$pstr);

	if(count($c)>1){
		$dir=substr($dir,0,$tmp)."/";
	}
	
	$falg = true;
	
	$dir = str_replace(array('//','\\','\\\\'),'/',$dir);
	
	if (!is_dir($dir))
	{
		$temp = explode('/',$dir);
		$cur_dir = '';
		for($i=0;$i<count($temp);$i++) 
		{
			$cur_dir .= $temp[$i].'/';
			
			if (!@is_dir($cur_dir)) 
			{
				if(!@mkdir($cur_dir,0777)) $falg = false;
			} 
		}
	}
	return $falg;
}


/**
 * 读取客户端地址
 *
 * @return string
 */
function get_ip()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	   $cip = $_SERVER["HTTP_CLIENT_IP"];
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	   $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	   $cip = $_SERVER["REMOTE_ADDR"];
	else
	   $cip = "无法获取！";
	return $cip;
}
	
/**
 * 跳转
 *
 * @param string $address	跳转地址
 * @return null
 */
function mygo($address)
{
	header("Location:".main_url()."index.php?p=".$address);
}

/**
 * 将相对路径（包括完全路径）转换成绝对路径
 *
 * @param string $str	需要转换的地址
 * @return $url
 */
function absolute($str)
{
	$vowels ="http://"."{$_SERVER['HTTP_HOST']}"; 
	$str=trim(str_replace($vowels,"",$str));
	$lujin=D_R.$str;
	$lujin = str_replace(array('//','\\','\\\\'),'/',$lujin);
	return $lujin;
}


/**
 * 将绝对路径转换成相对路径
 *
 * @param string $str	需要转换的地址
 * @return $url
 */
function relative($str)
{
	$str=trim(str_replace(D_R,"",$str));
	$str = str_replace(array('//','\\','\\\\'),'/',$str);
	return $str;
}

function arrayed($strs,$flag=false)
{
	/**
	* 通用数组化
	* @param str strs    要切割的字符串
	* @param str flag    切割符号，默认是:
	* @return arr
	*
	*/
	$ss=array();
	if($flag){
		$f1="@";
		$f2="_";
	}else{
		$f1=",";
		$f2=":";
	}
	
	if(!$strs or strlen($strs)==0)
	{
		return false;
	}
	
	$s=explode($f1,$strs);
	foreach($s as $key=>$value)
	{
		$v=explode($f2,$value);
		if(count($v)==2){
			$ss[$v[0]]=$v[1];
		}elseif(count($v)==3){
			$ss[$v[0]]=array($v[1],$v[2]);
		}
	}
	return $ss;
}

#通用字符串化
function stred($arr,$flag=false){
	/**
	* 通用字符串化
	* @param arr arr    要字符串化的数组
	* @param str flag    切割符号，默认是:
	* @return str
	*
	*/
	$strs="";
	if(!is_array($arr))
		return $arr;
	
	if($flag){
		$f1="@";
		$f2="_";
	}else{
		$f1=",";
		$f2=":";
	}
	
	foreach ($arr as $key=>$value){
		if(is_array($value))
		{
			if (!isset($value[0])){
				$value[0]=0;
			}
		
			if (!isset($value[1])){
				$value[1]=0;
			}
			$strs.=$key.$f2.$value[0].$f2.$value[1].$f1;
			
		}else{
			$strs.=$key.$f2.$value.$f1;
		}
	}
	return substr($strs,0,-1);
}

/**
 * 统一消息框
 *
 * @param string $message	消息
 * @param string $url		要跳转的地址
 * @param string $time		等待时间
 * @return go
 */
function message($message,$url='',$time=2000)
{
	$time+=1000;
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="robots" content="ALL" />
<title>正在登陆后台……</title>
<link rel="Shortcut Icon" href="/favicon.ico" type="image/x-icon" />
<script language="javascript" type="text/javascript" src="/template/default/f/js/function.js"></script>
<script language="javascript" type="text/javascript" src="/template/default/f/js/public.js"></script>
<script language="javascript" type="text/javascript" src="/template/default/f/js/login.js"></script>
<link href="/template/default/f/css/css.css" rel="stylesheet" type="text/css">
<script>
function backgo(url){
	window.location=url;
}
			
function msg_countdown(url,time){
	var sys_jinduid=document.getElementById("sys_jindu_id");
	time-=1000;
	if(time<0){
		backgo(url);
		return;
	}else if(time>=0){
	document.getElementById("msg_countdown").innerHTML=(time/1000);
	var data=sys_jinduid.getAttribute("data");
	var tmpjindu=(((data-time)/data)*100);
	tmpjindu=tmpjindu>=100?100:tmpjindu;
	sys_jinduid.style.width=(((data-time)/data)*100)+"%";
		}
if(time==0){
	setTimeout(\"msg_countdown("\"+url+\"",\"+time+\")\",300);
}else{
	setTimeout(\"msg_countdown("\"+url+\"",\"+time+\")\",1000);
	}
		}
</script>
</head>
<body>
<div class="bodybg">
<!--头部状态 开始-->
<div class="toptop" > 
    <div class="toptop_main"> 
        <div class="toptop_main1 ft1"><!--头部状态 左 开始--> 
            <ul></ul>
        </div><!--头部状态 左 结束-->
        <div class="toptop_main2 ft2"><!--头部状态 右 开始-->
        <ul></ul>
        </div><!--头部状态 右 结束-->
    </div>
</div>
<!--头部状态 结束-->
<div class="cr"></div> 

<div class="cr"></div>
';
	echo "<script>
			function backgo(url)
			{
				window.location=url;
			}
			
			function msg_countdown(url,time)
			{
				var sys_jinduid=document.getElementById('sys_jindu_id');
				time-=1000;
				if(time<0){
					backgo(url);
					return;
				}else if(time>=0){
					document.getElementById('msg_countdown').innerHTML=(time/1000);
					var data=sys_jinduid.getAttribute('data');
					var tmpjindu=(((data-time)/data)*100);
					tmpjindu=tmpjindu>=100?100:tmpjindu;
					sys_jinduid.style.width=(((data-time)/data)*100)+'%';
				}
				
				if(time==0)
				{
					setTimeout(\"msg_countdown('\"+url+\"',\"+time+\")\",300);
				}else{
					setTimeout(\"msg_countdown('\"+url+\"',\"+time+\")\",1000);
				}
			}
		</script>";
	
	if(is_numeric($url))
	{
		$urlgo='javascript:window.history.go('.$url.')';
	}else{
		 if($url)
		 {
			$urlgo=$url;
		 }else{
			$urlgo=back();
			$tmpurl=parse_url($urlgo);
			if(!$urlgo || $tmpurl["host"]!=$_SERVER['HTTP_HOST'])
			{
				$urlgo=main_url();
			}
		 }
	}
	echo "
<!--错误提示 开始-->
<div class='err_box'>
    <div class='inside_where'>您的位置：<a href='http://www.56uu.com/' target='_self'>网站后台</a> > <a href='/' target='_self'>提示信息</a> <br></div>
    <div class='err_main'>
        <div class='err_title'>操作提示信息</div>
        <ul>
        <li><span><strong>".$message."</strong></span></li>
		<li><div data='".$time."' class='sys_jindu_id' id='sys_jindu_id'></div></li>
        <li>操作提示：<span class='msg_countdown' id='msg_countdown'>".($time/1000)."</span>秒后返回到登陆页面</li>
        <li><a href='".$urlgo."' target='_self'>如果您的浏览器没有自动跳转，请点击这里</a></li>
        </ul>

    </div>
</div>
<script>msg_countdown('".$urlgo."',".$time.");</script>
<!--错误提示 结束-->
	";

	echo  '
<div class="cr"></div>
<div class="copyright"> 

  </div> 
</div> 
</body>
</html>
	';
	exit;
}

/*****************************************************
描述：概率随机函数
参数：
	$probability 随机率
	$max	100百分比随机  1000千分比随机  10000万分比随机 这个参数有点奇怪，
	默认是100，但如果是万分随机，而这个参数忘记设置，那么程序会修正这个参数，
	但速度上会比外部传递进去的参数产生的随机速度要低
返回值 ：0和1 随机失败返回false
******************************************************/
function rand_p($probability,$max=100)
{
	$probabilityarr=array();
	if(!$probability or !is_numeric($probability)){return false;}
	$tmpprob=$probability*$max;
	if($tmpprob<1)
	{//如果还是小于1，说明max参数有误，这里可以修正这个参数,但效率上可能有些损失
		$max=get_w_p($probability);
		$tmpprob=$probability*$max;
	}
	$max--;
	$m=mt_rand(0,$max);
	
	for($i=0;$i<$tmpprob;$i++)
	{
		$probabilityarr[$i]=1;
	}
	
	for($i=0;$i<$tmpprob;$i++)
	{
		if($i%2==0)
		{
			if(!$probabilityarr[$max-$i])
			{
				$probabilityarr[$max-$i]=1;
				unset($probabilityarr[$i]);
			}
		}
	}
	
	if($probabilityarr[$m])
	{
		return 1;
	}else{
		return 0;
	}
}


/*****************************************************
描述：取得小数位数
参数：
	$probability 随机率
返回值 ：int
******************************************************/
function get_w_p($probability)
{
	$tmp=explode(".",$probability);
	$t=strlen($tmp[1]);

	switch($t)
	{
		case 1:
			$ts=10;
		break;
		case 2:
			$ts=100;
		break;
		case 3:
			$ts=1000;
		break;
		case 4:
			$ts=10000;
		break;
		case 5:
			$ts=100000;
		break;
		case 5:
			$ts=1000000;
		break;
	}
	return $ts;
}

/*****************************************************
描述：随机函数
参数：
	$len  		长度	如果是随机率则值自动6
返回值 ：随机值，如果是谁纪律只返回一个
******************************************************/
function rand_m($len=6)
{
	$chars='ABDEFGHJKLMNPQRSTVWXY123456789'; // characters to build the password from   
	
	$password='';
    
	while(strlen($password)<$len)
	{
		$password.=substr($chars,(mt_rand()%strlen($chars)),1); 
	}    
	return $password; 
}

/*时间处理将时间错转换成X前*/
function fn($time,$flag=false)
{
	$z=time();
	$date1=date("Y-m-d-H-i-s",$z);
	$date2=date("Y-m-d-H-i-s",$z+$time);
	$date_arr1=explode("-", $date1);
	$date_arr2=explode("-", $date2);
	
	$y=$date_arr2[0]-$date_arr1[0];
	$m=$date_arr2[1]-$date_arr1[1];
	$d=$date_arr2[2]-$date_arr1[2];
	$h=$date_arr2[3]-$date_arr1[3];
	$i=$date_arr2[4]-$date_arr1[4];
	$s=$date_arr2[5]-$date_arr1[5];
	
	if($flag)
	{
		if($y){return $y."年前";}
		
		if($m){return $m."个月前";}
		
		if($d){return $d."天前";}
		
		if($h){return $h."个小时前";}
		
		if($i){return $i."分钟前";}
		
		if($s>9){return $s."秒前";}
		
		return  "刚刚";
	}else{
		if($y){return array($y."年前","Y");}
		
		if($m){return array($m."个月前","m");}
		
		if($d){return array($d."天前","d");}
		
		if($h){return array($h."个小时前","H");}
		
		if($i){return array($i."分钟前","i");}
		
		if($s>9){return array($s."秒前","s");}
		
		return  array("刚刚","s");
	}
}

/*过滤掉所有html代码*/
function filterhtml($str)
{
	$str=preg_replace("/\s+/", " ", $str); //过滤多余回车
	$str=preg_replace("/<[ ]+/si","<",$str); //过滤<__("<"号后面带空格)
	   
	$str=preg_replace("/<\!--.*?-->/si","",$str); //注释
	$str=preg_replace("/<(\!.*?)>/si","",$str); //过滤DOCTYPE
	$str=preg_replace("/<(\/?html.*?)>/si","",$str); //过滤html标签
	$str=preg_replace("/(?=href=)([^\>]*)(?=\>)/i","",$str); //过滤a标签
	$str=preg_replace("/<(\/?head.*?)>/si","",$str); //过滤head标签
	$str=preg_replace("/<(\/?meta.*?)>/si","",$str); //过滤meta标签
	$str=preg_replace("/<(\/?body.*?)>/si","",$str); //过滤body标签
	$str=preg_replace("/<(\/?link.*?)>/si","",$str); //过滤link标签
	$str=preg_replace("/<(\/?form.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/cookie/si","COOKIE",$str); //过滤COOKIE标签
	 
	$str=preg_replace("/<(\/?span.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/<(\/?table.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/<(\/?p.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/<(\/?strong.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/<(\/?br.*?)>/si","",$str); //过滤form标签
	$str=preg_replace("/<(\/?img.*?)>/si","",$str); //过滤form标签
	$str=str_replace("&nbsp;","",$str);
	   
	$str=preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si","",$str); //过滤applet标签
	$str=preg_replace("/<(\/?applet.*?)>/si","",$str); //过滤applet标签
	   
	$str=preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si","",$str); //过滤style标签
	$str=preg_replace("/<(\/?style.*?)>/si","",$str); //过滤style标签
	   
	$str=preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si","",$str); //过滤title标签
	$str=preg_replace("/<(\/?title.*?)>/si","",$str); //过滤title标签
	   
	$str=preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si","",$str); //过滤object标签
	$str=preg_replace("/<(\/?objec.*?)>/si","",$str); //过滤object标签
	   
	$str=preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si","",$str); //过滤noframes标签
	$str=preg_replace("/<(\/?noframes.*?)>/si","",$str); //过滤noframes标签
	   
	$str=preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si","",$str); //过滤frame标签
	$str=preg_replace("/<(\/?i?frame.*?)>/si","",$str); //过滤frame标签
	   
	$str=preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$str); //过滤script标签
	$str=preg_replace("/<(\/?script.*?)>/si","",$str); //过滤script标签
	$str=preg_replace("/javascript/si","Javascript",$str); //过滤script标签
	$str=preg_replace("/vbscript/si","Vbscript",$str); //过滤script标签
	$str=preg_replace("/on([a-z]+)\s*=/si","On\\1=",$str); //过滤script标签
	$str=preg_replace("/&#/si","&＃",$str); //过滤script标签，如javAsCript:alert(）
	 return $str;
}



/*
双字节 字数限制 处理程序
*/
function cstr($text,$len)
{
	return cut_str($text,$len);
}

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
	if($code == 'UTF-8')
	{
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);
		
		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
		return join('', array_slice($t_string[0], $start, $sublen));
	}else{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';
			for($i=0; $i<$strlen; $i++)
			{
				if($i>=$start && $i<($start+$sublen))
				{
					if(ord(substr($string, $i, 1))>129) $tmpstr.= substr($string, $i, 2);
					else $tmpstr.= substr($string, $i, 1);
				} 
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
			return $tmpstr;
	}
}


/**
 * 读取一个远程文件类似 file_get_contents()函数
 *
 * @param string $file		要读的url
 * @param string $newfile	存储的文件地址
 * @param string $e			状态 1直接反回数据 0存储到指定地址，并返回bool
 * @return null
 */
function file_get($file,$newfile=false)
{
	// 初始化一个 cURL 对象
	$curl = curl_init(); 
	// 设置你需要抓取的URL
	curl_setopt($curl, CURLOPT_URL, $file);
	// 设置header
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; QQDownload 1.7; TencentTraveler 4.0");
	// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	// 运行cURL，请求网页
	$data = curl_exec($curl);
	// 关闭URL请求
	curl_close($curl);
	//写入获得的数据
	if($data)
	{
		if(!$newfile)
		{
			return $data;
		}else{
			$write = @fopen($newfile,"w");
			fwrite($write,$data);
			fclose($write);
			return true;
		}
	}else{
		return false;
	}
}


/**
 * 读取一个远程文件类似 file_get_contents()函数
 *
 * @param string $file		要读的url
 * @param string $newfile	存储的文件地址
 * @param string $e			状态 1直接反回数据 0存储到指定地址，并返回bool
 * @return null
 */
function file_post($file,$post_data,$newfile=false)
{
	
	//$session_id="PHPSESSID=".session_id();
	// 初始化一个 cURL 对象
	$curl = curl_init(); 
	// 设置你需要抓取的URL
	curl_setopt($curl, CURLOPT_URL, $file);
	//以post的方式提交
	curl_setopt($curl, CURLOPT_POST, true);
	// 设置header
	curl_setopt($curl, CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; QQDownload 1.7; TencentTraveler 4.0");
	
	// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	//传递一个作为HTTP "POST"操作的所有数据的字符串。
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); 
	
	//curl_setopt($curl,CURLOPT_COOKIE,$session_id);
	// 运行cURL，请求网页
	$data = curl_exec($curl);
	// 关闭URL请求
	curl_close($curl);
	//写入获得的数据
	if($data)
	{
		if(!$newfile)
		{
			return $data;
		}else{
			$write = @fopen($newfile,"w");
			fwrite($write,$data);
			fclose($write);
			return true;
		}
	}else{
		return false;
	}
}

//搓动算法
function r_encode($str)
{
	$leng=strlen($str)-1;
	$lasstr=substr($str,-1);
	$jw=ord($lasstr)%9;
	for($i=$leng;$i>0;$i=$i-$jw)
	{
		$tmpstr=$str[$leng-$i];
		$str[$leng-$i]=$str[$i];
		$str[$i]=$tmpstr;
	}
	return trim($str.$jw);
}

//逆向搓动算法
function r_decode($str)
{
	$jw=substr($str,-1);
	$str=substr($str,0,-1);
	$leng=strlen($str)-1;
	
	for($i=0;$i<$leng;$i=$i+$jw)
	{
		$tmpstr=$str[$leng-$i];
		$str[$leng-$i]=$str[$i];
		$str[$i]=$tmpstr;
	}
	return $str;
}

/**
 * 提供给跨域php试用 echo它即可将数据反回给前端
 *
 * @param string $data		要传递的消息
 * @return null
 */
function runad($data)
{
	$data=$data?json_encode($data):"";
	
	
	$callback=isset($_GET["callback"])?$_GET["callback"]:"";
	$parameter=isset($_GET["parameter"])?$_GET["parameter"]:"";
	$parameter=var_export($parameter,true);
	if($callback)
	{
		return "
		var sys_data=$data;
		var parameter=$parameter;
		$callback(sys_data,parameter);";
	}
}

/**
 * 对象转数组
 * @author 佚名
 * @param object $obj
 * @return array
 */
function object_to_array($obj)
{
	if(!$obj){return $obj;}
	$_arr = is_object($obj)?get_object_vars($obj):$obj;
	foreach ($_arr as $key => $val)
	{
		$val = (is_array($val) || is_object($val))?object_to_array($val):$val;
		$arr[$key] = $val;
	}
	return $arr;
}

/**
 * 格子转换成坐标
 * @param string $grid 格子
 * @return  array
 */
function gtox($grid)
{
	$dw=100;
	$dh=120;
	
	$gridarr=explode(":",$grid);
	$gw=$gridarr[0];
	$gh=$gridarr[1];
	
	if($gh%2)
	{
		$x=$gw*$dw-($dw/2);
	}else{
		$x=$gw*$dw;
	}

	$y=(($dh-($dh/4))*$gh)-($dh/4);
	return array("x"=>$x,"y"=>$y,"xy"=>$x.":".$y);
}

/**
 * 坐标转换成格子
 * @param string $xy 坐标
 * @return  array
 */
function xtog($xy)
{
	$dw=100;
	$dh=120;
	
	$xy=explode(":",$xy);
	$x=$xy[0];
	$y=$xy[1];
	
	if($gh%2)
	{
		$nw=$x/$dw-($dw/2);
		$w=($x+round($nw)*1)/$dw-($dw/2);
	}else{
		$nw=$x/$dw;
		$w=($x+round($nw)*1)/$dw;
	}
	
	$nh=$y/($dh-$dh/4);
	$h=($y+(round($nh)*1)+($dh/4))/(($dh-$dh/4)-1);
	
	return array("w"=>$w,"h"=>$h,"grid"=>$w.":".$h);
}

/**
 * 取得第一个中文
 * @param string $str 字符串
 * @return  string
 */
function getonestr($str)
{
    $asc=ord(substr($str,0,1));
    if ($asc<160) //非中文
    {
       return substr($str,0,1);
    }else{   //中文
        return substr($str,0,4);
    }
}

/**
 * 城市名字生成器
 * @param int $map_id 区域地图ID
 * @return  string
 */
function city_name_generate($map_id)
{
	$n=$cn=array();
	$tab=novice($map_id,1);
	$sql="SELECT city_id FROM `base_".$tab."` WHERE map_id=$map_id and city_id>0";

	$mapdata=dbarr($sql);
	if($mapdata)
	{
		foreach($mapdata as $key=>$value)
		{
			$city=dbarr("SELECT name FROM `base_map_city` WHERE `id`=".$value["city_id"]);
			$cn[$city[0]["name"]]=1;
		}
	}
	
	require(D_R."res/data/base_city_name.php");
	foreach($base_city_name as $key=>$value)
	{
		if(!isset($cn[$value["city_name"]]))
		{
			$n[]=$value["city_name"];
		}
	}
	
	$rand=rand(0, count($n));
	
	if($n)
	{
		return $n[$rand];
	}else{
		return "";
	}
}

//数组合并 返回一个数组，该数组包括了所有在 arr1 和arr2中的数据，如果两者键名相同者用arr2覆盖arr1
function arrunite($arr1,$arr2)
{
	foreach((array)$arr2 as $key=>$value)
	{
		if(is_array($value))
		{
			arrunite($arr1[$key],$value);
		}
		$arr1[$key]=$value;
	}
	return $arr1;
}

/*
散装分页函数
$arr 需要分页的数组
$page  每页记录数
$currentPage要取那也的数据
返回:array(
content=>内容,
currentPage =>第几页,
totalPage=>共几页,
)
*/

function spage($arr,$page,$currentPage=0)
{	       
	foreach((array)$arr as $k=>$v)
	{
              $arr1[] = $v;
    }
    $sum=count($arr1);
        
	$totalPage=($sum%$page==0)?intval($sum/$page): intval($sum/$page)+1;//取得总页数
	
	if($currentPage)
	{
		$dp=$currentPage-1;
	}else{
		$dp=0;
	}
	
	for($i=$dp*$page;$i<$sum and $i<$dp*$page+$page;$i++)
	{		

		  $atr[]=$arr1[$i];
              
	}	
	$arr_['content']=$atr;
	   
	$currentPage=($currentPage<1)?1:$currentPage;
	   
	$currentPage=($currentPage>$totalPage)?$totalPage:$currentPage;
        
	if($currentPage ==0){
           $arr_['currentPage'] =1;
        }else{
           $arr_['currentPage']=$currentPage;//第几页
        }
	 $arr_['totalPage']=$totalPage;//共X页
	
	return $arr_;
}

//分页

/* $page  当前页
 * $total 页总数
 * $tab 表名
 */
function pageft($currentPage,$total,$limit=30)
{

	$totalPage=($total%$limit==0)?intval($total/$limit): intval($total/$limit)+1;//取得总页数
	$allurl="http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}"; //全地址
				
	$allurl=str_replace('&page='.$_GET['page'],'',$allurl);
	
	$allurl=str_replace('page='.$_GET['page'],'',$allurl);
	$allurl_=explode('?',$allurl);
			
	if(count($allurl_)==2)
	{
		$url                 = $allurl."&page";
	}else{
		$url                 = $allurl."?page";
	}
				
    $string ='';
    if($currentPage ==1)
    {
      $string .='首页|上一页|';
    }else{
      $string .='<a class ="addparam" href="'.$url.'">首页</a>|<a class ="addparam" href="'.$url.'='.($currentPage-1).'">上一页</a>|';
   }

   if($currentPage ==$totalPage)
   {
      $string .='下一页|尾页|';
   }else{
      $string .='<a class ="addparam" href="'.$url.'='.($currentPage+1).'">下一页</a>|<a class ="addparam" href="'.$url.'='.$totalPage.'">尾页</a>
';
   }
   return $string;
}


//分页2

/* $page  当前页
 * $total 页总数
 * $tab 表名
 */

function pageft2($currentPage, $total)
{
	$allurl="http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}"; //全地址
				
	$allurl=str_replace('&page='.$_GET['page'],'',$allurl);
	
	$allurl=str_replace('page='.$_GET['page'],'',$allurl);
	$allurl_=explode('?',$allurl);
			
	if(count($allurl_)==2)
	{
		$url                 = $allurl."&page";
	}else{
		$url                 = $allurl."?page";
	}
				
    $string ='';
    if($currentPage ==1)
    {
      $string .='首页|上一页|';
    }else{
      $string .='<a class ="addparam" href="'.$url.'">第一页</a>|<a class ="addparam" href="'.$url.'='.($currentPage-1).'">上一页</a>|';
   }

   if($currentPage ==$total)
   {
      $string .='下一页|最后一页';
   }else{
      $string .='<a class ="addparam" href="'.$url.'='.($currentPage+1).'">下一页</a>|<a class ="addparam" href="'.$url.'='.$total.'">最后一页</a>';
   }
   return $string;
}
/**
 * 数据表总数
 * @access	Public
 * @param	string	$res	将结果转换成数组
 * @return	array	res		结果数组
 */
function restoarray($res)
{
	if($res)
	{
		return iterator_to_array($res);
	}else{
		return false;
	}
}

/**
 * 获得指定表的最大ID 需要担当一定的风险，因为没有在大数据中测试效率如何
 * @param obj $mongo 类
 * @param int $table 表名
 * @param int $idfiled ID字段
 * @return  int
 */
function max_id($mongo,$table,$idfiled="id")
{
	$tmmp=$mongo->mongo->find($table,array(),array($idfiled=>1))->sort(array($idfiled=>-1))->limit(1);
	
	$tmp=restoarray($tmmp);
	
	if($tmp)
	{
		foreach($tmp as $value)
		{
			return $value[$idfiled]+1;
		}
	}
	return 1;
}

/**
 * 是否新手地图
 * @param int $map_id 地图ID
 * @param int $e 0布尔 1返回数据表
 * @return  bool
 */
function novice($map_id,$e=0)
{
	$novice=json_decode(_NOVICE_MAP);
	if(in_array($map_id, $novice))
	{
		if($e)
		{
			return "map_data_novice";
		}else{
			return true;
		}
	}else{
		if($e)
		{
			return "map_data";
		}else{
			return false;
		}
	}
}

function int2iP($iIP)  
{  
    $xor = array(0x000000ff,0x0000ff00,0x00ff0000,0xff000000);  
    for($i=0; $i<4; $i++)  
    {  
        ${s.$i} = ($iIP & $xor[$i]) >> $i*8;  
        if (${s.$i} < 0) ${s.$i} += 256;  
    }  
    return $s3.'.'.$s2.'.'.$s1.'.'.$s0;  
}

function ip2int($sIP)  
{  
    $aIP = explode('.',$sIP);  
    $iIP = ($aIP[0] << 24) | ($aIP[1] << 16) | ($aIP[2] << 8) | $aIP[3];
    if($iIP < 0) $iIP += 4294967296;  
    return $iIP;  
}

/*
求两点距离
坐标格式如：55:78
$xy1 自己坐标
$xy2 对方坐标
*/
function distance($xy1,$xy2)
{
	$xy1=explode(':',$xy1);
	$xy2=explode(':',$xy2);
	$var = round (sqrt(pow(($xy2[0]-$xy1[0]),2)+pow(($xy2[1]-$xy1[1]),2)));
	return $var;
}

/*
求两点距离
坐标格式如：55:78
$title 标题
$linedata_arr 数据数组
 array(
 			0=>array(
				"value"=>array(),
				"colour"=>"#F5B50D",
				"key"=>"线标"
			)
	)
*/
function create_line_show($title="增减曲线趋势图",$linedata_arr=array())
{
	require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');

	$line_dot=array();
	$tag=array();
	$y_count1=0;
	$title = new \OFC_Elements_Title($title);	
	foreach($linedata_arr as $key=>$value)
	{
		if(isset($value["colour"])==false)
		{
			$value["colour"]='#F5B50D';
		}
		
		if(isset($value["key"])==false)
		{
			$value["key"]='无';
		}
		
		$data=array();
		$x_tag=array();
		foreach ($value["value"] as $k=>$v)
		{
			$data[]=$v;
		}
		
		$line_dot[$key] = new \OFC_Charts_Line();
		$line_dot[$key]->set_values($data);	//减少
		$line_dot[$key]->set_colour($value["colour"]);
		$line_dot[$key]->set_key($value["key"],16);
		
		if(count($value["value"])>count($tag)){$tag=$value["value"];}
	}
		
	foreach($tag as $k=>$v)
	{
		if(strlen($k)>8)
		{
			$x_tag[]=substr($k,0,4)."-".substr($k,4,2)."-".substr($k,6,2).":".substr($k,8,2);
		} else{
			$x_tag[]=substr($k,0,4)."-".substr($k,4,2)."-".substr($k,6,2);
		}
		
		if($v>$y_count1)
			$y_count1=$v;
	}

	$x = new \OFC_Elements_Axis_X();
	$x->set_offset(false);
	$x -> set_colour('#0000FF');	//蓝色
	//用数组设定X轴下标内容
	$x -> set_labels_from_array($x_tag);
			
	$y=new \OFC_Elements_Axis_Y();
	$y->set_range(0,$y_count1+10,10);
	$y->set_steps($y_count1/4);
	$y->set_colour( '#9900FF');	//紫色
	
	$chart = new \OFC_Chart();
	$chart->set_title( $title );
	$chart->set_x_axis($x);
	$chart->set_y_axis($y);
	
	foreach($linedata_arr as $key=>$value)
	{
		$chart->add_element($line_dot[$key]);
	}
	return  $chart->toPrettyString();
}

//随机生成颜色
//$max 生成多少组，如果是1组返回字符串，如果是多个，返回数组
function rand_color($max=1)
{  
	if($max==1){
		for($a=0;$a<6;$a++){    //采用#FFFFFF方法，  
			$d.=dechex(rand(0,15));//累加随机的数据--dechex()将十进制改为十六进制  
		}  
		return '#'.$d;  
	}else{
		$arrc=array();
		for ($i=0;$i<=$max;$i++)
		{
			$d="";
			for($a=0;$a<6;$a++){    //采用#FFFFFF方法，  
				$d.=dechex(rand(0,15));//累加随机的数据--dechex()将十进制改为十六进制  
			}  
			$arrc[]= '#'.$d;  
		}
		return $arrc;
	}
} 

//生成url
//$parameter_url array(array("url"=>"","name"=>"加入"))
function create_url($parameter_url,$select_parameter)
{
	$xurl="";
	foreach($parameter_url as $key=>$value)
	{
		if($value["name"]==$select_parameter){
			$xurl.=$value["name"]."&nbsp;&nbsp;";
		}else{
			$xurl.="<a href='".$value["url"]."'>".$value["name"]."</a>&nbsp;&nbsp;";
		}
	}
	return $xurl;
}


function properties($goods_info)
{
    /*
    * info 装备属性处理
    * @param dict goods_info 物品数据必须包含property  pro_warsoul pro_strengthen pro_succinct goods_ext_id这几个属性
    * @return dict 返回property
    */
	if(isset($goods_info["pro_warsoul"])==false)
        $goods_info["pro_warsoul"]=array();
        
	if(isset($goods_info["pro_strengthen"])==false)
        $goods_info["pro_strengthen"]=array();
        
	if(isset($goods_info["pro_succinct"])==false)
        $goods_info["pro_succinct"]=array();
        
	if(isset($goods_info["gem_property"])==false)
        $goods_info["gem_property"]=array();
        
    #统计属性
	$tmpbase_goods=dbarr("SELECT * FROM `base_goods_ext` a inner join base_goods b on a.goods_id=b.id WHERE a.goods_ext_id=".$goods_info["goods_ext_id"]);
	
    $base_goods_ext=$tmpbase_goods[0];
    $properties=arrayed($base_goods_ext["property"]);
	if(isset($properties["by"]))
        $properties["by"][1]=0;
        
	if(isset($properties["ea"]))
        $properties["ea"][1]=0;

    #战魂产生的属性
    if(is_array($goods_info["pro_warsoul"])==false){
        $pro_warsoul=arrayed($goods_info["pro_warsoul"]);
	}else{
        $pro_warsoul=$goods_info["pro_warsoul"];
	}
        
    #强化产生的属性
    if(is_array($goods_info["pro_strengthen"])==false){
        $pro_strengthen=arrayed($goods_info["pro_strengthen"]);
	}else{
        $pro_strengthen=$goods_info["pro_strengthen"];
	}
        
    #洗练产生的属性
    if(is_array($goods_info["pro_succinct"])==false){
        $pro_succinct=arrayed($goods_info["pro_succinct"]);
	}else{
        $pro_succinct=$goods_info["pro_succinct"];
	}
        
    #宝石产生的属性
    if(is_array($goods_info["gem_property"])==false){
        $gem_property=arrayed($goods_info["gem_property"]);
	}else{
        $gem_property=$goods_info["gem_property"];
	}
            
    $pro_list=array($pro_warsoul,$pro_strengthen,$pro_succinct,$gem_property);
    foreach($pro_list as $k=>$obj){
		foreach($obj as $key=>$value){
			if(isset($properties[$key]))
            {
                if(intval($properties[$key][0])==0){
                    $properties[$key][1]=intval($properties[$key][1])+intval($obj[$key][1]);
				}else{
                    $properties[$key][1]=floatval($properties[$key][1])+floatval($obj[$key][1]);
				}
			}else{
                $properties[$key]=$value;
			}
		}
	}
    return $properties;
}
?>