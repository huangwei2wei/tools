<?php
namespace kernel\base\tool;

/**
 * 将memcached中的数据处理成文件，将文件中的数据处理到mysql里，将数据文件导入memcached里
 */
class data_process{
	var $mem;
	var $mysqldata;
	var $mem_data_all;
	function __construct()
	{
		require(D_R."config/config_mysql.php");
		global $mem;
		$this->mem=$mem;
		$this->mysqldata=$mysqldata;
		$this->mem_data_all=new MEM_DATA_ALL;
	}

	function __destruct()
	{
		unset($this->mem,$this->mysqldata);
	}

	/**
	 * 将memcached中的数据处理成文件
	 *
	 */
	function mem_to_file()
	{
		$tmpdata=$this->mem_data_all->get("user_list");
		if(!$tmpdata){return false;}
		
		$lists=$this->mem_data_all->lists();
		$dird=dirb("tmpdata",2,"dir");
		if(!$dird)
		{
			return false;
		}
		
		$tmpdata="";
		foreach((array)$this->mysqldata as $key=>$value)
		{
			$tmpdatastr="";
			$tmpdata="";
			if($value[0]==1)
			{
				if($key=="user_ext_ft")
				{
					foreach((array)$lists as $k=>$v)
					{
						$tmpdata=$this->mem_data_all->get("online_".$k);
						if(!$tmpdata)
						{
							$tmpdata=$this->mem_data_all->get("offline_".$k);
						}
						if($tmpdata)
						{
							$tmpdatastr.=json_encode($tmpdata)."_innerback2010_".$k."_rzfyback20100827_";
						}
					}
					if($tmpdatastr)
					{
						@file_put_contents($dird.$key.".data", gzcompress($tmpdatastr));
					}
				}elseif($key=="user"){
					foreach((array)$lists as $k=>$v)
					{
						$tmpdata=$this->mem_data_all->get("user_info_".$k);
						
						if($tmpdata)
						{
							$tmpdatastr.=json_encode($tmpdata)."_innerback2010_".$k."_rzfyback20100827_";
						}
					}
					if($tmpdatastr)
					{
						@file_put_contents($dird.$key.".data", gzcompress($tmpdatastr));
					}
				}else{
					foreach((array)$lists as $k=>$v)
					{
						$tmpdata=$this->mem_data_all->get($key."_".$k);
						if($tmpdata)
						{
							$tmpdatastr.=json_encode($tmpdata)."_innerback2010_".$k."_rzfyback20100827_";
						}
					}
					if($tmpdatastr)
					{
						@file_put_contents($dird.$key.".data", gzcompress($tmpdatastr));
					}
				}
			}else{
				$tmpdata=$this->mem_data_all->get($key);
				
				if($tmpdata)
				{
					$tmpdatastr=json_encode($tmpdata);
					if($tmpdatastr)
					{
						@file_put_contents($dird.$key.".data", gzcompress($tmpdatastr));
					}
				}
			}
		}
	}
	
