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
	
	function get_base_module_sub()
	{
		$base_module_sub =dbarr("SELECT * FROM `base_module_sub` WHERE 1");
		
		return $base_module_sub;
	}
	
	function goods_track_module($post)
	{

		$nickname=trim($post["nickname"]);
		$time1=strtotime(trim($post["time1"])." 00:00:00");
		$time2=strtotime(trim($post["time2"])." 23:59:59");
		
		
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>trim($nickname) )); 
		$user_id=$user_info["user_id"];
		
		
		$q=array(
			"module_sub_id"=>intval(trim($post["module"])),"user_id"=>intval($user_id),"time"=>array('$gt'=>intval($time1),'$lt'=>intval($time2)),
		);
		
		$goods_act=$this->phpmongo->find("log_goods_act",$q)->sort(array("time"=>1)); 
		return $goods_act;
	}
	
	function goods_track_user($post)
	{
		$openid=trim($post["openid"]);
		$nickname=trim($post["nickname"]);

		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>trim($nickname) )); 
		$user_id=$user_info["user_id"];

		$q=array("user_id"=>intval($user_id));
		$goods_act=$this->phpmongo->find("log_goods_act",$q)->sort(array("time"=>1)); 
		$rundata=array();
		$goods_usre_name=array();
		foreach($goods_act as $value)
		{
			if(isset($rundata[$value["goods_usre_name"]])==false){
				$rundata[$value["goods_usre_name"]]=array("list"=>array(),"theory_count"=>0);
			}
			$rundata[$value["goods_usre_name"]]["list"][]=$value;
			$rundata[$value["goods_usre_name"]]["theory_count"]+=intval($value["e"].$value["max"]);
			$goods_usre_name[]=$value["goods_usre_name"];
		}
		
		$user_goods=$this->phpmongo->find("user_goods",array("user_id"=>intval($user_id),"goods_name"=>array('$in'=>$goods_usre_name)),array("goods_name"=>1,"goods_num"=>1)); 
		$ugn=array();
		foreach($user_goods as $value)
		{
			if(isset($ugn[$value["goods_name"]])==false)$ugn[$value["goods_name"]]=0;
			
			$ugn[$value["goods_name"]]+=$value["goods_num"];
		}
		return array($rundata,$ugn);
	}
	
	
	
	
	function res_track_module($post)
	{
		$nickname=trim($post["nickname"]);
		$time1=strtotime(trim($post["time1"])." 00:00:00");
		$time2=strtotime(trim($post["time2"])." 23:59:59");
		
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>trim($nickname))); 
		$user_id=$user_info["user_id"];
		
		$q=array(
			"module_sub_id"=>intval(trim($post["module"])),"user_id"=>intval($user_id),"time"=>array('$gt'=>intval($time1),'$lt'=>intval($time2)),
		);
		$money_act=$this->phpmongo->find("log_money_act",$q)->sort(array("time"=>1)); 
		return $money_act;
	}
	
	function res_track_user($post)
	{
		////1:'diamond', 2:"gold", 3:"guild_gold",5:"competitive_gold"
		$ress=array(1=>"gold",2=>"copper",3=>"sports",4=>"jade",5=>"exploit",6=>"energy",7=>"spar",8=>"fe",9=>"blue_point",10=>"purple_point",11=>"gold_point",12=>"contribution");
		
		$nickname=trim($post["nickname"]);
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>trim($nickname))); 
		$user_id=$user_info["user_id"];

		$q=array("user_id"=>intval($user_id));
		$goods_act=$this->phpmongo->find("log_money_act",$q)->sort(array("time"=>1)); 
		$rundata=array();
		$money_type=array();
		foreach($goods_act as $value)
		{
			if(isset($rundata[$value["money_type"]])==false){
				$rundata[$value["money_type"]]=array("list"=>array(),"theory_count"=>0);
			}
			$rundata[$value["money_type"]]["list"][]=$value;
			$rundata[$value["money_type"]]["theory_count"]+=intval($value["e"].$value["money"]);
			$money_type[ $value["money_type"] ]=1;
		}
		
		$money_type["init_gold"]=1;
		$money_type["init_copper"]=1;
		$money_type["init_energy"]=1;
//		print_r($money_type);
		$user_info=$this->phpmongo->findone("user_info",array("user_id"=>intval($user_id)),$money_type); 
		return array($rundata,$user_info);
	}
}
?>