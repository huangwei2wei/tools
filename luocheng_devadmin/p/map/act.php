<?php
require("../../kernel/base/lib/public.php");
//$arr=dbarr("SELECT * FROM `bres` WHERE 1");
//print_r($arr);
$mapdata=$_POST["mapdata"];
$data=explode(";", $mapdata);
$area_id=intval($_POST["area_id"]);
$area_id=!$area_id?1:$area_id;
//base_map_data_novice
if($_POST["map_id"]==16)
{
	$tab="base_map_data_novice";
}else{
	$tab="base_map_data";
}

sql("delete from $tab where map_id=".intval($_POST["map_id"])." and area_id=".$area_id."");

for($i=0;$i<count($data);$i++)
{
	if($data[$i])
	{
		$f=explode("@", $data[$i]);
		if($f[1])
		{
			$user_id=0;
			$type=0;
			$dbarrs=dbarr("SELECT barrier FROM `base_bres` WHERE `id`=".$f[1]);
			if($dbarrs[0]["barrier"]==0){$type=0;}else{$type=1;}
			$f0=explode(",",$f[0]);
			$f2=explode(":",$f[2]);
			
			if($f[1]==2){
				$user_id=-2;
			}elseif($user_id==1){
				$user_id=-1;
			}
			
			sql("INSERT INTO `$tab` (`map_id`, `area_id`, `xy`,`x`,`y`, `bres_id`,`z_grid`, `grid`,`type`,`user_id`) VALUES ('".intval($_POST["map_id"])."', '".$area_id."', '".$f[2]."',$f2[0],$f2[1], '".$f[1]."','".$f0[key($f0)]."', '".$f[0]."','".$type."','".$user_id."')");
		}
	}
}
echo "ok";
?>