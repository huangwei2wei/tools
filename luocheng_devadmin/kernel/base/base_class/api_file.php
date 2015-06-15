<?php 
namespace kernel\base\base_class;
/*跨服务器读写文件*/
class api_file
{
	var $server="admin.zzql.com";
	var $error="";
	
	/**
	 * API 申请文件跨服务器调度
	 * @access	Public
	 * @param string $info		要读取的文件地址array("path"=>"/aa.php","act"=>"r","data"=>"")
	 * @param string $server	要指向的服务器 地址 s1.qq.zzql.com
	 * @param string $key		客户端密钥
	 * @return	u		0 |array
	 */
	public function api_file($info,$server,$key)
	{
		$nsql=array();

		if(!is_array($info))
		{
			$nsql[]=$info;
		}else{
			$nsql=$info;
		}
		
		$nsql=r_encode(base64_encode(json_encode($nsql)));
		
		$newstr_x=base64_encode($nsql);
		
		$md5=md5($newstr_x.$key.$server);
		
		$put_str=r_encode($newstr_x);
		
		$post_data=array(
							"md5"=>$md5,
							"data"=>$put_str,
						);
		
		$url="http://".$server."/api/api_file.php";
		
		$arr=file_post($url,$post_data);
		$arr_tmp=$arr;
		$arr=object_to_array(json_decode($arr));
		
		$data=r_decode($arr["data"]);
		
		if(md5($data.$key.$server)==$arr["md5"])
		{
			$datat=object_to_array(json_decode(base64_decode($data)));
			return isset($datat[0])?$datat[0]:"";
		}else{
			if($arr_tmp){$this->error=$arr_tmp;}
			return 0;
		}
	}
	
	/**
	 * 执行API 文件跨服务器动作
	 * @access	Public
	 * @param string $data	数据
	 * @param string $md5	校验码	
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function api_get_file($data,$md5)
	{
		$r=array();
		$server=$this->server;
		//取得验证码
		$ip=get_ip();
		$se=dbarr("SELECT b.identifier FROM `server_list` a inner join operators b on a.operators_id=b.id WHERE a.`server_ip`='".$ip."'",0,"operators");
		$se=$se[0];
		
		$keys=$se["identifier"];
		
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
			if(is_array($value))
			{
				if($value["act"]=="r")//读操作
				{
					$data_tmp=@file_get_contents(D_R.$value["path"]);
					$r[$key_]=$data_tmp;
				}
				
				if($value["act"]=="w")//写操作
				{
					if(@file_put_contents(D_R.$value["path"],$value["data"]))
					{
						$r[$key_]=true;
					}else{
						$r[$key_]=false;
					}
				}
			}
		}
		
		$newdata=base64_encode(json_encode($r));
		return json_encode(array("data"=>r_encode($newdata),"md5"=>md5($newdata.$keys.$server)));
	}
	
	/**
	 * 远程写文件
	 * @access	Public
	 * @param string $path	远端路径		
	 * @param string $data	要写的数据
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function w($path,$data)
	{
		$userid=$_SESSION["userid"];
		$server_id=$_SESSION["server_id"];
		if(!$userid || !$server_id)
		{
			echo '<script>location.href="login.php";</script>'; 
			exit; 
		}
		createDir(D_R.$path);
		file_put_contents(D_R.$path,$data);
	}
	
	/**
	 * 让远端下载服务器文件
	 * @access	Public
	 * @param string $downurl	远端要下载的地址
	 * @param string $filename	远端存储的地址
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function down($downurl,$filename)
	{
		$userid=$_SESSION["userid"];
		$server_id=$_SESSION["server_id"];
		if(!$userid || !$server_id)
		{
			echo '<script>location.href="login.php";</script>'; 
			exit; 
		}
		
		$se=dbarr("SELECT b.platform,b.identifier, a.server_id,a.game_web, a.server_ip FROM `server_list` a inner join operators b on a.operators_id=b.id  WHERE `operators_id`=$userid and `server_id`=$server_id",0,"operators");
		if($se)
		{
			$se=$se[0];
			
			$wconfig=array(
							array("path"=>$downurl,"act"=>"down","data"=>$filename),
				);
			
			if($se["game_web"])
			{
				$server=$se["game_web"];
			}else{
				$server="s".$se["server_id"].'.qq.'.$se["platform"];
			}
			$run=$this->api_file($wconfig,$server,$se["identifier"]);
			if(!$run)
			{
				return array("res"=>false,"msg"=>$this->error);
			}else{
				return array("res"=>true,"msg"=>$run);
			}
		}
	}
	
	/**
	 * 解压文件
	 * @access	Public
	 * @param string $filename	远端ZIP存储的地址
	 * @param string $dir	远端解压地址
	 * @return	int		0[失败]|1|2|3...|999[成功]
	 */
	function unzip($filename,$dir)
	{
		$userid=$_SESSION["userid"];
		$server_id=$_SESSION["server_id"];
		if(!$userid || !$server_id)
		{
			echo '<script>location.href="login.php";</script>'; 
			exit; 
		}
		
		$se=dbarr("SELECT b.platform,b.identifier, a.server_id,a.game_web, a.server_ip FROM `server_list` a inner join operators b on a.operators_id=b.id  WHERE `operators_id`=$userid and `server_id`=$server_id",0,"operators");
		if($se)
		{
			$se=$se[0];
			
			$wconfig=array(
							array("path"=>$filename,"act"=>"unzip","data"=>$dir),
				);
			
			if($se["game_web"])
			{
				$server=$se["game_web"];
			}else{
				$server="s".$se["server_id"].'.qq.'.$se["platform"];
			}
			$run=$this->api_file($wconfig,$server,$se["identifier"]);
			if(!$run)
			{
				return array("res"=>false,"msg"=>$this->error);
			}else{
				return array("res"=>true,"msg"=>$run);
			}
		}
	}
}
?>