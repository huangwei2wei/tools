<?php
function file_get_pic($url) 
{
	$useragent="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; QQDownload 1.7; TencentTraveler 4.0";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT,20);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	//curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_ENCODING ,"gzip");//解压方式
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
}

//取得指定url的域名，不包含WWW的前最

function getdomain($url)
{
	if(!$url){return false;}

	$url=parse_url($url);

	$host=$url["host"];

	$com=strrchr($host,".");

	$host1=str_replace($com,"",$host);

	$d=strrchr($host1,".");

	return substr($d.$com, 1,strlen($d.$com));

}

$url="http://club.auto.qq.com/thread-354758-1-1.html";
function getimg($url)
{
	$img=array();
	$str=file_get_pic($url);
	$strPreg = '/<img(.+?)\/>/';
	preg_match_all($strPreg,$str,$arr1);
	if($arr1 && isset($arr1[1]))
	{
		foreach($arr1[1] as $value)
		{
			preg_match_all("/[src|file|orgsrc]{3,8}+=[\'\"]([\s\S]*?)[\"\']/i",$value,$arr2);
			if($arr2 && isset($arr2[1]))
			{
				foreach($arr2[1] as $vss)
				{
					$img[md5($vss)]=$vss;
				}
			}
		}
	}
	
	preg_match_all("/([^\"\' ]+)([\.][jpg|jpeg|gif|png]{3,4})([^\"\' ]+)/i",$str,$arr);
	if($arr && isset($arr[0]))
	{
		foreach($arr[0] as $vss)
		{
			$img[md5($vss)]=$vss;
		}
	}
	return $img;
}

function analysis_img($img,$url)
{
	$webhost=getdomain($url);
	$newimg=array();
	$pcshu=array(".","(",")","{","}");
	foreach($img as $tmp_value)
	{
		$tmp_value=trim($tmp_value);
		if(substr($tmp_value,0,4)!="http")
		{
			if(substr($tmp_value,0,1)=="/")
			{
				$newimg[]="http://".$webhost."/".$tmp_value;
			}elseif(substr($tmp_value,0,2)=="./"){
				$newimg[]=substr($url,0,strrpos($url,"/"))."/".substr($tmp_value,1,strlen($tmp_value));
			}elseif($tmp_value && !in_array(substr($tmp_value,0,1),$pcshu)){//这里要做严格处理
				if(strrpos($tmp_value, ";") || strrpos($tmp_value, ":") || strrpos($tmp_value, "-")  || strrpos($tmp_value, '(')  || strrpos($tmp_value, ')'))
				{
					continue;
				}
				$newimg[]=substr($url,0,strrpos($url,"/"))."/".$tmp_value;
			}
		}else{
			$newimg[]=$tmp_value;
		}
	}
	return $newimg;
}

$img=getimg($url);
$newimg=analysis_img($img,$url);

print_r($newimg);