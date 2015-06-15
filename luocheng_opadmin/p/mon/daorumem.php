<?php
require("../../kernel/base/lib/public.php");
function tomem($mem,$map_id)
{
	require(D_R."res/data/base_monster_list.php");
	
	$list=dbarr("SELECT * FROM `base_map_nexus` where id=".$map_id." ORDER BY `id`  DESC");
	foreach((array)$list as $key=>$value)
	{
			$monster=$value["monster"];
			$mo=explode(",",$monster);
			foreach((array)$mo as $kk=>$vv)
			{
				$monster_arr=explode(":",$vv);
				$aaa=explode("\r\n",$monster_arr[2]);
				if(count($aaa)>1)
				{
					$monster_arr[2]=$aaa[0];
				}
				unset($aaa);
				
				$mxy_arr=explode("@",str_replace(array("\n",'\n',"\r\n",'\r\n'),"",$monster_arr[2]));
				$mxy_arr1=explode("_",$mxy_arr[0]);
				$mxy_arr2=explode("_",$mxy_arr[1]);
				
				$arr[]=array(
							"m_id"=>$monster_arr[0],		
							"x1"=>$mxy_arr1[0],
							"y1"=>$mxy_arr1[1],
							"x2"=>$mxy_arr2[0],
							"y2"=>$mxy_arr2[1],
							"xy1"=>$mxy_arr1[0].":".$mxy_arr1[1],
							"xy2"=>$mxy_arr2[0].":".$mxy_arr2[1],
							"e"=>1,//0死亡 1正常
							"uptime"=>0,//上一次怪物刷新时间
							"time"=>$base_monster_list[$monster_arr[0]]["time"],//刷新时间
				);
			}
	}
	$mem->delete("map_$map_id");
	$mem->set("map_".$map_id,$arr);
}

$map_id=$_GET["map_id"];
if(!intval($map_id))
{
	echo "失败";exit;
}
tomem($mem,intval($map_id));
echo "成功";
?>