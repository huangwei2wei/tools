<?php
require("../../kernel/base/lib/public.php");
$str=$_GET["str"];
$map_id=intval($_GET["map_id"]);
$str=substr($str,0,-1);

sql("delete from base_map_monster where map_id=".$map_id);
$strarr=explode(",",$str);
foreach($strarr as $value)
{
	$tmps=explode(":",$value);
	$tmpxy=explode("@",$tmps[2]);
	
	$field=array("map_id","monster_id","xy1","xy2");
	
	$xy1=explode("_",$tmpxy[0]);
	$xy1str=($xy1[0]*5).":".($xy1[1]*5);
	if($tmpxy[1])
	{
		$xy2=explode("_",$tmpxy[1]);
		$xy2str=($xy2[0]*5).":".($xy2[1]*5);
	}else{
		$xy2str="";
	}
	
	$value=array($map_id,$tmps[0],$xy1str,$xy2str);
	dbinsert("base_map_monster", $field, $value);
}

echo $str;
?>
