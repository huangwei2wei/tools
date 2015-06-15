<?php
require("../../lib/public.php");
class dirlist{
	var $list;
	var $rep=array();//允许通过的扩展名，空为所有都可以
	
	function lists($dir)
	{
		if(is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file= readdir($dh)) !== false)
				{
					if((is_dir($dir."/".$file)) && $file!="." && $file!="..")
					{
						$this->lists($dir."/".$file."/");
					}else{
						if($file!="." && $file!="..")
						{
							$expname=substr($file,strrpos($file,"."),strlen($file));
							if(!$this->rep || in_array($expname,$this->rep))
							{
								//echo $dir."/".$file."<br>";
								$this->list[]=$dir."/".$file;
							}
						}
					}
				}
				closedir($dh);
			}
		}
	}
}

//开始运行
$data=new dirlist();
$data->rep=array(".php");
$data->lists(D_R);
$dirlist=$data->list;

foreach($dirlist as $value)
{
	$str = file_get_contents($value);
	if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf))
	{ 
		//$str = substr($str, 3); 
		//file_put_contents($value, $str); 
		echo $value."<br>";
	} 
}
?>