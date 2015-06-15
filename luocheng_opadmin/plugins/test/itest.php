<?php 
namespace plugins\test;
use controllers\module as MODULE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;
set_time_limit(0);

class itest extends module
{
	function detect_task()
	{
		return $this->checkRepeat();
	}
	
	function checkRepeat()
	{
		$this->phpmongo->selecttable("user_goods");
		$user_goods=$this->phpmongo->table->find();
		foreach($user_goods as $value)
		{
			$this->phpmongo->selecttable("user_goods_1");
			unset($value["_id"]);
			$tmp=$this->phpmongo->table->findone(array("goods_user_id"=>$value["goods_user_id"]));
			if($tmp){
				$value["goods_user_id"]=$this->phpmongo->max_id("user_goods");
			}
			$this->phpmongo->table->insert($value);
		}
	}
	
	function check_user_hero()
	{
		$this->phpmongo->selecttable("user_hero");
		$user_goods=$this->phpmongo->table->find();
		foreach($user_goods as $value)
		{
			$this->phpmongo->selecttable("user_hero_1");
			unset($value["_id"]);
			$tmp=$this->phpmongo->table->findone(array("id"=>$value["id"]));
			if(!$tmp){
				$this->phpmongo->table->insert($value);
			}
			
		}
	}
	
	function update_user_info()
	{
		$this->phpmongo->selecttable("user_info");
		$user_info=$this->phpmongo->table->find(array("league_id"=>array('$gt'=>0)),array("user_id"=>1));
		foreach($user_info as $key=>$value)
		{
			echo intval($value["user_id"]);
			echo "<br>";
			$user_goods=$this->phpmongo->table->update(array("user_id"=>intval($value["user_id"])),array('$set'=>array("league_id"=>0)));
		}
	}
	
	function update_league()
	{
		$league_user=$this->phpmongo->find("league_user");	
		foreach($league_user as $key=>$value)
		{
				$user_hero=$this->phpmongo->findone("user_hero",array("user_id"=>intval($value["user_id"]),"main"=>1),array("base_hero_id"=>1));
				$this->phpmongo->update("league_user",array("base_hero_id"=>intval($user_hero["base_hero_id"])),array("id"=>intval($value["id"])));
		}
		
		$personals=$this->phpmongo->find("personals");
		foreach($personals as $key=>$value)
		{
				$user_hero=$this->phpmongo->findone("user_hero",array("user_id"=>intval($value["f_user_id"]),"main"=>1),array("base_hero_id"=>1,"name"=>1));
				$this->phpmongo->update("personals",
				array("f_base_hero_id"=>intval($user_hero["base_hero_id"]),
				"f_name"=>$user_hero["name"]),
				array("id"=>intval($value["id"])));
		}
	}
	
	function update_user()
	{
		$user_info=$this->phpmongo->find("user_info",array(),array("user_id"=>1));	
		foreach($user_info as $key=>$value)
		{
			$this->phpmongo->update("user_info",array("ext.operators_activity"=>""),array("user_id"=>intval($value["user_id"])));
		}
		$this->phpmongo->delete("message",array("type"=>7));
	}
	
	function update_iopenid()
	{
		$user_info=$this->phpmongo->find("user_info",array('iopenid'=>array('$ne'=>"")),array("iopenid"=>1,"user_id"=>1));	
		foreach($user_info as $key=>$value)
		{
			if(!is_numeric($value["iopenid"]))
			{
				$user_base=$this->phpmongo->findone("user_base",array('user_name'=>$value["iopenid"]),array("id"=>1));
				//echo $value["iopenid"]."-".$user_base["id"]."<br>";
				$this->phpmongo->update("user_info",array("iopenid"=>intval($user_base["id"])),array("user_id"=>intval($value["user_id"])));
			}
		}
	}
	
	
	function update_pay_logw()
	{
		$user_info=$this->phpmongo->find("log_pay",array(),array("ts"=>1,"billno"=>1,"openid"=>1));	
		foreach($user_info as $key=>$value)
		{
			//$user_base=$this->phpmongo->findone("user_base",array('user_name'=>$value["openid"]),array("id"=>1));
			//$user_hero=$this->phpmongo->findone("user_hero",array('user_id'=>$user_base["id"],"main"=>1),array("lv"=>1));
			//echo date("W",$value["ts"])."<br>";
			//echo $user_hero["lv"]."<br>";
			//$this->phpmongo->update("log_pay",array("lv"=>intval($user_hero["lv"])),array("billno"=>$value["billno"],"openid"=>$value["openid"]));
			
			//$this->phpmongo->update("log_pay",array("w"=>intval(date("W",$value["ts"])),"e"=>1),array("billno"=>$value["billno"],"openid"=>$value["openid"]));
			$this->phpmongo->update("log_pay",array("timecount"=>intval(date("Ymd",$value["ts"]))),array("billno"=>$value["billno"],"openid"=>$value["openid"]));
			
		}
	}
	
