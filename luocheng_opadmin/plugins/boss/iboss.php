<?php 
namespace plugins\boss;
use controllers\module as MODULE;
use kernel\base\base_class\string as STRING;
class iboss extends module
{
	function add_boss_list_views($id)
	{
		if($id>0)
		{
			$rs=dbarr("SELECT * FROM `operators` WHERE id=$id",0,"operators");
			return $rs[0];
		}
	}
	
	function boss_list_views()
	{
		$rs=dbarr("SELECT * FROM `operators` WHERE 1 ORDER BY `id` ASC ",0,"operators");
		return $rs;
	}
	function add_boss_list($obj)
	{
		$filed=array("username","name","formation","company","identifier","boss");
		$value=array($obj["username"],$obj["name"],$obj["formation"],$obj["company"],$obj["identifier"],$obj["boss"]);
		
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
	
	function del_boss_list($boss_id)
	{
		sql("DELETE FROM server_list.operators WHERE operators.id=$boss_id");
	}
}
		