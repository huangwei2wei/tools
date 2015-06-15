<?php 
namespace kernel\base\base_class;
use kernel\base\base_class\admin_page as NP;
/*充值模型*/
class api_mysql
{
	var $server="kf";
	
	/**
	 * API mysql跨服务器调度
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	public function api_sql($sql,$server)
	{
		$nsql=array();
		$key=ALL_56UU_KEY;

		if(!is_array($sql))
		{
			$nsql[]=$sql;
		}else{
			$nsql=$sql;
		}
		
		$nsql=r_encode(base64_encode(json_encode($nsql)));
		
		$newstr_x=base64_encode($nsql);
		
		$md5=md5($newstr_x.$key.$server);
		
		$put_str=r_encode($newstr_x);
		
		$post_data=array(
							"md5"=>$md5,
							"data"=>$put_str,
						);
		
		switch ($server)
		{
			case "pay":
				$url="http://".$server."1.".DOMAIN."/index.php?module=api&methods=api_get_mysql";
			break;
			
			case "www":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql";
			break;
			
			case "login":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql";
			break;
			
			case "kf":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql";
			break;
		}
		
		$arr=file_post($url,$post_data);
		
		$arr=object_to_array(json_decode($arr));
		
		$data=r_decode($arr["data"]);
		
		if(md5($data.$key.$server)==$arr["md5"])
		{
			$datat=object_to_array(json_decode(base64_decode($data)));
			return isset($datat[0])?$datat[0]:false;
		}else{
			return 0;
		}
	}
	
	/**
	 * API mysql跨服务器调度 分页
	 * @access	Public
	 * @param string $sql		要执行的sql
	 * @param string $server	要指向的服务器  pay|www|login|kf
	 * @return	u		0 |array
	 */
	public function api_sql_p($sql,$server,$page=0,$length=20)
	{
		$nsql=array();
		$key=ALL_56UU_KEY;

		if(!is_array($sql))
		{
			$nsql[]=$sql;
		}else{
			$nsql=$sql;
		}
		
		$nsql=r_encode(base64_encode(json_encode($nsql)));
		
		$newstr_x=base64_encode($nsql);
		
		$md5=md5($newstr_x.$key.$server);
		
		$put_str=r_encode($newstr_x);
		
		$post_data=array(
							"md5"=>$md5,
							"data"=>$put_str,
							"page"=>$page,
							"length"=>$length,
							"url"=>$allurl="http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'],
						);
		
		switch ($server)
		{
			case "pay":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql_p";
			break;
			
			case "www":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql_p";
			break;
			
			case "login":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql_p";
			break;
			
			case "kf":
				$url="http://".$server.".".DOMAIN."/index.php?module=api&methods=api_get_mysql_p";
			break;
		}
		
		$arr=file_post($url,$post_data);
		$arr=object_to_array(json_decode($arr));
		
		$data=r_decode($arr["data"]);
		
		if(md5($data.$key.$server)==$arr["md5"])
		{
			$datat=object_to_array(json_decode(base64_decode($data)));
			return isset($datat[0])?$datat[0]:false;
		}else{
			return 0;
		}
	}
	
	/**
	 * 执行API mysql跨服务器
	 * @access	Public
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function api_get_mysql($data,$md5)
	{
		$r=array();
		$server=$this->server;
		
		$keys=ALL_56UU_KEY;
		
		$get_data=r_decode($data);
		if(md5($get_data.$keys.$server)!=$md5)
		{
			return 0;
		}
		
		$newdata=base64_decode($get_data);
		$newdata=r_decode($newdata);
		$newdata=base64_decode($newdata);
		
		$newdata=object_to_array(json_decode($newdata));
		$sql=$newdata;
		foreach($sql as $key_=>$value)
		{
			$data_tmp=dbarr($value);
			if(!$data_tmp){continue;}
			foreach($data_tmp as $key=>$value)
			{
				foreach($value as $k=>$v)
				{
					if(is_numeric($k))
					{
						unset($data_tmp[$key][$k]);
					}
					
				}
			}
			$r[$key_]=$data_tmp;
		}
		
		$newdata=base64_encode(json_encode($r));
		return json_encode(array("data"=>r_encode($newdata),"md5"=>md5($newdata.$keys.$server)));
	}
	
	/**
	 *带翻页功能的mysql
	 * @access	Public
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function api_get_mysql_p($data,$md5,$page,$length,$url)
	{
		$np=new NP;
		$r=array();
		$server=$this->server;
		
		$keys=ALL_56UU_KEY;
		
		$get_data=r_decode($data);
		if(md5($get_data.$keys.$server)!=$md5)
		{
			return 0;
		}
		
		$newdata=base64_decode($get_data);
		$newdata=r_decode($newdata);
		$newdata=base64_decode($newdata);
		
		$newdata=object_to_array(json_decode($newdata));
		$sql=$newdata;
		
		foreach($sql as $key_=>$value)
		{
			$data_tmp=$np->sql_p($value,$page,$length);
			
			if(!$data_tmp){continue;}
			foreach($data_tmp as $key=>$value)
			{
				foreach($value as $k=>$v)
				{
					if(is_numeric($k))
					{
						unset($data_tmp[$key][$k]);
					}
					
				}
			}
			$r[$key_]=array(
							"data"=>$data_tmp,
							"p"=>$np->p(true,$url,$page)
						);
		}
		
		$newdata=base64_encode(json_encode($r));
		return json_encode(array("data"=>r_encode($newdata),"md5"=>md5($newdata.$keys.$server)));
	}
}
?>