	function  user_id($user_id)
	{
        $mkey="s_1_1";
        $dbname=split("_",$mkey);
        $newid=intval($dbname[2])*100000-100000;
		if($user_id==0){
			return 0;
		}else{
        	return $newid+$user_id;
		}
	}
	
	function updateuser_id()
	{
		$serverid=11;
		$this->updateuser_id1($serverid);
		$this->updateuser_id2($serverid);
	}
	
	function updateuser_id1($serverid)
	{
		$max=$serverid*100000-100000;
		
		$listtab=array(
			'equip'=>array("user_id"=>1),
			'c_gag'=>array("user_id"=>1),
			'league'=>array("user_id"=>1),  
			'league_user'=>array("user_id=>"=>1),
			'message'=>array("send_id"=>1),
			'personals'=>array("f_user_id"=>1),
			'user_goods'=>array("user_id"=>1),
			'user_hero'=>array("user_id"=>1),
			'user_info'=>array("user_id"=>1),
			'user_task'=>array("user_id"=>1)
		);
		
		$listtabmax=array(
			'equip'=>array("user_id"=>$max),
			'c_gag'=>array("user_id"=>$max),
			'league'=>array("user_id"=>$max),  
			'league_user'=>array("user_id=>"=>$max),
			'message'=>array("send_id"=>$max),
			'personals'=>array("f_user_id"=>$max),
			'user_goods'=>array("user_id"=>$max),
			'user_hero'=>array("user_id"=>$max),
			'user_info'=>array("user_id"=>$max),
			'user_task'=>array("user_id"=>$max)
		);
		
		$listtabquery=array(
			'equip'=>array("user_id"=>array('$lt'=>$max)),
			'c_gag'=>array("user_id"=>array('$lt'=>$max)),
			'league'=>array("user_id"=>array('$lt'=>$max)),  
			'league_user'=>array("user_id=>"=>array('$lt'=>$max)),
			'message'=>array("send_id"=>array('$lt'=>$max)),
			'personals'=>array("f_user_id"=>array('$lt'=>$max)),
			'user_goods'=>array("user_id"=>array('$lt'=>$max)),
			'user_hero'=>array("user_id"=>array('$lt'=>$max)),
			'user_info'=>array("user_id"=>array('$lt'=>$max)),
			'user_task'=>array("user_id"=>array('$lt'=>$max))
		);
		
		foreach($listtab as $key=>$value)
		{
			$this->phpmongo->update($key,$listtabmax[$key],$listtabquery[$key],'$inc');
		}
	}
	
	function updateuser_id2($serverid)
	{
		$max=$serverid*100000-100000;
		
		$listtab=array(
			'league'=>array("g_user_id"=>1),  
			'message'=>array("get_id"=>1),
			'personals'=>array("user_id"=>1),
		);
		
		$listtabmax=array(
			
			'league'=>array("g_user_id"=>$max),  
			'message'=>array("get_id"=>$max),
			'personals'=>array("user_id"=>$max),
		);
		
		$listtabquery=array(

			'league'=>array("g_user_id"=>array('$lt'=>$max)),  
			'message'=>array("get_id"=>array('$lt'=>$max)),
			'personals'=>array("user_id"=>array('$lt'=>$max)),
		);
		
		foreach($listtab as $key=>$value)
		{
			$this->phpmongo->update($key,$listtabmax[$key],$listtabquery[$key],'$inc');
		}
	}
	
