<?php
session_start();
$uptypes=array('image/jpg',  //上传文件类型列表 
 'image/jpeg', 
 'image/png', 
 'image/pjpeg', 
 'image/gif', 
 'image/bmp', 
 'application/x-shockwave-flash', 
 'image/x-png'); 

$max_file_size=1024*1024*2;   //上传文件大小限制, 单位BYTE 
$destination_folder='../../upimg/'; //上传文件路径 
$watermark=0;   //是否附加水印(1为加水印,其他为不加水印); 
$watertype=2;   //水印类型(1为文字,2为图片) 
$waterposition=1;   //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中); 
$waterstring="newphp.site.cz"; //水印字符串 
$waterimg="http://www.siyto.cn/senyto/images/index_01.gif";  //水印图片
$imgpreview=2;   //是否生成预览图(1为生成,其他为不生成); 
$imgpreviewsize=1/2;  //缩略图比例 

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{ 
if (!is_uploaded_file($_FILES["upfile"][tmp_name])) 
//是否存在文件 
{  
echo "<font color='red'>文件不存在！</font>"; 
exit; 
} 

 $file = $_FILES["upfile"]; 
 if($max_file_size < $file["size"]) 
 //检查文件大小 
 { 
 echo "<font color='red'>文件太大！</font>"; 
 exit; 
  } 

if(!in_array($file["type"], $uptypes)) 
//检查文件类型 
{ 
 echo "<font color='red'>只能上传图像文件或Flash！</font>"; 
 exit;  
} 

if(!file_exists($destination_folder)) 
mkdir($destination_folder); 
 
$filename=$file["tmp_name"]; 
$image_size = getimagesize($filename);  
$pinfo=pathinfo($file["name"]); 
$ftype=$pinfo[extension]; 
$destination = $destination_folder.time().".".$ftype; 
if (file_exists($destination) && $overwrite != true) 
{ 
     echo "<font color='red'>同名文件已经存在了！</a>"; 
     exit; 
  } 
  
 if(!move_uploaded_file ($filename, $destination)) 
 { 
   echo "<font color='red'>移动文件出错！</a>"; 
     exit; 
  } 

$pinfo=pathinfo($destination); 
$fname=$pinfo[basename]; 
echo " <font color=red>已经成功上传</font><br>文件名: <font color=blue>".$destination_folder.$fname."</font><br>"; 
echo " 宽度:".$image_size[0]; 
echo " 长度:".$image_size[1]; 
if($watermark==1) 
{ 
$iinfo=getimagesize($destination,$iinfo); 
$nimage=imagecreatetruecolor($image_size[0],$image_size[1]); 
$white=imagecolorallocate($nimage,255,255,255); 
$black=imagecolorallocate($nimage,0,0,0); 
$red=imagecolorallocate($nimage,255,0,0); 
imagefill($nimage,0,0,$white); 
switch ($iinfo[2]) 
{ 
 case 1: 
 $simage =imagecreatefromgif($destination); 
 break; 
 case 2: 
 $simage =imagecreatefromjpeg($destination); 
 break; 
 case 3: 
 $simage =imagecreatefrompng($destination); 
 break; 
 case 6: 
 $simage =imagecreatefromwbmp($destination); 
 break; 
 default: 
 die("<font color='red'>不能上传此类型文件！</a>"); 
 exit; 
} 
 
imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]); 
imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white); 
 
switch($watertype) 
{ 
 case 1:  //加水印字符串 
 imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black); 
 break; 
 case 2:  //加水印图片 
 $simage1 =imagecreatefromgif($waterimg); 
 imagecopy($nimage,$simage1,0,0,0,0,130,44); 
 imagedestroy($simage1); 
 break; 
}  
 
switch ($iinfo[2]) 
{ 
 case 1: 
 //imagegif($nimage, $destination); 
 imagejpeg($nimage, $destination); 
 break; 
 case 2: 
 imagejpeg($nimage, $destination); 
 break; 
 case 3: 
 imagepng($nimage, $destination); 
 break; 
 case 6: 
 imagewbmp($nimage, $destination); 
 //imagejpeg($nimage, $destination); 
 break; 
} 
 
//覆盖原上传文件 
imagedestroy($nimage); 
imagedestroy($simage); 
} 
} 
$destination=str_replace('../../','',$destination);

$str_up = 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['mulu']."/".$destination;
?>

<script language="javascript">
function face(i){
	str = '';
	switch(i){
		case 1:
		  stradd(1,95);
		break;
		case 2:
		  stradd(100,123);
		break;
		case 3:
		  stradd(200,239);
		break;
	}

}

function stradd(starti,endi){
	window.returnValue = "<?=$str_up;?>";
	window.close();
}
face(1);
</script>