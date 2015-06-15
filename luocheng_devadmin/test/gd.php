<?php 
require("../kernel/base/lib/public.php");

/*
*文件:image_get_point.php
*功能:获取图片指定某点的颜色值
*作者:鱼尾唯一
*完成:2007/09/21
*补充:参照人民邮电出版社<<PHP网络编程技术与实例>>一书
*外话:今天又是星期五了真爽啊!
*/
/*
function dec2hex($dec)
{
	return strtoupper($dec>15?dechex($dec):('0'.dechex($dec)));
}

$im = imagecreatefrompng('a.png');
$srcW = @ImageSX( $im );
$srcH = @ImageSY( $im );

$arr=array();
for($i=0;$i<$srcW;$i++)
{
	for($j=0;$j<$srcH;$j++)
	{
		$rgb = imagecolorat($im,$i,$j);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		$RGB = dec2hex($r).dec2hex($g).dec2hex($b);
		if($RGB=="FFFFFF")
		{
			$arr[]=$i.":".$j;
//			echo "dec:$r-$g-$b<br />hex:#$RGB"."<br>";
		}
	}
}

foreach($arr as $value)
{
	dbarr("INSERT INTO `base_technology_test` (`xy`)VALUES ('".$value."')");
}
*/
print_r(x("readonly"));
?>