	function uplongmai()
	{
		$user_info=$this->phpmongo->find("user_info",array(),array("ext.open_module"=>1,"user_id"=>1));	
		foreach($user_info as $key=>$value)
		{
			$open_module=arrayed($value["ext"]["open_module"]);
			if(in_array(32,$open_module)==false)
			{
				$open_module[32]=array(0,1);
				$_open_module=stred($open_module);
				$this->phpmongo->update("user_info",array("ext.open_module"=>$_open_module),array("user_id"=>intval($value["user_id"])));
			}
		}
	}
	
	
	function upgoods()
	{
		$base_goods_ext=dbarr("SELECT * FROM `base_goods_ext` WHERE 1");
		$newgoods=array();
		foreach($base_goods_ext as $value)
		{
			$value["property"]=arrayed($value["property"]);
			$newgoods[$value["goods_ext_id"]]=$value;
		}
		
		$user_goods=$this->phpmongo->find("user_goods",array("goods_type"=>1),array("goods_user_id"=>1,"property"=>1,"goods_ext_id"=>1));	
		foreach($user_goods as $key=>$value)
		{
			$property=arrayed($value["property"]);
			if(isset($property["bz"])){
				$bz=$property["bz"][1];
				$bzarr=explode("@",$bz);
				
				foreach($bzarr as $k=>$v)
				{
					if(isset($property[$v]) and isset($newgoods[$value["goods_ext_id"]]["property"][$v]))
					{
						if(intval($property[$v][0])==1)
						{
							$property[$v][1]=floatval($newgoods[$value["goods_ext_id"]]["property"][$v][1])+floatval($newgoods[$value["goods_ext_id"]]["property"]["dr"][1])*intval($property["by"][1]);
						}else{
							$property[$v][1]=intval($newgoods[$value["goods_ext_id"]]["property"][$v][1])+intval($newgoods[$value["goods_ext_id"]]["property"]["dr"][1])*intval($property["by"][1]);
						}
					}
					
					$_property=stred($property);
					$this->phpmongo->update("user_goods",array("property"=>$_property),array("goods_user_id"=>intval($value["goods_user_id"])));
				}
			}
		}
	}
	
	function uphotel()
	{
		$user_info=$this->phpmongo->find("user_info",array(),array("user_id"=>1));
		foreach($user_info as $key=>$value)
		{
			$_up_hotel_time=stred(array(1=>array(0,0),2=>array(0,0),3=>array(0,0)));
			$this->phpmongo->update("user_info",array("ext.up_hotel_time"=>$_up_hotel_time),array("user_id"=>intval($value["user_id"])));
		}
	}
	
