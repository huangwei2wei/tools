<?php
namespace plugins\monster;
use controllers\controllers as CONT;

class monster{
	function index($obj)
	{
		
	} 
	
	//添加怪物集合显示
	function add_monster_list_views($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		if(isset($get["id"]) and $get["id"]>0)
		{
			$monster_list_data=$cont->obj->add_monster_list_views($get["id"]);
			$formation=arrayed($monster_list_data["formation"]);
			$monster_id=arrayed($monster_list_data["monster_id"]);
		}
		$checklist=$cont->obj->checklist();
		$hero_views=$checklist["hero"];
		$goods_views=$checklist["goods"];
		$monster_views=$checklist["monster"];
		require_once parse_tmp("/monster/add_monster_list.html");//引入模版
	}
	
	//集合列表显示
	function monster_list_views($obj)
	{
		$obj->_bug=0;
		$juli=array(1=>1,2=>1,3=>1,4=>2,5=>2,6=>2,7=>3,8=>3,9=>3);
		$cont=new CONT("monster");
		$monster_list=$cont->obj->monster_list_views();
		$monster_views=$cont->obj->monster_views();
		
		foreach($monster_list as $key=>$value)
		{
			$formation=arrayed($value["formation"]);
			$monster_id=arrayed($value["monster_id"]);
			
			if($monster_id)
			{
				$monster_list[$key]["monster_id"]=$monster_id;
			}else{
				$monster_list[$key]["monster_id"]=array();	
			}
			
			if(is_array($formation))    //add
           {      
				foreach($formation as $k=>$v)
				{
					if($formation[$k][0]>0)
					{
						if(!isset($monster_id[$v[1]]))
						{
							$formation[$k][0]=-2;
						}
			
						$ao=$cont->obj->ao($v[1],$monster_id[$v[1]][1]);
						
						if($ao and $ao<$juli[$k])
						{
							//错误的攻击距离
							$formation[$k][0]=-1;
						}
						
						if($ao==FALSE)
						{
							$formation[$k][0]=-2;
						}
					}
				}
				$monster_list[$key]["formation"]=stred($formation);
				
			}
		}   //add
		$monster_list_data=$monster_list;
		require_once parse_tmp("/monster/monster_list.html");//引入模版
	}
	
	//删除
	function del_monster_list_views($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		$cont->obj->del_monster_list($get["id"]);
		$this->monster_list_views($obj);
	}
	
	//查询怪物下的等级列表
	function get_monster_lv($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		$monster_lv_data=$cont->obj->get_monster_lv($get["id"]);
		echo json_encode($monster_lv_data);
	}
	
	//添加
	function add_monster_list($obj)
	{
		$obj->_bug=0;
		$post=$obj->post;
		
		$cont=new CONT("monster");
		
		for($i=1;$i<10;$i++)
		{
			if($post["mons".$i]>0){
				$post["monster_id"].=$post["mons".$i].":0:".$post["mons".$i."x"].",";
				$post["formation"].=$i.":1:".$post["mons".$i].",";
			}else{
				$post["formation"].=$i.":0:0".",";
			}
		}
		
		$post["monster_id"]=substr($post["monster_id"],0,-1);
		$post["formation"]=substr($post["formation"],0,-1);
		$cont->obj->add_monster_list($post);
		$this->monster_list_views($obj);
	}
	
	//添加怪物
	function addmons($obj)
	{
		$cont=new CONT("monster");
		$checklist=$cont->obj->checklist();
		$monster_views=$checklist["monster"];
		$ni=$obj->get["ni"];
		require_once parse_tmp("/monster/addm.html");//引入模版
	}
	
	function upformation($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		$run=$cont->obj->upformation($get["query_value"],$get["filed"],$get["data"]);
		if($run)
		{
			echo 1;
		}else{
			echo 0;
		}	
	}
	
	function upformation1($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		$run=$cont->obj->upformation1($get["query_value"],$get["filed"],$get["data"]);
		if($run)
		{
			echo 1;
		}else{
			echo 0;
		}	
	}
	
	function monster_combat($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("monster");
		$run=$cont->obj->monster_combat();
		require_once parse_tmp("/monster/monster_combat.html");//引入模版
	}
}
?>