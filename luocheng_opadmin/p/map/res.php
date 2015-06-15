<?php
require("../../kernel/base/lib/public.php");

$tab=novice($_GET["map_id"],1);

if($_GET["e"]=="word")
{
	$base_map_word=dbarr("SELECT * FROM `base_map_word` WHERE `status`=1 order by id desc");
	echo json_encode($base_map_word);
}

if($_GET["e"]=="datamap")
{
	$tab=novice($_GET["map_id"],1);
	$newbres=array();
	$base_bres=dbarr("SELECT * FROM `base_bres` WHERE 1");
	foreach($base_bres as $key=>$value)
	{
		$newbres[$value["id"]]=$value;
	}
	
	$base_map_data=array();
	$mongo_a=$_GET["mongo"];
	if($mongo_a==1)
	{
		$tmp=restoarray($mongo->mongo->find($tab,array("map_id"=>intval($_GET["map_id"]))));
		if($tmp)
		{
			foreach($tmp as $value)
			{
				$base_map_data[]=$value;
			}
		}
	}else{
		$base_map_data=dbarr("SELECT * FROM base_".$tab." WHERE `map_id`=".intval($_GET["map_id"]));
		foreach($base_map_data as $key=>$value)
		{
			$base_map_data[$key]["bres_type"]=$newbres[$value["bres_id"]]["type"];
			foreach($value as $k=>$v)
			{
				if(is_numeric($k))
				{
					unset($base_map_data[$key][$k]);
				}
			}
		}
	}
	
	foreach($base_map_data as $key=>$value)
	{
		$aa=getimagesize("./bres/png/".$value["bres_id"].".png");
		$base_map_data[$key]["width"]=$aa[0];
		$base_map_data[$key]["height"]=$aa[1];
	}
//	print_r($base_map_data);
	echo json_encode($base_map_data);
}

//取得npc列表
if($_GET["e"]=="base_npc")
{
	$base_npc=dbarr("SELECT id,name,slg_type FROM `base_npc` WHERE 1 order by id asc");
	echo json_encode($base_npc);
}

//取得资源列表
if($_GET["e"]=="bres")
{
	$base_bres=dbarr("SELECT * FROM `base_bres` WHERE 1 order by id asc");
	echo json_encode($base_bres);
}

//添加城市
if($_GET["e"]=="ccity")
{
	$city_name=city_name_generate($_GET["map_id"]);
	$info=creation_city($_GET["map_id"],$_GET["base_npc"],$city_name,$_GET["grid"]);
	echo json_encode($info);
}

//添加资源
if($_GET["e"]=="addbres")
{
	$bres_id=$_GET["bres_id"];
	$gezi_str=$_GET["gezi"];
	$map_id=$_GET["map_id"];
	
	
	$gezi=explode(",", $gezi_str);
	
	$type=0;
	$dbarrs=dbarr("SELECT barrier FROM `base_bres` WHERE `id`=".$bres_id);
	if($dbarrs[0]["barrier"]==0){$type=0;}else{$type=1;}
	$gtox=gtox($gezi[0]);
	$field=array("map_id", "xy","x","y", "bres_id","z_grid", "grid","type","user_id");
	$value=array($map_id,$gtox["xy"],$gtox["x"],$gtox["y"],$bres_id,$gezi[0],$gezi_str,$type,0);
	dbinsert("base_".$tab, $field, $value);
}

//注意建筑物是不允许被编辑的
if($_GET["e"]=="editbres")
{
	$data_map_id=$_GET["data_map_id"];
	$bres_id=$_GET["bres_id"];
	$gezi_str=$_GET["gezi"];
	$map_id=$_GET["map_id"];
	
	$gezi=explode(",", $gezi_str);

	$type=0;
	$dbarrs=dbarr("SELECT barrier,type FROM `base_bres` WHERE `id`=".$bres_id);
	if($dbarrs[0]["barrier"]==0){$type=0;}else{$type=1;}
	
	$gtox=gtox($gezi[0]);
	$field=array("map_id", "xy","x","y", "bres_id","z_grid", "grid","type");
	$value=array($map_id,$gtox["xy"],$gtox["x"],$gtox["y"],$bres_id,$gezi[0],$gezi_str,$type);
	
	dbupdate("base_".$tab, $field, $value, "id=".$data_map_id);
}

if($_GET["e"]=="delbres")
{
	//如果是城市则要特殊处理.在城市表也要删除
	$data_map_id=$_GET["data_map_id"];
	$dbarrs=dbarr("SELECT city_id FROM base_".$tab." WHERE `id`=".$data_map_id);
	
	sql("delete from  base_".$tab." where id=$data_map_id");
	
	if($dbarrs && $dbarrs[0]["city_id"])
	{
		sql("delete from base_map_city where id=".$dbarrs[0]["city_id"]);
	}
}

/**
 * 创建NPC城市
 * @param int $map_id 区域地图ID
 * @param int $npc_id NPCID
 * @param string $city_name 城市名称
 * @param int $grid 建筑所占用的格子
 * @return arr
 */