	function upuser_info_force()
	{
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>intval($value["user_id"])),array("id"=>1,"fighting_force"=>1));
		$tmphero=array();
		foreach($user_hero as $hk=>$hk)
		{
			$tmphero[$hk["id"]]=$hk["fighting_force"];	
		}
		
		$user_info=$this->phpmongo->find("user_info",array(),array("ext.formation"=>1,"ext.formation_id"=>1,"user_id"=>1));
		foreach($user_info as $key=>$value)
		{
			if(isset($value["ext"]) and isset($value["ext"]["formation"]) and isset($value["ext"]["formation_id"]))
			{
				$formation=json_decode($value["ext"]["formation"], true);
				$formation_id=$value["ext"]["formation_id"];
				if(intval($formation_id)>0){
					$form=$formation[$formation_id];
					$force=0;
					foreach($form as $k=>$v)
					{
						if($v[1]>0){
							$v[2]=isset($tmphero[$v[1]])?$tmphero[$v[1]]:0;
						}else{
							$v[2]=0;
						}
						$v[0]=intval($v[0]);
						$v[1]=intval($v[1]);
						
						$force+=intval($v[2]);
						$form[$k]=$v;
					}
					$formation[$formation_id]=$form;
					$upformation=json_encode($formation);
					$this->phpmongo->update("user_info",array("force"=>$force,"formation"=>$upformation),array("user_id"=>intval($value["user_id"])));
				}
			}
		}
	}
	
	function imtoserver()
	{
		$this->phpmongo->update("user_info",array("old_server"=>0),array('user_id'=>array('$gt'=>0)));
		$serverid=intval($_SESSION["server_id"]);
		$user_base=$this->phpmongo->find("user_base");	
		$user_list=array();
		foreach($user_base as $value)
		{
			$user_list[]=array($value["user_name"],intval($value["id"]));	
		}
		
		
		$tmp=dbarr("SELECT * FROM  `server_list` WHERE  `operators_id` =".intval($_SESSION["userid"])." and server_id=".intval($_SESSION["server_id"])." ORDER BY  `server_id` DESC LIMIT 1",0,"operators");
		//,`mongo_auth`
		$tmp_mongo=new TMP_MONGO;
        $tmp_mongo->tmpmongoconn($tmp[0]["server_mongo_ip"],$tmp[0]["server_mongo_port"],"all_user",$tmp[0]["mongo_username"],$tmp[0]["mongo_password"],$tmp[0]["mongo_line"],$tmp[0]["mongo_auth"]);
		
		foreach($user_list as $value)
		{
			$user_list=$tmp_mongo->findone("user_list",array("user_name"=>$value[0]));
			if($user_list)
			{
				$this->phpmongo->update("user_info",array("old_server"=>intval($user_list["server_id"])),array("base_user_id"=>$value[1]));	
			}else{
				$tmp_mongo->insert("user_list",array("user_name"=>$value[0],"server_id"=>$serverid));
			}
		}
	} 
	
	
	function delmessage()
	{
		$this->phpmongo->delete("message",array("type"=>5));	
	}
	
	function delgoods()
	{
		$this->phpmongo->delete("user_goods",array("goods_ext_id"=>0));	
	}
	
	function upwto()
	{
		$two_login=$this->phpmongo->find("log_two_login");
		foreach($two_login as $value)
		{
			$this->phpmongo->update("log_two_login",array("month"=>intval(date("Ym",$value["time"])),"year"=>intval(date("Y",$value["time"]))),array("time"=>$value["time"]));	
		}	
	}
	
	function upgoods_gem()
	{
		$user_goods=$this->phpmongo->find("user_goods",array("goods_type"=>1,"gem_ids"=>""),array("base_gem_ids"=>1,"gem_ids"=>1,"goods_user_id"=>1));
		foreach($user_goods as $value)
		{
			print_r($value);
			$this->phpmongo->update("user_goods",array("gem_ids"=>$value["base_gem_ids"]),array("goods_user_id"=>intval($value["goods_user_id"])));	
		}	
	}
	
	function first_recharge()
	{
		$user_info=$this->phpmongo->find("user_info",array("ext.first_charge"=>1),array("user_id"=>1));
		foreach($user_info as $value)
		{
			$this->phpmongo->update("user_info",array("first_recharge"=>1),array("user_id"=>intval($value["user_id"])));	
		}	
	}
	
	
	function user_ontime()
	{
		$user_info=$this->phpmongo->find("user_info");
		foreach($user_info as $value)
		{
			if(isset($value["ontime"])==false or $value["ontime"]==0)
			{
				$this->phpmongo->update("user_info",array("ontime"=>intval($value["createtime"])),array("user_id"=>intval($value["user_id"])));	
				
			}
			
		}	
	}
	
	function hero_team()
	{
		$user_info=$this->phpmongo->find("user_info");
		foreach($user_info as $value)
		{
			
			if(isset($value["ext"]["open_module"]))
			{
				$open_module=arrayed($value["ext"]["open_module"]);
				$open_module["105"]=array(0,1);
				$this->phpmongo->update("user_info",array("ext.open_module"=>stred($open_module)),array("user_id"=>intval($value["user_id"])));	
				
			}
			
		}	
	}
	
	function today_diamond_time()
	{
		$this->phpmongo->update("user_info",array("today_diamond_time"=>20140826),array("today_diamond_time"=>20140825));	
	}
}
?>