<?php 
namespace plugins\goods;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
use kernel\base\base_class\page as PAGE;

class igoods extends module
{
	function add_boss_list_views($obj)
	{
			if($id>0)
		{
			$rs=dbarr("SELECT  FROM `operators` WHERE id=$id");
			return $rs[0];
		}
	}
	
	function add_boss_list($obj)
	{
		$filed=array("id","username","name","formation","company","identifier","boss");
		$value=array($obj["id"],$obj["username"],$obj["name"],$obj["formation"],$obj["company"],$obj["identifier"],$obj["boss"]);
		if($obj["edit"]>0)
		{		
			dbupdate("operators", $filed, $value, "id=".$obj["edit"]);
		}else{
			dbinsert("operators",$filed,$value);
		}
	}
	
	
	
	function getdata5($d_id=false,$d_name=false)
	{
		  if($d_id)
			{
		        $goods_ary5=dbarr("SELECT id FROM `operators`WHERE id=$d_id ",0,"operators");
			}elseif($d_name){
		        $goods_ary5=dbarr("SELECT * FROM `operators` WHERE name='".$d_name."'",0,"operators");
			}else{          
				     $goods_ary5=dbarr("SELECT * FROM `operators` WHERE 1",0,"operators");
		    }
		    return $goods_ary5;
	}
	function gamefu($z_name=false,$z_id=false)
	{

		  if($z_name){
		        $goods_ary6=dbarr("SELECT * FROM `server_list` WHERE server_name= '".$z_name."'",0,"operators");
			   }
			  elseif($z_id){ 

				$goods_ary6=dbarr("SELECT * FROM `server_list` WHERE server_id= '".$z_id."'",0,"operators");
			    }
			    else 
			    {
			    	$goods_ary6=dbarr("SELECT * FROM `server_list` WHERE 1",0,"operators");
			    }
		    return $goods_ary6;
		
	}
	//获取数据
	function getdata($g_id=false,$g_name=false)
	{
		if($g_id)
		{
	        $goods_ary =dbarr("SELECT id,goods_name  FROM base_goods WHERE id= $g_id ");
		}elseif($g_name){
	        $goods_ary =dbarr("SELECT id,goods_name FROM base_goods WHERE goods_name= '".$g_name."'");
		}else{          
			$goods_ary =dbarr("SELECT id,goods_name FROM base_goods ");
	    }
	    return $goods_ary;
	}
	
	
	function getdata2($w_id=false,$w_name=false,$w_pr=false)
	{
		if($w_id)
		{
	        $goods_ary2 =dbarr("SELECT id,name,base_property FROM base1_hero WHERE id= $w_id ");
	        
		}elseif($w_pr){
	        $goods_ary2 =dbarr("SELECT id,name,base_property FROM base1_hero WHERE base_property= '".$w_pr."'");
		}elseif($w_name){
	        $goods_ary2 =dbarr("SELECT id,name,base_property FROM base1_hero WHERE name= '".$w_name."'");
		}else{          
			$goods_ary2 =dbarr("SELECT id,name,base_property FROM base1_hero ");
			
	    }
	    return $goods_ary2;
	}
	// 表1 as a  inner join 表2  as b  on 相同值
	function getdata3($m_id=false,$m_lv=false,$m_name=false)
	{
		if($m_id && $m_lv)
		{
			$goods_ary3 =dbarr("select b.id,a.property,a.lv,b.name from base2_monster_lv as a inner join base2_monster as b on a.monster_id=b.id where b.id=$m_id and a.lv=$m_lv");
		}elseif($m_id){
			$goods_ary3 =dbarr("select b.id,a.property,a.lv,b.name from base2_monster_lv as a inner join base2_monster as b on a.monster_id=b.id where b.id=$m_id");
		}elseif($m_name){
	        $goods_ary3 =dbarr("select b.id,a.property,a.lv,b.name from base2_monster_lv as a inner join base2_monster as b on a.monster_id=b.id where b.name= '".$m_name."'");
		}else{          
			$goods_ary3 =dbarr("select b.id,a.property,a.lv,b.name from base2_monster_lv as a inner join base2_monster as b on a.monster_id=b.id where 1");
	    }
	    return $goods_ary3;
	}
	
	function getdata4($c_id=false,$c_name=false)
	{
		if($c_id)
		{
	        $goods_ary4 =dbarr("SELECT id FROM base3_skill_effect  WHERE id= $c_id ");
		}elseif($c_name){
	        $goods_ary4 =dbarr("SELECT id,name FROM base3_skill WHERE name= '".$c_name."'");
		}
		else{          
			$goods_ary4 =dbarr("select a.id,a.result,a.info,b.name from base3_skill_effect as a,base3_skill as b where a.id=b.id");
	    }
	    return $goods_ary4;
	}
	
	
	
	function getextdata($goods_type)
	{
		$goods_ary =dbarr("SELECT a.*,b.goods_type,b.sub_type,b.goods_name FROM `base_goods_ext` a inner join  base_goods b on a.`goods_id`=b.id WHERE b.`goods_type`= $goods_type");
		return $goods_ary;
	}
	
	function base_goods_id($id)
	{
		$goods_ary =dbarr("SELECT * FROM `base_goods` WHERE id=".$id);
		return $goods_ary[0];
	}
	
	function updata($dbname,$filed,$data,$query)
	{
		$keys=key($query);
		sql("UPDATE  `$dbname` SET  `$filed` =  '$data' WHERE  `$keys` =$query[$keys]");
	}
	
	function base_goods_list($get)
	{
		$goods_ary =dbarr("SELECT `goods_name`,`id` as goods_id FROM `base_goods` WHERE `goods_type`=".$get["goods_type"]." and `sub_type`=".$get["sub_type"]."");
		return $goods_ary;
	}
	
	function base_hero_list($get)
	{
		$goods_ary2 =dbarr("SELECT `lv`,`id` FROM `base1_hero` WHERE `id`=".$get["id"]." and `sub_type`=".$get["sub_type"]."");
		return $goods_ary2;
	}
	function base_monster_list($get)
	{
		$goods_ary3 =dbarr("SELECT `lv`,`id` as name FROM `name` WHERE `id`=".$get["id"]." and `sub_type`=".$get["sub_type"]."");
		
		return $goods_ary3;
	}
}
?>