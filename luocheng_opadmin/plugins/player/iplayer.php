<?php 
namespace plugins\player;
use controllers\module as MODULE;
use kernel\base\base_class\mongopage as MONGOPAGE;

class iplayer extends module
{
//*************************************人名币*****************************************//
	//人民币玩家
	function play_rmb_lv()
	{
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$user_info=$mongopage->mongodb($this->phpmongo,"user_info",array("money"=>array('$gt'=>0)),array("user_id"=>-1),array("user_id"=>1,"createtime"=>1,"money"=>1));
		$mypages=$mongopage->p(true);
		$user_list=array();
		$user_id=array();
		foreach($user_info as $key=>$value)
		{
			$user_list[$value["user_id"]]=$value;
			$user_id[]=$value["user_id"];
		}

		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$user_id),"main"=>1),array("lv"=>1,"user_id"=>1,"name"=>1));
		$showdata=array();
		foreach($user_hero as $value)
		{
			$showdata[]=array(
				"money"=>$user_list[$value["user_id"]]["money"],
				"createtime"=>$user_list[$value["user_id"]]["createtime"],
				"user_id"=>$value["user_id"],
				"name"=>$value["name"],
				"lv"=>$value["lv"]
			);
		}
		return array("page"=>$mypages,"content"=>$showdata);
	}
 //**********************************活跃玩家(经常登陆)**************************************//

	function play_ac_lv($type)
	{	
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		$time=time();
		$time=array(
			1=>array('$where'=>'function(){return obj.login_sum/(('.intval($time).'-obj.createtime)/86400)>=1;}'),
			2=>array('$where'=>'function(){return obj.login_sum/(('.$time.'-obj.createtime)/(86400*7))>=1; }'),
			3=>array('$where'=>'function(){return ('.$time.'-obj.downtime>86400*7 && '.$time.'-obj.downtime<86400*30);}'),
			4=>array('$where'=>'function(){return ('.$time.'-obj.downtime>86400*30 && '.$time.'-obj.downtime<86400*30*3);}'),
			5=>array('$where'=>'function(){return ('.$time.'-obj.downtime>86400*30*3);}')
		);
		
		
		//( 下线时间-注册时间/86400)/登陆次数>=2 就是活跃用户
		$user_info=$mongopage->mongodb($this->phpmongo,"user_info",$time[$type],array("user_id"=>-1),array("user_id"=>1,"downtime"=>1,"createtime"=>1,"login_sum"=>1));

		$mypages=$mongopage->p(true);
		$user_list=array();
		$user_id=array();
		foreach($user_info as $key=>$value)
		{
			$user_list[$value["user_id"]]=$value;
			$user_id[]=$value["user_id"];
		}
		
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$user_id),"main"=>1),array("lv"=>1,"user_id"=>1,"name"=>1));
		$showdata=array();
		
		foreach($user_hero as $value)
		{
			$showdata[]=array(
				"createtime"=>$user_list[$value["user_id"]]["createtime"],
				"downtime"=>$user_list[$value["user_id"]]["downtime"],
				"user_id"=>$value["user_id"],
				"name"=>$value["name"],
				"lv"=>$value["lv"],
				"login_sum"=>$user_list[$value["user_id"]]["login_sum"],
			);
		}
		return array("page"=>$mypages,"content"=>$showdata);
	}		   
}
	