function creation_city($map_id,$npc_id,$city_name,$grid)
{
	$tba=novice($map_id,1);
	//判断这个地块是否允许建城 只有空地才可以建城,也就是地图表里没有的数据就可以
	if(grid_occupation($map_id,$grid))
	{
		return array("res"=>false,"msg"=>1462);//格子已经被占用
	}
	
	//判断格子周围方圆4格子没有其他建筑才可以建城,如果成功则返回周围6个格子
	$rgrid=range_grid($map_id,$grid,60);
	if(!$rgrid)
	{
		return array("res"=>false,"msg"=>1463);//没有足够大的区域建城池
	}
	
	$base_npc=dbarr("SELECT hero FROM `base_npc` WHERE `id`=$npc_id");

	$field=array('map_id','npc_id','page','uptime',"name","flag","hero");
	$value=array(intval($map_id),intval($npc_id),intval($page),time(),$city_name,getonestr($city_name),$base_npc[0]["hero"]);
	dbinsert("base_map_city", $field, $value);
	$city_id=dbarr("SELECT id FROM base_map_city WHERE 1 order by id desc limit 1");
	$city_id=$city_id[0]["id"];
	//将周围6个格子表示城本用户，本城市所有
	$rgrid_arr=explode(",", $rgrid);
	foreach($rgrid_arr as $key=>$value)
	{
		$is_g=dbarr("SELECT id FROM base_".$tba." WHERE map_id=$map_id and z_grid='".$value."'");
		if($is_g)
		{
			$field=array("subsidiary_city_id");
			$value=array(intval($city_id));
			//将扩展到手的格子置为己有
			dbupdate("base_".$tba, $field, $value, "id=".$is_g[0]["id"]);
		}
	}
	
	//根据格子确定坐标
	$xyarr=gtox($grid);
	
	//想数据地图中加入一个记录
	$field=array("type",'map_id','page','user_id','city_id','xy','x','y','bres_id','subsidiary_city_id','z_grid','grid');
	$value=array(1,$map_id,$page,-2,intval($city_id),$xyarr["xy"],$xyarr["x"],$xyarr["y"],2,0,$grid,$grid.",".$rgrid);
	
	if(dbinsert("base_".$tba, $field, $value))
	{
		return array("res"=>true,"msg"=>$grid.",".$rgrid,"bres_id"=>2);
	}else{
		return array("res"=>false,"msg"=>1464);//数据插入地图表出问题
	}
}

/**
 * 格子是否占用
 * @param int $map_id 区域地图ID	
 * @param string $grid 格子
 * @return  bool
 */
function grid_occupation($map_id,$grid)
{
	$tba=novice($map_id,1);

	$data=dbarr("SELECT grid FROM base_".$tba." WHERE `map_id`=$map_id");
	if($data)
	{
		foreach($data as $value)
		{
			$grid_arr=explode(",", $value["grid"]);
			if(in_array($grid,$grid_arr))
			{
				return true;
			}
		}
	}
	return false;
}

/**
 * 将下一格的序列配合原始坐标得出新的格子号
 *
 * @param integer $user_city_grid_data 本城市的原始格子
 * @param integer $number_grid 用户下一个扩展的格子
 * @return string
 */
function number_to_grid($user_city_grid_data,$number_grid)
{
	$act=0;
	$tmpdata=explode(":",$user_city_grid_data);
	$act=$tmpdata[1]%2;
	
	include("./s.php");
	return $res;
}
    
    
/**
 * 格子周围范围
 *
 * @param integer $map_id 区域ID
 * @param integer $center_grid 格子
 * @param integer $number_grid 周围格子数
 * @param integer $e 0正常 1强制返回数据
 * @return string
 */
function range_grid($map_id,$center_grid,$number_grid=6,$e=0)
{
	$run6=$run="";
	for($i=1;$i<=$number_grid;$i++)
	{
		$g=number_to_grid($center_grid,$i);
	
		//判定本各自是否被占用（建城池或系统占用）
		$sys=sys_grid_occupation($map_id,$g);
		//0可以使用 1可以使用，但坐标不使用，2完全不可以使用
		if($sys==0)
		{
			if($i<7)
			{
				$run6.=$g.",";
			}
//    			$run.=$g.",";
		}elseif($sys==2){//旁边有人建城
			if($e)
			{
				return substr($run6,0,-1);
			}else{
				return false;
			}
		}
	}
	
	if($run6)
	{
		return substr($run6,0,-1);
	}else{
		return false;
	}
}

/**
 * 格子是否被建城(允许系统占用)
 * @param obj $res 资源类
 * @param int $map_id 区域地图ID	
 * @param string $grid 格子
 * @return  int 0 1 2  0可以使用 1可以使用，但坐标不使用，2完全不可以使用
 */
function sys_grid_occupation($map_id,$grid)
{
	$tba=novice($map_id,1);
	
	require(D_R."/res/data/base_bres.php");
	
	$r=0;//0可以使用 1可以使用，但坐标不使用，2完全不可以使用

	//这个语句最多只会找到一条
	$sql="SELECT * FROM base_".$tba." WHERE `map_id`=$map_id  and z_grid='".$grid."'";
	$data=dbarr($sql);
	
	if($data)
	{
		foreach($data as $value)
		{
			if($value["type"]==1)
			{//已经被占用 进一步排除掉系统占用
				if($base_bres[$value["bres_id"]]["barrier"]==1)
				{//被城池占用
					return 2;
				}
			}else if($value["type"]==2){
				$r=1;
			}
		}
	}
	return $r;
}
    
?>