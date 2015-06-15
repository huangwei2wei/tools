<?php 
namespace kernel\base\base_class;

class upfile
{
	/*////////////////////////////////////////////////////////////////////////////////////
	 * 函数名：upfile
	 * 功  能：文件上传
	 * 参  数：
	 * $filename="upload_file";// 表单名
	 * $parameter=array(
	 *		"types"=>array('image/jpg','image/jpeg'),//上传类型       $uptypes=array('image/jpg','image/jpeg')
	 *		"file_size_max"=>1024,//允许上传容量   如$file_size_max = (1024*1024)*2; 
	 *		"store_dir"=>"",//上传存储路径   如$store_dir = "upimg/";
	 *		"show"=>"",//是否显示上传过程中的信息 空为不显示     默认为空
	 *		"autoback"=>"",// 如果出错则返回上一页     空位不返回  	 默认为空
	 *		"bbn"=>"",// 是否保存原始名           空为否		     默认为空
	 *		"accept_overwrite"=>"",//是否被允许覆盖           为空为不允许   默认为空
	 *  );
	 *	返回值：新文件名 和原文件名
	//////////////////////////////////////////////////////////////////////////////////////*/
	function up($filename,$parameter)
	{
		$uptypes=isset($parameter["types"])?$parameter["types"]:"";
		$file_size_max=isset($parameter["file_size_max"])?$parameter["file_size_max"]:"";
		$store_dir=isset($parameter["store_dir"])?$parameter["store_dir"]:"";
		$show=isset($parameter["show"])?$parameter["show"]:"";
		$autoback=isset($parameter["autoback"])?$parameter["autoback"]:"";
		$bbn=isset($parameter["bbn"])?$parameter["bbn"]:"";
		$accept_overwrite=isset($parameter["accept_overwrite"])?$parameter["accept_overwrite"]:"";
		
		$uptypes=$uptypes?$uptypes:array('image/jpg','image/jpeg');
		$file_size_max=$file_size_max?$file_size_max:1024;
		$store_dir=$store_dir?$store_dir:"/res/up";
		
		$upload_file=$_FILES[$filename]['tmp_name'];
		$upload_file_size=$_FILES[$filename]['size'];
		if(!$upload_file)
		{
			return;
		}
		
		$upload_file_name=$_FILES[$filename]['name'];
		
		$REFERER=$_SERVER['HTTP_REFERER'];
		
		if(!$bbn)
		{
			$names=substr($upload_file_name,0,strrpos($upload_file_name,'.')); //取得文件名
			$exptta=strrchr($upload_file_name,'.');
			$exptta=trim($exptta);  //取得扩展名
			$time=microtime();
			$time=explode(' ',$time);
			
			$times=str_replace('0.','',$time[0]);
			$name=$time[1].$times.$exptta;
		}else{
			$name=$upload_file_name;
		}
		$file = $_FILES[$filename]; 
		if(!in_array($file["type"], $uptypes)) 
				//检查文件类型 
		{ 
			if($autoback)
			{
				 print "<script>
							alert('只能上传图像文件或Flash！');
							window.location='".$REFERER."';
					   </script>";
			}else{
				 print "<script>
							alert('只能上传图像文件或Flash！');
					   </script>";
			}	   
		 	exit; 
		}
		$names=substr($upload_file_name,0,strrpos($upload_file_name,'.')); //取得文件名
		$exptta=strrchr($upload_file_name,'.');
		$exptta=trim($exptta);  //取得扩展名
		$time=time();
		if($upload_file)
		{
			
			// 检查文件大小
			if ($upload_file_size > $file_size_max) 
			{
				if($autoback)
				{
					print "<script>
								alert('对不起，你的文件容量大于规定');
								window.location='".$REFERER."';
						   </script>";
				}else{
				    print "<script>
							alert('对不起，你的文件容量大于规定');
					      </script>";
				}	
				exit;
			}
			
			// 检查读写文件
			if (file_exists($store_dir . $name) && !$accept_overwrite)
			{
				if($autoback)
				{
					print "<script>
								alert('存在相同文件名的文件');
								window.location='".$REFERER."';
						   </script>";
				}else{
				    print "<script>
							alert('存在相同文件名的文件');
					      </script>";
				}
				exit;
			}
			
			//复制文件到指定目录
			if (!move_uploaded_file($upload_file,$store_dir.$name))
			{
				if($autoback)
				{
					print "<script>
								alert('文件复制失败');
								window.location='".$REFERER."';
						   </script>";
				}else{
				    print "<script>
							alert('文件复制失败');
					      </script>";
				}
				exit;
			}
		
		}
		
		if($show)
		{
			echo   "<p>你上传了文件:";
			echo  $_FILES[$filename]['name'];
			echo "<br>";
			//客户端机器文件的原名称。
			
			echo   "文件的 MIME 类型为:";
			echo $_FILES[$filename]['type'];
			//文件的 MIME 类型，需要浏览器提供该信息的支持，例如“image/gif”。
			echo "<br>";
			
			echo   "上传文件大小:";
			echo $_FILES[$filename]['size'];
			//已上传文件的大小，单位为字节。
			echo "<br>";
			
			echo   "文件上传后被临时储存为:";
			echo $_FILES[$filename]['tmp_name'];
			//文件被上传后在服务端储存的临时文件名。
			echo "<br>";
			
			
			$Erroe=$_FILES[$filename]['error'];
			
			switch($Erroe)
			{
				case 0:
					echo   "上传成功"; 
					print"<script>
							alert('上传成功！');
							window.location='".$REFERER."';
						 </script>";
				break;
				
				case 1:
					echo   "上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值."; 
					print"<script>
							alert('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！');
							window.location='".$REFERER."';
						 </script>";
				break;
				
				case 2:
					echo   "上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。";
					print"<script>
							alert('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！');
							window.location='".$REFERER."';
						 </script>";
				break;
				
				case 3:
					echo   "文件只有部分被上传";
					print"<script>
							alert('文件只有部分被上传！');
							window.location='".$REFERER."';
						 </script>";
				break;
				
				case 4:
					echo   "没有文件被上传";
					print"<script>
							alert('没有文件被上传！');
							window.location='".$REFERER."';
						 </script>";
				break;
			}
		}
		return array($name,$names);
	}
}
?>