	/**
	 * 将数据文件导入memcached里
	 *
	 */
	function file_to_mem($d=false,$h=false,$i=false)
	{
		if($d || $h || $i)
		{
			if(!$d || !$h || !$i)
			{
				return false;
			}
		}
		
		$newarr=array();
		$get_dir_file=$this->get_dir_file($d,$h,$i);

		foreach((array)$get_dir_file as $key=>$value)
		{
			unset($newarr,$objarr);
			$file = basename($value,".data");
			
			$tmpdata=@file_get_contents($value);

			if($tmpdata)
			{
				if($this->mysqldata[$file][0]==1)
				{
					$tmpdata_=gzuncompress($tmpdata);
					$tmpdata_arr=explode("_rzfyback20100827_",$tmpdata_);
					foreach((array)$tmpdata_arr as $k=>$v)
					{
						$tmpdata_arr_=explode("_innerback2010_",$v);
						if($tmpdata_arr_[0])
						{
							$objarr=obj_to_array(json_decode($tmpdata_arr_[0]));
							if(is_array($objarr))
							{
								if($file=="user_ext_ft")
								{
									$this->mem_data_all->settab("offline_".$tmpdata_arr_[1],$objarr);
								}elseif($file=="user"){
									$this->mem_data_all->settab("user_info_".$tmpdata_arr_[1],$objarr);
								}else{
									$this->mem_data_all->settab($file."_".$tmpdata_arr_[1],$objarr);
								}
							}
						}
					}
				}else{
					if($tmpdata)
					{

						$objarr=obj_to_array(json_decode(gzuncompress($tmpdata)));
						if(is_array($objarr))
						{
							$this->mem_data_all->settab($file,$objarr);
						}
					}
				}
			}
		}
	}
	
	
	/**
	 * 将数据文件导入memcached里(个人)
	 *
	 */
	function user_file_to_mem($user_id,$d=false,$h=false,$i=false)
	{
		if($d || $h || $i)
		{
			if(!$d || !$h || !$i)
			{
				return false;
			}
		}
		
		$newarr=array();
		$get_dir_file=$this->get_dir_file($d,$h,$i);

		foreach((array)$get_dir_file as $key=>$value)
		{
			unset($newarr,$objarr);
			$file = basename($value,".data");
			
			$tmpdata=@file_get_contents($value);

			if($tmpdata)
			{
				if($this->mysqldata[$file][0]==1)
				{
					$tmpdata_=gzuncompress($tmpdata);
					$tmpdata_arr=explode("_rzfyback20100827_",$tmpdata_);
					foreach((array)$tmpdata_arr as $k=>$v)
					{
						$tmpdata_arr_=explode("_innerback2010_",$v);
						if($tmpdata_arr_[0])
						{
							$objarr=obj_to_array(json_decode($tmpdata_arr_[0]));
							if(is_array($objarr))
							{
								if($objarr["user_id"]==$user_id)
								{
									if($file=="user_ext_ft")
									{
										$this->mem_data_all->settab("offline_".$tmpdata_arr_[1],$objarr);
									}elseif($file=="user"){
										$this->mem_data_all->settab("user_info_".$tmpdata_arr_[1],$objarr);
									}else{
										$this->mem_data_all->settab($file."_".$tmpdata_arr_[1],$objarr);
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	/*单个文件导入*/
	function file_to_mem_d($value)
	{
		$file = basename($value,".data");
		$tmpdata=@file_get_contents($value);
		if($tmpdata)
		{
			if($this->mysqldata[$file][0]==1)
			{
				$tmpdata_=gzuncompress($tmpdata);
				$tmpdata_arr=explode("_rzfyback20100827_",$tmpdata_);
				foreach((array)$tmpdata_arr as $k=>$v)
				{
					$tmpdata_arr_=explode("_innerback2010_",$v);
					if($tmpdata_arr_[0])
					{
						$objarr=obj_to_array(json_decode($tmpdata_arr_[0]));
						if(is_array($objarr))
						{
							if($file=="user_ext_ft")
							{
								$this->mem_data_all->settab("offline_".$tmpdata_arr_[1],$objarr);
							}elseif($file=="user"){
								$this->mem_data_all->settab("user_info_".$tmpdata_arr_[1],$objarr);
							}else{
								$this->mem_data_all->settab($file."_".$tmpdata_arr_[1],$objarr);
							}
						}
					}
				}
			}else{
				if($tmpdata)
				{

					$objarr=obj_to_array(json_decode(gzuncompress($tmpdata)));
					if(is_array($objarr))
					{
						$this->mem_data_all->settab($file,$objarr);
					}
				}
			}
		}
	}

	/**
	 * 将最后备份的文件数据处理到mysql里
	 *
	 */
	function file_to_mysql($d=false,$h=false,$i=false)
	{
		if($d || $h || $i)
		{
			if(!$d || !$h || !$i)
			{
				return false;
			}
		}
		
		$newarr=array();
		$get_dir_file=$this->get_dir_file($d,$h,$i);

		foreach((array)$get_dir_file as $key=>$value)
		{
			unset($newarr,$objarr);
			$file = basename($value,".data");
			
			$tmpdata=@file_get_contents($value);

			if($tmpdata)
			{
				if($this->mysqldata[$file][0]==1)
				{
					$tmpdata_=gzuncompress($tmpdata);
					$tmpdata_arr=explode("_rzfyback20100827_",$tmpdata_);
					foreach((array)$tmpdata_arr as $k=>$v)
					{
						$tmpdata_arr_=explode("_innerback2010_",$v);
						if($tmpdata_arr_[0])
						{
							$objarr=obj_to_array(json_decode($tmpdata_arr_[0]));
							if(is_array($objarr))
							{
								$this->setmysqltab($file,$tmpdata_arr_[1],$objarr);
							}
						}
					}
				}else{
					if($tmpdata)
					{
						$objarr=obj_to_array(json_decode(gzuncompress($tmpdata)));
						if(is_array($objarr))
						{
							foreach((array)$objarr as $kk=>$vv)
							{
								$this->setmysqltab($file,$kk,$vv);
							}
						}
					}
				}
			}
		}
			
	}
	
	/**
	 * 将单个文件中的数据处理到mysql里
	 *
	 */
	function file_to_mysql_d($dir)
	{
		$file = basename($value,".data");
		$tmpdata=@file_get_contents($value);
		if($tmpdata)
		{
			if($this->mysqldata[$file][0]==1)
			{
				$tmpdata_=gzuncompress($tmpdata);
				$tmpdata_arr=explode("_rzfyback20100827_",$tmpdata_);
				foreach((array)$tmpdata_arr as $k=>$v)
				{
					$tmpdata_arr_=explode("_innerback2010_",$v);
					if($tmpdata_arr_[0])
					{
						$objarr=obj_to_array(json_decode($tmpdata_arr_[0]));
						if(is_array($objarr))
						{
							$this->setmysqltab($file,$tmpdata_arr_[1],$objarr);
						}
					}
				}
			}else{
				if($tmpdata)
				{
					$objarr=obj_to_array(json_decode(gzuncompress($tmpdata)));
					if(is_array($objarr))
					{
						foreach((array)$objarr as $kk=>$vv)
						{
							$this->setmysqltab($file,$kk,$vv);
						}
					}
				}
			}
		}
	}
	
	/**
	 * 插入数据库程序
	 *
	 */
	function setmysqltab($tab,$id,$info)
	{
		if($tab=="user_list" or $tab=="user_list")
		{
			return;
		}
		$value=array();
		$field=array();
		foreach((array)$info as $k=>$v)
		{
			if($tab=="user")
			{
				if(is_array($v))
				{
					$value1=array();
					$field1=array();
					foreach((array)$v as $kk=>$vv)
					{
						$value1[]=$vv;
						$field1[]=$kk;
						if($kk=="id")
						{
							$ide=$vv;
						}
					}
					//echo "SELECT * FROM `user_ext` WHERE `id`=".$id."@<br>";
					$user_e=dbarr("SELECT * FROM `user_ext` WHERE `id`=".$ide);
					if($user_e)
					{
						dbupdate("user_ext", $field1, $value1,"id=".$id);
					}else{
						dbinsert("user_ext", $field1, $value1);
					}
					unset($field1,$value1);
				}else{
					if($k!="pwd")
					{
						$value[]=$v;
						$field[]=$k;
						if($k=="id")
						{
							$ids=$v;
						}
					}
				}
			}else{
				if(!is_array($v))
				{
					$value[]=$v;
					$field[]=$k;
				}
				if($k=="id")
				{
					$ids=$v;
				}
			}
		}
		if($tab=="user_goods" or $tab=="user_goods_depot")
		{
			//echo "SELECT * FROM `$tab` WHERE `goods_user_id`=".$id."#<br>";
			$user_e=dbarr("SELECT * FROM `$tab` WHERE `goods_user_id`=".$id);
			if($user_e)
			{
				dbupdate($tab, $field, $value,"goods_user_id=".$ids);
			}else{
				dbinsert($tab, $field, $value);
			}
		}else{
			//echo "SELECT * FROM `$tab` WHERE `id`=".$id."#<br>";
			$user_e=dbarr("SELECT * FROM `$tab` WHERE `id`=".$id);
			if($user_e)
			{
				dbupdate($tab, $field, $value,"id=".$ids);
			}else{
				dbinsert($tab, $field, $value);
			}
		}
		
		unset($value,$field);
		
	}
	/**
	 * 按时间取得对应目录里的文件，如果输入时间参数全为空则取最后创建的目录
	 *
	 */
	function get_dir_file($d=false,$h=false,$i=false)
	{
		$filearray=array();
		$dir=D_R."log/tmpdata/".date("Y-m",time()).'/';
		$dir_=$dir;
		if($d)
		{
			$dir.=date("d",time()).'/';
		}
		
		if($h)
		{
			$dir.=date("H",time()).'/';
		}
		
		if($i)
		{
			$dir.=date("i",time()).'/';
		}
		
		if($dir!=$dir_)
		{
			$get_dir_file=get_dir_files($dir);
			$filearray=$get_dir_file["file"];
		}else{
			$dir.=date("d",time()).'/';
			$get_dir_file=get_dir_files($dir);
			
			$tmpdir_=$this->get_final_dir($get_dir_file["dir"]);
			if(!$tmpdir_)
			{
				return false;
			}
			$get_dir_file=get_dir_files($tmpdir_);
			
			$tmpdir_=$this->get_final_dir($get_dir_file["dir"]);
			
			$get_dir_file=get_dir_files($tmpdir_);
			if(!$get_dir_file)
			{
				return false;
			}
			$filearray=$get_dir_file["file"];
		}
		return $filearray;
	}
	
	/**
	 * 取得数组中的最后创建的那个文件
	 * $dirarr 目录数组
	 */
	function get_final_dir($dirarr)
	{
		$tmpdir=array();
		foreach((array)$dirarr as $k=>$v)
		{
			if(@filectime($v))
			{
				$user_time=@filectime($v);
			}else{
				$user_time=@filemtime($v);
			}
			$tmpdir[$user_time]=$v;
		}
		return array_pop($tmpdir);
	}
	
	/**
	 * 取得最近10天备份列表
	 *
	 */
	function get_dir_file10($dir)
	{
		$filearray=array();
		if(!$dir)
		{
			$dir=D_R."log/tmpdata/";
		}
		
		$filearray=get_dir_files($dir,array("svn"),$layers=false);
		return $filearray;
	}
}
?>