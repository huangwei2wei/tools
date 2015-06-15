<?php 
namespace kernel\base\sql\nosql;
use kernel\base\sql\nosql\mongo as MONGO;
use kernel\base\base_class\res as RES;

class advanced
{
	var $mem;
	var $mongo;
	var $res;
	var $error;
	function __construct()
	{
		global $mem;
		$this->mongo=new MONGO;
		$this->mem=$mem;
		$this->res=new RES;
	}

	function __destruct()
	{
		unset($this->mem);
	}
	
	//取得第三方数据
	function get3data($user_id,$key=false)
	{
		$logininfo=$this->mongo->findone('login_user',array("id"=>$user_id));

		if($key)
		{
			return $logininfo[$key];
		}else{
			return $logininfo;
		}
	}
	
	//更新第三方数据
	function upget3data($user_id,$info,$key=false)
	{
		if($key)
		{
			$up=array($key=>$info);
		}else{
			$up=$info;
		}
		return $this->mongo->replace("login_user",$up,array("id"=>$user_id));
	}
	
	//删除第三方数据
	function del3data($user_id)
	{
		$this->mongo->delete('login_user',array("id"=>$user_id));
	}
	
	/**
	 * 获取指定的mem数据表中的所有数据
	 *
	 * @return array
	 */
	function gettab($tab)
	{
		if($tab)
		{
			return  restoarray($this->mongo->find($tab));
		}else{
			return false;	
		}
	}
	
	/*C提供的在先数据*/
	function ctoon()
	{
		$onlinestatistics=restoarray($this->mongo->find("onlinestatistics"));
		if(is_array($onlinestatistics))
		{
			$onlinestatistics_arr=explode("@",$onlinestatistics[0]);
		}else{
			$onlinestatistics_arr=explode("@",$onlinestatistics);
		}
		
		return $onlinestatistics_arr;
	}
	
	/**
	 * 获取在线用户ID列表
	 *
	 * @return array $data 返回在线ID列表
	 */
	function on_list($user_id=false)
	{
		$on_off=false;
		$on_s=array();
		$user_list = restoarray($this->mongo->find("user_info",array("online"=>1)));
		foreach((array)$user_list as $key=>$value)
		{
			$on_s[$key]=$key;
			
			if($user_id and $user_id==$key)
			{
				return true;
			}
		}
		
		if($user_id)
		{
			return $on_off;
		}else{
			return false;
		}
	}

	
	/**
	 * 获取下线用户ID列表
	 *
	 * @return array $data 返回下线ID列表
	 */
	function off_list($user_id=false)
	{
		$on_off=false;
		$off_s=array();
		$user_list = restoarray($this->mongo->find("user_info",array("online"=>0)));
		foreach((array)$user_list as $key=>$value)
		{
			$off_s[$key]=$key;
				
			if($user_id and $user_id==$key)
			{
				$on_off=true;
			}
		}
		
		if($user_id)
		{
			return $on_off;
		}else{
			return $off_s;
		}
	}

	/**
	 * 获取总用户ID列表
	 *
	 * @return array $user_list 返回总用户ID列表  array（[]=>用户ID,.....）;
	 */
	function lists()
	{
		$user_list=restoarray($this->mongo->find("user_info",array(),array("user_id"=>1)));
		return $user_list;
	}
	
	
	/**
	 * 获取用户总信息列表
	 *
	 * @return $uf 获取用户总信息列表
	 */
	function list_info()
	{
		$user_list=restoarray($this->mongo->find("user_info"));
		if($user_list)
		{
			foreach($user_list as $key=>$value)
			{
				$uf[$value["user_id"]]=$value;
			}
		}
		return $uf;
	}

	/**
	 * 获取对应用户信息
	 *
	 * @param integer $user_id 用户ID
	 * @return mixed 返回用户信息
	 */
	function user_info($user_id)
	{
		$user=$this->mongo->findone("user_info",array("user_id"=>intval($user_id)));
		$get3data=$this->get3data($user_id);
		if($get3data)
		{
			if($get3data["xy"] and $get3data["mapid"])
			{
				$user['xy'] = $get3data["xy"];
				$user['in_map'] = $get3data["mapid"];
			}
		}
		return $user;
	}
	
	/**
	 * 更新用户数据
	 *
	 * @param string $info 更新内容
	 * @param integer $user_id 用户ID
	 * @param string $key 更新健名
	 * @return bool
	 */
	function up_user_info($info,$user_id,$key=false)
	{
		if(!$user_id)
		{
			return false;
		}else{
			if($key)
			{ 
                return $this->mongo->update("user_info",array($key=>$info),array("user_id"=>$user_id));       
			}else{
				return $this->mongo->update("user_info",$info,array("user_id"=>$user_id));
			}
		}
	}

	/**
	 * 获取对应用户属性信息
	 *
	 * @param integer $user_id 用户ID
	 * @return array 返回用户属性数组
	 */
	function user_info_attribute($user_id)
	{
		$user_info=$this->mongo->findone("user_info",array("user_id"=>$user_id),array("property"=>1));
		return arrayed($user_info['property']);
	}

	/**
	 * 更新用户属性数据
	 *
	 * @param array $info 更新内容 数组
	 * @param integer $user_id 用户ID
	 * @return bool 
	 */
	function up_user_info_attribute($info,$user_id)
	{
		if(!$info or !$user_id)
		{
			return false;
		}else{
			return $this->mongo->update("user_info",array("property"=>stred($info)),array("user_id"=>$user_id));
		}
	}


	/**
	 * 获取用户真气和气血数据 蓝和红
	 *
	 * @param integer $user_id 用户ID
	 * @return array 
	 */
	function fairyism($user_id)
	{
		//$on_list=$this->on_list($user_id);
		$fon=$this->mongo->findone("user_info",array("user_id"=>$user_id),array("property"=>1));
		$fon=arrayed($fon["property"]);
		return array("af"=>$fon["af"],"ad"=>$fon["ad"]);
	}
	
	/**
	 * 更新真气
	 *
	 * @param arr $info  
	 * @param integer $user_id 用户ID
	 * @return bool
	 */
	function upfairyism($info,$user_id)
	{
		if(!$info or !$user_id)
		{
			return false;
		}else{
			$fon=$this->mongo->findone("user_info",array("user_id"=>$user_id),array("property"=>1));
			$fon=arrayed($fon["property"]);
			$fon["ad"]=$info;
			return $this->mongo->update("user_info",array("property"=>stred($fon)),array("user_id"=>$user_id));
		}
	}
	
	/**
	 * 消耗真气或气血数据
	 *
	 * @param unknown_type $user_id 用户ID
	 * @param unknown_type $sum 消耗数值
	 * @param unknown_type $type 类型 fairyism真气 tymos气血
	 * @param unknown_type $coerce 强制 1当被减数不够时强制为0 0当被减数不够时放弃
	 * @return mixed 剩余真气|false
	 */
	function xfairyism($user_id,$sum,$type='fairyism',$coerce=1)
	{
		$f=$this->fairyism($user_id);
		if($f['act']==0)
		{
			$f[$type]-=$sum;
			if($f[$type]<0)
			{
				if($coerce==1)
				{
					$f[$type]=0;
				}else{
					return false;
				}
			}
		}else{
			//return false;//后期这里要做延时处理
			sleep(0.1);
			return xfairyism($user_id,$sum,$type,$coerce);
		}
		$this->upfairyism($f,$user_id);
		return $f[$type];
	}
	
	/**
	 * 移动真气数据组 e==on表示移到上线组 off表示移到下线组
	 *
	 * @param integer $user_id 用户ID
	 * @param string $e on|off
	 * @return bool
	 */
	function fairyism_on_off($user_id,$e="on")
	{
		if($e=='on')
		{
			$foff=$this->user_info("user_info",array("id"=>$user_id));
			if(!$foff)
			{
				return false;
			}
			
			$update_filed=array(
									"competition"=>$foff['competition']?$foff['competition']:3,
									"ontime"=>time(),
									"online"=>1,
									"team_id"=>0
							);
			
			return $this->mongo->update("user_info",$update_filed,array("id"=>$user_id));
		}elseif($e=='off'){
			$fon=$this->user_info("user_info",array("id"=>$user_id));
			
			if(!$fon)
			{
				return false;
			}
			
			$update_filed=array(
									"competition"=>$foff['competition']?$foff['competition']:3,
									"ontime"=>time(),
									"online"=>0,
									"team_id"=>0
							);
			
			return $this->mongo->update("user_info",$update_filed,array("id"=>$user_id));
		}else{
			return false;
		}
		return true;
	}
	
	/**
	 * 获取用户装备ID数组
	 *
	 * @param unknown_type $user_id 用户ID
	 * @param unknown_type $parts 部位equip1  equip2  equip3 equip4  equip5 equip6 equip7 equip8 equip9 equip10
	 * @param unknown_type $user_hero_id 用户英雄ID
	 * @return mixed 用户装备ID数组或ID
	 */
	function getequip($user_id,$parts=false,$user_hero_id=0)
	{
		if($user_hero_id)
		{
			$getequip=$this->mongo->findone("equip",array("user_id"=>$user_id,"hero_user_id"=>$user_hero_id));
		}else{
			$getequip=$this->mongo->findone("equip",array("user_id"=>$user_id));
		}
		
		foreach((array) $getequip as $key=>$value)
		{
			if($key!='user_id' and  $key!='id' ){
				$e[$key]=$value;
			}
		}

		if(!$parts)
		{
			return $e;
		}else{
			return $e[$parts]?$e[$parts]:false;
		}
	}
	
	/**
	 * 更新装备
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $goods_user_id 物品id
	 * @param string $parts 部位equip1  equip2  equip3 equip4  equip5 equip6 equip7 equip8 equip9 ，talisman
	 * @param integer	$hero_id	英雄ID
	 * @return bool
	 */
	function upequip($user_id,$goods_user_id,$parts=false,$hero_id=0)
	{
		if($hero_id)
		{
			$getequip=$this->mongo->findone("equip",array("user_id"=>$user_id));
		}else{
			$getequip=$this->mongo->findone("equip",array("user_id"=>$user_id,"hero_user_id"=>$hero_id));
		}
		if(!$parts)
		{
			$goods=$this->getgoods($user_id,$goods_user_id);
			if($goods['goods_type']==1 or $goods['goods_type']==2)
			{
				$parts=$goods['sub_type'];
			}else{
				return false;
			}
		}
		
		$getequip[$parts]=$goods_user_id;
		$equipdate=$getequip;
		if($this->mongo->update("equip",$equipdate,array("user_id"=>$user_id)))
		{
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取用户背包数组(不含物品)
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $lattice_id 方格ID
	 * @return array 用户背包数组
	 */
	function getpack($user_id,$lattice_id=false)
	{
		$goods=$this->getgoods($user_id);
		
		if($goods){

			foreach($goods as $key=>$value)
			{
				$getpack[intval($value['lattice_id'])]=$value;
			}
		}
		if($lattice_id)
		{
			return $getpack[$lattice_id];
		}else{
			return $getpack;
		}

	}
	
	/**
	 *获取用户物品数组
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $goods_user_id 用户物品ID
	 * @param integer $key 按照这个key可以取得数组的对应健名的数值
	 * @return array 
     * 
	 */
	function getgoods($user_id,$goods_user_id=0,$key=false)
	{
		$base_quality=$this->res->base_quality();
		$base_condition=$this->res->base_condition();
		$base_goods=$this->res->base_goods();
		$goods=array();
		
		if($goods_user_id)
		{
			$get_user_goods[]=$this->mongo->findone("user_goods", array("goods_user_id"=>$goods_user_id));
		}else{
			$get_user_goods=restoarray($this->mongo->find("user_goods", array("user_id"=>$user_id)));
		}
		
		if(!$get_user_goods){return false;}
		foreach((array)$get_user_goods as $keys =>$value)
		{
			//用户出售价格估算
			$value["sell_price"]=price_estimates($this->res,$value["goods_type"],$value["quality"],$value["copper"]);
			
			//条件处理
			if($value['condition_id']>0)
			{
				$value['condition']=$base_condition[$value['condition_id']]["info"];
			}
			
			$goods[$value['goods_user_id']]=arrunite($base_goods[$value['goods_id']],$value);
		}

		if(!$key)
		{
			if($goods_user_id)
			{
				return $goods[$goods_user_id];
			}else{
				return $goods;
			}
		}else{
			if($goods_user_id)
			{
				$goods[$goods_user_id][$key];
			}else{
				return $goods;
			}
		}
	}
	

	/**
	 *新增一个物品
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $goods_info 物品信息
	 * @return true false
     * 
	 */
	function addgoods($user_id,$goods_info)
	{
//		print_r("====>>>".$goods_info);
		$goods__=array();
		$d=$maximumid=0;
		$goods_num=$goods_info["goods_num"];
		//$goods__=$this->res->base_goods($goods_info["goods_id"]);
		$tmpbase_goods=dbarr("SELECT a.*,b.name,b.wrap_num,b.goods_type,b.sub_type,b.is_used FROM `base_goods_ext` a inner join base_goods b on a.goods_base_id=b.id WHERE a.goods_ext_id=".$goods_info["goods_ext_id"]);
		if(count($tmpbase_goods)==0){
			echo "无此物品！";
			return false;	
		}
		
		$mynumeric=array("goods_type"=> 1,"sub_type"=>6,"quality"=>1,"is_sale"=>1,"pack_show"=>1,"wrap_num"=>1,
			"double_click"=>1,"goods_user_id"=>1,"goods_ext_id"=>1,"goods_num"=>1,"user_id"=>1,"binding"=>1,"goods_state"=>1,"lattice_id"=>1
				,"copper"=>1,"estate"=>1,"time"=>1,"goods_base_id"=>1,"is_used"=>1
		);
		
		foreach($tmpbase_goods[0] as $key=>$value)
		{
			if(!is_numeric($key))
			{
				if(isset($mynumeric[$key]))
				{
					$goods__[$key]=intval($value);
				}else{
					$goods__[$key]=$value;
				}
			}
		}
//		print_r($goods__);
		if(count($goods__)<12)return false;
			
		//$goods__=$tmpbase_goods[0];
		$wrap_num=$goods__["wrap_num"];
		if($wrap_num>1 and $goods_num>$wrap_num)return false;
		if($wrap_num==1 and $goods_num>1)return false;
		
		$newgoods_info=array();
		//unset($goods_info["goods_ext_id"]);
		$goods_info["goods_state"]=0;//重置状态

		$get_user_goods=$this->mongo->findone("user_goods",array("user_id"=>$user_id,"goods_ext_id"=>$goods_info["goods_ext_id"]),array("goods_user_id"=>true,"goods_num"=>true,"lattice_id"=>true,"goods_name"=>true));
		
		//判断物品是否可叠加
//		print_r($get_user_goods);
//		print_r($get_user_goods["goods_num"]."aaaaaaaaaaa".$goods_num."bbbbbbbbbbbbb".$goods__["wrap_num"]."ccccccccc". $goods__["wrap_num"]."dddddddd"  );
		if($get_user_goods["goods_num"]+$goods_num<=$goods__["wrap_num"] && $goods__["wrap_num"]>1 && $get_user_goods)
		{//可叠加 的物品数据一定是相同的，否则不能有叠加属性
			$run=$this->mongo->update("user_goods", array("goods_num"=>$get_user_goods["goods_num"]+$goods_num), array("goods_user_id"=>$get_user_goods["goods_user_id"]));
			$d=1;
			if($run)
			{
				$this->goods_log($user_id,intval($get_user_goods["goods_user_id"]),$goods_info["goods_ext_id"],$goods_num,-1);	
			}
		}else{//不可叠加或者可叠加，但物品表中没有 ,或者叠加已达上限
			//生成物品在用户表中的ID
			$d=0;
			//$maximumid=max_id($this,"user_goods","goods_user_id");
			//取得物品放置的格子
//			$lattice=$this->user_lattice_id($user_id);
//
//			if(!$lattice)
//			{//格子有问题
//				$this->error["error"]=$this->error['err_msg'];
//				return false;
//			}
//			if($get_user_goods["goods_num"]+$goods_num>=$goods__["wrap_num"]){
//				echo "数量超过上限";
//				return false;
//			}
			
			$newgoods_info=$goods__;
			//指定ID
			//$newgoods_info["goods_user_id"]=$maximumid;
			//指定数量
			$newgoods_info["goods_num"]=$goods_num;
			//指定用户
			$newgoods_info["user_id"]=$user_id;
			//绑定情况
			$newgoods_info["binding"]=$newgoods_info["binding"]==2?0:intval($newgoods_info["binding"]);
			//合并数据
			$newgoods_info =arrunite($newgoods_info,$goods_info);
			//为物品指定格子
//			$newgoods_info["lattice_id"]=$lattice[0];
//			$newgoods_info["goods_id"]=intval($newgoods_info["goods_id"]);
			$newgoods_info["goods_ext_id"]=intval($newgoods_info["goods_ext_id"]);
			$newgoods_info["gem_ids"]=$newgoods_info["base_gem_ids"];
			unset($newgoods_info["id"]);
			if($newgoods_info["goods_ext_id"]==0)return false;
			$newgoods_info["property"]=stred(properties($newgoods_info));
//			print_r("物品----->>>".$newgoods_info);
			$run=$this->mongo->insert("user_goods",$newgoods_info);
			if($run)
			{
				$this->goods_log($user_id,intval($run),$newgoods_info["goods_ext_id"],$goods_num,-1);	
			}
		}
		
		if($run)
		{
			if($d==1)
			{//可叠加
				$runs= array(
						"lattice_id"=>$get_user_goods["lattice_id"],
						"goods_user_id"=>$get_user_goods["goods_user_id"],
						"goods_type"=>$goods__["goods_type"],
						"sub_type"=>$goods__["sub_type"],
						"label"=>$goods__["label"]
					);
			}else{
				$runs= array(
						"lattice_id"=>$lattice[0],
						"goods_user_id"=>$maximumid,
						"goods_type"=>$goods__["goods_type"],
						"sub_type"=>$goods__["sub_type"],
						"label"=>$goods__["label"]
					);
			}
			
			unset($newgoods_info);
			unset($get_user_goods);
			
			return $runs;
		}else{
			unset($get_user_goods);
			unset($newgoods_info);
			$this->error["error"]=1262;
			echo "发放失败！";
			return false;
		}
	}
	
	function goods_log($user_id,$goods_user_id,$goods_ext_id,$mmax,$module_sub_id)
	{
		
		$adddata=array(
                  "user_id"=>$user_id,
                  "goods_user_id"=>$goods_user_id,
                  "goods_ext_id"=>$goods_ext_id,
                  "max"=>$mmax,
                  "e"=>"+",
                  "time"=>time(),
                  "timecount"=>intval(date("Ymd",time())),
				  "module_sub_id"=>$module_sub_id
                  );
        
            $this->mongo->insert("log_goods_act",$adddata);
	}
	
	/**
	 * 更新用户物品数组
	 *
	 * @param string $info 要更新的数据
	 * @param integer $user_id 用户ID 
	 * @param integer $goods_user_id 用户物品ID
	 * @param string $key 按照这个key可以取得数组的对应健名的数值
	 * @return bool
	 */
	function upgoods($info,$user_id,$goods_user_id=0,$key=false)
	{
		$get_user_goods=restoarray($this->mongo->find("user_goods",array("user_id"=>$user_id)));
             
		$ea=$get_user_goods?1:0;
		if(!$goods_user_id)
		{
			foreach($info as $key=>$value)
			{
				$this->mongo->update("user_goods",$value,array("goods_user_id"=>$value["goods_user_id"]));
			}
			return true;
		}else{
			if(!$key)
			{
				return $this->mongo->update("user_goods",$info,array("goods_user_id"=>$goods_user_id));
			}else{
				return $this->mongo->update("user_goods",array($key=>$info),array("goods_user_id"=>$goods_user_id));
			}
		}
		return false;
	}
	
	/**
	 * 删除物品
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $goods_user_id 用户物品ID
	 * @param integer $sum 删除数量
	 * @return bool
	 */
	function delgoods($user_id,$goods_user_id,$sum=1)
	{
		$get_user_goods=$this->mongo->findone("user_goods",array("goods_user_id"=>$goods_user_id));
		
		if(!$get_user_goods)
		{
			return false;
		}else{
			if($get_user_goods['goods_num']-$sum<=0)
			{
				return $this->mongo->delete("user_goods", array("goods_user_id"=>$goods_user_id));
			}else{
				$goods_num=$get_user_goods['goods_num']-$sum;
				return $this->mongo->update("user_goods",array("goods_num"=>$goods_num),array("goods_user_id"=>$goods_user_id));
			}

			return false;
		}
	}

	/**
	 * 获取用户社交关系数据
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $relationship 朋友关系， 1好友 2仇敌  1好友是不允许直接PK 仇敌可以  0两个都取
	 * @param integer $state 建交状态 1成功 2申请中  0两个都取
	 * @return arr 
	 */
	function get_personals($user_id,$relationship=0,$state=0)
	{
		$rundata=array();
		$personals=restoarray($this->mongo->find("personals",array("user_id"=>$user_id)));
		
		foreach((array)$personals as $key=>$value)
		{
			if(!$relationship and !$state)
			{
                 $rundata[$value["f_user_id"]]=$value;
			}
			
			if($relationship and $state)
			{
				if($value['relationship'] ==$relationship && $value['state'] ==$state )
				{
					$rundata[$value["f_user_id"]]=$value;
				}                           
			}
			
			if(!$relationship and $state)
			{
				if($value['state']==$state)
				{
					$rundata[$value["f_user_id"]]=$value;
				}
			}		
				
			if($relationship and !$state)
			{
					if($value['relationship'] ==$relationship)
					{
					   $rundata[$value["f_user_id"]]=$value; 
					}
			}
		}
		return $rundata;
	}
	
	/**
	 * 加入社交
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $f_user_id 好友或仇敌ID
	 * @param integer $relationship 朋友关系， 1好友 2仇敌  1好友是不允许直接PK 仇敌可以
	 * @return bool 
	 */
	function add_personals($user_id,$f_user_id,$relationship)
	{
		$people_relations=$this->get_personals($user_id);
		$f_people_relations=$this->get_personals($f_user_id);
		
		foreach((array)$people_relations as $key=>$value)
		{
			if($value["f_user_id"]==$f_user_id)
			{
				return false;
			}
		}
		
		foreach((array)$f_people_relations as $key=>$value)
		{
			if($value["f_user_id"]==$user_id)
			{
				return false;
			}
		}
		
		$a=$people_relations?1:0;
		$b=$f_people_relations?1:0;
		
		$array=array(
			'f_user_id'=>$f_user_id,//对方ID
			'pk'=>0,//PK数
			'addtime'=>time(),//加入时间
			'relationship'=>$relationship,//朋友关系， 1好友 2仇敌  1好友是不允许直接PK 仇敌可以
			'state'=>2,//建交状态 1成功 2申请中
			'act'=>1,//谁操作 1我方操作 2对方操作
		);
		if($relationship==2){$array["state"]=1;}
		$this->insert("personals", $array);
		
		
		$array["f_user_id"]=$user_id;
		$array["act"]=2;
		$this->insert("personals", $array);
		return true;
	}

	/**
	 * 更新社交
	 *
	 * @param string $info 更新内容
	 * @param integer $user_id 用户ID
	 * @param integer $f_user_id 好友或仇敌ID
	 * @param string $keys 更新键值
	 * @return bool
	 */
	function up_personals($info,$user_id,$f_user_id,$keys=false)
	{
		$personals=$this->get_personals($user_id);

		if($personals[$f_user_id])
		{
			if($keys)
			{
				return $this->mongo->update("personals", array($keys=>$info), array("f_user_id"=>$f_user_id));
			}else{
				return $this->mongo->update("personals", $info, array("f_user_id"=>$f_user_id));
			}
		}
		
		return false;
	}
	
	/**
	 * 删除社交
	 * @param integer $user_id 用户ID
	 * @param integer $f_user_id 好友或仇敌ID
	 * @return bool
	 */
	function del_personals($user_id,$f_user_id)
	{
		$this->mongo->delete("personals",array("user_id"=>$user_id,"f_user_id"=>$f_user_id));//删除自己的记录
		$this->mongo->delete("personals",array("user_id"=>$f_user_id,"f_user_id"=>$user_id));//删除别人的记录
		return true;
	}

       
	/**
	 * 取得国家数据
	 *
	 * @param integer $ingroup_id 国家id
	 * @return array
	 */
	function get_ingroup($ingroup_id=0)
	{
		$ingroup=restoarray($this->mongo->find("ingroup"));
		foreach($ingroup as $key=>$value)
		{
			$ningroup[$value["id"]]=$value;
		}
		
		if(!$ingroup_id)
		{
			return $ningroup;
		}else{
			return $ningroup[$ingroup_id];
		}
	}

	/**
	 * 更新国家数据
	 *
	 * @param string $info 要更新的内容
	 * @param integer $ingroup_id 门派id
	 * @param string $key 更新关键字
	 * @return bool
	 */
	function up_ingroup($info,$ingroup_id=0,$key=false)
	{
		if($key)
		{
			if($ingroup_id)
			{
				$this->mongo->update("ingroup", array($key=>$info),array("id"=>$ingroup_id));
			}else{
				$this->phpmongo->update("ingroup", array($key=>$info),array(),true);
			}
		}else{
			if($ingroup_id)
			{
				$this->mongo->update("ingroup",$info,array("id"=>$ingroup_id));
			}else{
				$this->phpmongo->update("ingroup", $info,array(),true);
			}
		}
	}

      
   /*
   * 创建队伍
   * @param integer $user_id 用户id
   * @param string $name 队伍名称
   * @param string $info 队伍介绍
   * @param string $affiche 队伍公告信息
   * @return boolen
   */
	function create_league($user_id,$name,$info,$affiche)
	{
		$league =restoarray($this->mongo->find("league")->sort(array("id"=>-1))->limit(1));
		$league=$league[0];
		//取得ID
		if($league)
		{
			$league_id=$league["id"]+1;
		}else{
			$league_id=1;
		}
		
		$is=$league?1:0;
		$u_info =$this->user_info($user_id);
		$u_arr[$user_id]=array(
					'user_id'=>$user_id, //用户ID	
					'state'=>1, //申请状态( 1队长 2副队长 3干事 4普通成员 9申请中)	
					'time'=>time(),
		);		
		$l_arr=array(
			'id'=>$league_id,
			'user_id'=>$user_id, //队伍创始人用户
			'g_user_id'=>0, //队伍归上忍管理者的id	
			'name'=>$name, //队伍名称	
			'info'=>$info, //队伍简介	
			'affiche'=>$affiche, //队伍公告	
			'num_top'=>100, //队伍人数上限	
			'ingroup'=>$u_info['ingroup'], //队伍属于那个国家的	
			'state'=>1, //队伍状态(1为正常,2为关闭)	
			'time'=>time(), //队伍创
			"user_list"=>$u_arr,//队员列表
		);
		
		$this->insert("league", $l_arr);
	  	
	    $this->up_user_info($league_id,$user_id,'league_id');  //添加到用户表
		return $league_id;//取得队伍ID
	}

	/*
	 * 队伍列表
	 * @return array
	 */
	function league_list($league_id=0)
	{
		$newlea=array();
		$league_data=restoarray($this->mongo->find('league'));
		foreach((array)$league_data as $key=>$value)
		{
			$newlea[$value["id"]]=$value;
		}
		
		if(!$league_id)
		{
			return $newlea;
		}else{
			return $newlea[$league_id];
		}
	}
		
	/*
	 *队伍更新       
	 * @param    $league_id 队伍id
	 * @param    $info      更新内容
	 * @param    $key     更新字段
	 * @return  boolen
	 */
	function up_league($league_id,$info,$key=false)
	{
		$arr=array();
		if($key)
		{
			$arr[$key]=$info;
		}else{
			$arr=$info;
		}
		
		return $this->mongo->update('league',$arr,array("id"=>$league_id));    
	}

	/*
	 *队员申请或邀请       
	 * @param    $league_id 队伍id
	 * @param    $info      更新内容
	 * @param    $state    9申请 8邀请
	 * @return  boolen
	 */
	function add_league($league_id,$user_id,$state=9)
	{
		$l_arr= $this->mongo->findone('league_user',array("id"=>$league_id,"user_id"=>$user_id)); 
		if($l_arr)
		{
			return false;
		}else{
			$adduser=array(
							"league_id"=>$league_id,
							'user_id'=>$user_id, //用户ID
							'state'=>$state, //申请状态( 1队长 2副队长 3干事 4普通成员 9申请中)																		'time'=>time(),
					);
		}
		//$this->up_user_info($league_id,$user_id,'league_id');  //添加到用户表
		return $this->mongo->insert('league_user',$adduser);    
	}
	
	 /*
	 *队伍成员更新
	* @param    $league_id 队伍id
	 * @param    $user_id   用户id
	 * @param    $info      更新内容
	 * @param    $key     更新字段
	 * @return  boolen
	 */
	function up_league_mumber($league_id,$user_id=0,$info,$key=false)
	{
		if($key)
		{
			$fields=array($key=>$info);
		}else{
			$fields=$info;
		}
		
		if($user_id)
		{
			$query=array("league_id"=>$league_id,"user_id"=>$user_id);
		}else{
			$query=array("league_id"=>$league_id);
		}
		
		return $this->mongo->update("league_user", $fields, $query);
	 }

	 /**
	  * 删除队员
	 * @param    $league_id 队伍id
	  * @param   integer    $league_id   队伍id
	  * @return  bool
	  */
	function del_league_member($league_id,$user_id=0)
	{
		if($user_id)
		{
			return $this->mongo->delete("league_user", array("league_id"=>$league_id,"user_id"=>$user_id));
		}else{
			return $this->mongo->delete("league_user", array("league_id"=>$league_id));
		}
		return false;
	}

       
	/**
	 * 新建临时组 必须有两个人才能创建
	 * @param   $userid_teamid    发起组队用户ID
	 * @param   $userid  被邀请用户组队ID
	 * @param   $e  0 $userid_teamid是队长ID  1 $userid_teamid是组队ID
	 * @return int
	 */
	function addteam($userid_teamid,$userid,$e=1)
	{
		$team=restoarray($this->mongo->find("team")->sort(array("id"=>-1))->limit(1));
		$team=$team[0];
		$team_id=intval($team["id"])+1;
		
		if(intval($e)==0)
		{
			$mmicrotime_float =str_replace(".","",mmicrotime_float());
		    $teamdata=array(
		    				"id"=>$team_id,
							"captain"=>	$userid_teamid,
							"list"=>array(
									$userid_teamid=>array("e"=>1,"time"=>time()),// 1正式成员, 2被邀请方通过，3队长通过
									$userid=>array("e"=>1,"time"=>time()),
								), 
			);
			
			$this->up_user_info($team_id,$userid_teamid,"team_id"); 
			$this->upget3data($userid_teamid,$team_id."_captain","team_id");
			
			$this->up_user_info($team_id,$userid,"team_id"); 
			$this->upget3data($userid,$team_id,"team_id");
			
			//file_put_contents(D_R."log/team.log", var_export($team,true));
			$this->mongo->insert("team", $teamdata);
			return $team_id;
		}else{
			$team=$this->mongo->findone("team",array("id"=>$userid_teamid));
			$team["list"][$userid]=array("e"=>1,"time"=>time());
			$this->up_user_info($userid_teamid,$userid,"team_id"); 
			$this->upget3data($userid,$userid_teamid,"team_id");
			return $this->mongo->update('team',array("list"=>$team["list"]),array("id"=>$userid_teamid));
		}
	}
	
	/**
	 * 删除临时组
	 * @param   $info     内容
	 * @param   $team_id  组队id
	 * @param   $key      要更新的字段
	 * @return array
	 */
	function delteam($teamid=0)
	{
		if($teamid)
		{
			$team=restoarray($this->mongo->find("team",array("id"=>$teamid)));
			foreach($team["list"] as $key=>$value)
			{
				$this->up_user_info(0,$key,"team_id"); 
				$this->upget3data($key,0,"team_id");
			}
		}else{
			$team=restoarray($this->mongo->find("team"));
			foreach($team as $key=>$value)
			{
				foreach($value["list"] as $k=>$v)
				{
					$this->up_user_info(0,$k,"team_id"); 
					$this->upget3data($k,0,"team_id");
				}
			}
			$this->mongo->delete("team");
		}
	}
	
		
	/**
	 * 获取临时组
	 * @param   $team_id  组队id
	 * @return array
	 */
	function getteam($teamid=0)
	{
		$nteam=array();
		$team=restoarray($this->mongo->find("team"));
		foreach($team as $key=>$value)
		{
			$nteam[$value["id"]]=$value;
		}
		if($teamid==0)
		{
			return $nteam;
		}else{
			return $nteam[$teamid];
		}
	}
	
	/**
	 * 更新临时组
	 * @param   $info     内容
	 * @param   $team_id  组队id
	 * @param   $key      要更新的字段
	 * @return array
	 */
	function upteam($info,$teamid,$key=false)
	{
		if($key)
		{
			$fields=array($key=>$info);
		}else{
			$fields=$info;
		}
		
		$query=array("id"=>$teamid);
		return $this->mongo->update("team", $fields, $query);
	}
	
	/**
	 * 升级时间信息加入
	 * @param string $user_id 用户ID
	 * @param string $type  升级类型 1奇门遁甲 2忍术升级及学习 3忍术修炼 4气海升级 5征收
	 * @param array $time 所需时间
	 * @param array $callback 回调函数 如果是类的话用数组格式 array("function"=>"collection_over","class"=>"qq_api\national_power\national_power")
	 * @param array $arr 外部存入一个数组 可选
	 * @return key false
	 */
	function add_uptime($user_id,$type,$time,$callback=false,$arr=false)
	{
		$arr1=array(
					"user_id"=>$user_id,
					"type"=>$type,
					"time"=>$time,
					"callback"=>$callback,
					"arr"=>$arr,
		);
		return $this->mongo->insert("uptime", $arr1);
	}
	
	/**
	 * 获取升级时间信息获取
	 * @param int $user_id 用户ID
	 * @param int $type 类型标识
	 * @param int $list_id 序列ID，具体ID
	 * @return arr 
	 */
	function get_uptime($user_id=false,$type=false,$list_id=false)
	{
		if($list_id)
		{
			$query=array("_id"=>new \MongoId($list_id));
		}else{
			if($user_id and $type)
			{ 
				$query=array("user_id"=>$user_id,"type"=>$type);
			}elseif(!$user_id and $type){
				$query=array("type"=>$type);
			}elseif($user_id and !$type){
	            $query=array("user_id"=>$user_id);
			}elseif(!$user_id and !$type){
				 $query=array();
			}
		}
		
		$uptime=restoarray($this->mongo->find("uptime",$query));
		return $uptime;
	}
	
	/**
	 * 修改升级时间信息
	 * @param int $list_id 序列ID
	 * @param array $time 所需时间
	 * @return true false
	 */
	function up_uptime($list_id,$time)
	{
		return $this->mongo->update("uptime", array("time"=>$time), array("_id"=>new \MongoId($list_id)));
	}
	
	/**
	 * 删除升级信息
	 * @param int $list_id 序列ID
	 * @return true false
	 */
	function del_uptime($list_id)
	{
		return $this->mongo->delete("uptime",array("_id"=>new \MongoId($list_id)));
	}
	
	/**
	 * 加入buff
	 * @param string $user_id 用户ID
	 * @param string $type  1药品
	 * @param array $time 所需时间
	 * @param array $callback 回调函数 如果是类的话用数组格式 array("function"=>"collection_over","class"=>"qq_api\national_power\national_power")
	 * @param array $arr 外部存入一个数组 可选
	 * @return key false
	 */
	
	function addbuff($user_id,$type,$time,$callback=false,$arr=false)
	{
		$arr1=array(
					"user_id"=>$user_id,
					"type"=>$type,
					"time"=>$time,
					"callback"=>$callback,
					"arr"=>$arr,
		);
		return $this->mongo->insert("buff", $arr1);
	}
	
	/**
	 * 获取buff
	 *
	 * @param integer $user_id 用户ID
	 * @param integer $type 类型
	 * @param integer $buff_id BUFFID
	 * @return bool
	 */
	function getbuff($user_id=false,$type=false,$buff_id=false)
	{
		if($buff_id)
		{
			 $query=array("_id"=>new \MongoId($buff_id));
		}else{
			if($user_id and $type)
			{ 
				$query=array("user_id"=>$user_id,"type"=>$type);
			}elseif(!$user_id and $type){
				$query=array("type"=>$type);
			}elseif($user_id and !$type){
	            $query=array("user_id"=>$user_id);
			}elseif(!$user_id and !$type){
				 $query=array();
			}
		}
		
		$uptime=restoarray($this->mongo->find("buff",$query));
		return $uptime;
	}
	
	/**
	 * 更新buff
	  * @param integer $buff_id BUFFID
	 * @param integer $time 时间
	 * @return bool
	 */
	function upbuff($buff_id,$time,$data=false)
	{
		if($data)
		{
			return $this->mongo->update("buff",$data, array("_id"=>new \MongoId($buff_id)));
		}else{
			return $this->mongo->update("buff", array("time"=>$time), array("_id"=>new \MongoId($buff_id)));
		}
	}
	
	/**
	 * 删除buff
	 * @param integer $buff_id
	 * @return bool
	 */
	function delbuff($buff_id)
	{
		return $this->mongo->delete("buff", array("_id"=>new \MongoId($buff_id)));
	}

	/**
	 * 更新技能升级时间表 这个有问题
	 */
	function upgrade_state($user_id,$key,$value)
	{
		$fields=array($key=>$value);
		$query=array("user_id"=>$user_id);
		$this->mongo->update("upgrade_state", $fields, $query);
		/*
		$upgrade_state = $this->mem->get("upgrade_state");
		if ($key && $value) {
			foreach ($upgrade_state as $k => $v) {
				if ($v['user_id'] == $user_id) {
					if ($key == 'v_id') {
						$upgrade_state[$k]['v_start'] = $value;
					}elseif($key == 'qihai_id') {
						$upgrade_state[$k]['qihai_start'] = $value;
					}
				}
			}
			return $this->mem->replace("upgrade_state",$upgrade_state);
		}else {
			foreach ($upgrade_state as $k => $v) {
				if ($v['user_id'] == $user_id) {
					return $v;
				}
			}
		}*/
	}

	//取得仓库列表
	function getdepot($user_id,$goods_user_id=false)
	{
		$depot=array();
		require_once(D_R."res/data/base_goods.php");
		$goods_depot = $this->mongo->find("user_goods_depot");
		
		if($goods_depot)
		{
			foreach((array)$goods_depot as $keys =>$value)
			{
				$depot[$value['goods_user_id']]=arrunite($base_goods[$value['goods_id']],$value);
			}
		}else{
			return false;
		}

		if(!$goods_user_id)
		{
			return $depot;
		}else{
			if(!$key)
			{
				return $depot[$goods_user_id];
			}else{
				return $depot[$goods_user_id][$key]?$depot[$goods_user_id][$key]:false;
			}
		}
	}
	
		//更新仓库
	function updepot($info,$user_id,$goods_user_id=false,$key=false)
	{
		if($key)
		{
			$fields=array($key=>$info);
		}else{
			$fields=$info;
		}
		$query=array("goods_user_id"=>$goods_user_id);
		return $this->mongo->update("user_goods_depot", $fields, $query);
	}
	
	//扔掉仓库里的物品
	function del_goods_depot($user_id,$goods_user_id,$sum=1)
	{
		return $this->mongo->delete("user_goods_depot", array("goods_user_id"=>$goods_user_id));
	}
	

	/*装备基表和扩展表组合
	 *
	 */
	function goods()
	{
            require(D_R.'res/data/base_goods_ext.php');
            require(D_R.'res/data/base_goods.php');
            foreach($base_goods_ext as $k=>$v){
                 foreach($base_goods as $key=>$value){
                     if($v['goods_id'] == $key ){
                         $goods[$v['goods_ext_id']] = arrunite($value,$v);
                     }
                     
                 }
            }
           return $goods;
	}
	
	/**
	 * 取得用户背包一个可用的方格号 方格号必须从1开始
	 *
	 * @param integer $user_id 户ID
	 * @param integer $goods_user_id 物品ID
	 * @param integer $sum 数量
	 * @return 返回值 1：方格数组|false 数组下标 0 表示方格号  1表示是否叠加（0表示不可叠加 1表示可以叠加） 2表示 用户物品ID
	 */
	function user_lattice_id($user_id,$goods_user_id=false,$sum=1)
	{
		
		$getpack=array();
		$query=array("user_id"=>$user_id);
		$user_info=$this->mongo->findone("user_info",$query);
		//$base_goods=$this->res->base_goods();
		
		if($goods_user_id)
		{//首先判断是否可以叠加，如果可以叠加，叠加有先
			$query["goods_user_id"]=$goods_user_id;
			$goods=$this->mongo->findone("user_goods",$query);
			if($goods)
			{
				$base_goods=dbarr("SELECT `wrap_num` FROM `base_goods` WHERE `id`=".$goods["goods_id"]);
				$wrap_num=$base_goods[0]['wrap_num'];//叠加数量
				//$wrap_num=$base_goods[$goods["goods_id"]]['wrap_num'];//叠加数量
				if((intval($goods['goods_num'])+$sum)<$wrap_num)
				{//返回此方格号进行叠加
					return array(0=>$goods['lattice_id'],1=>1,2=>$goods_user_id);
				}
			}
		}
		
		//非叠加
		$goods=restoarray($this->mongo->find("user_goods",$query));
		
		foreach((array)$goods as $key=>$value)
		{
			if($value["lattice_id"])
			{
				$getpack[$value["lattice_id"]]=$value;
			}
		}
		
		if($user_info['vip_lv']>0){
			$user_info['pack_grade']=$user_info['pack_grade']*2;
		}
		
		//背包格树循环
		for($i=1;$i<=intval($user_info['pack_grade']);$i++)
		{
			if(!isset($getpack[$i]))
			{
				return array(0=>$i,1=>0);
			}
		}
		
		
		$this->error['err_msg']=1083;//为背包方格号已经没有
		return false;
	}
	
	
	//创建一个交易,返回交易key值
	function create_trading($user_id1,$user_id2)
	{
		$mmicrotime=str_replace(".","",mmicrotime_float());
		$tt = 'trading_'.$mmicrotime;
		$arr=array(
			$user_id1 =>array("is_ok"=>0,"goods"=>array()),
			$user_id2 =>array("is_ok"=>0,"goods"=>array()),
		);

		if($this->mem->set($tt,$arr,1800))
		{
			$this->upget3data($user_id1,$tt,"trading_id");
			$this->upget3data($user_id2,$tt,"trading_id");
			return $tt;
		}else{
			return false;
		} 
	}

	//修改交易数据
	function update_trading($trading_id,$tradinginfo)
	{
		return $this->mem->replace($trading_id,$tradinginfo,1800);
		//return $this->mem->replace($trading_id,$tradinginfo);
	}
	
	//删除一个交易
	function del_trading($trading_id)
	{
		$trading=$this->get_trading($trading_id);
		foreach((array)$trading as $key=>$value)
		{
			$this->upget3data($key,0,"trading_id");
		}
		
		return $this->mem->delete($trading_id);
	}
	
    //取出一个交易
	function get_trading($trading_id)
	{
		return $this->mem->get($trading_id);
	}

	/*
	* 取得地图关系表
	* 
	*/
	function get_map_nexus()
	{
		return $this->mongo->findone("map_nexus");
	}
	
/*加入战争
	 * @param array $attack_user_id  攻击方ID
	 * @param array $defend_user_id  防守方ID
	 * @param int $time  最后更新时间
	 * @param int $monster 与人还是与怪
	 * @param int $type_of_war  战争类型，1遭遇战 2攻城战 3国战 4竞技场
	 * @param array $attack_info 收集到或处理好的攻击方信息
	 * @param array $defend_info 收集到或处理好的防守方信息
	 * @param int $assemblymonster  如果是怪物则传入怪物集合ID
	返回  战争号
	*/
	function addwar($attack_user_id,$defend_user_id,$time,$monster,$type_of_war,$attack_info,$defend_info,$assemblymonster=0)
	{
		$war_list=array();
		$asum=0;
		$dsum=0;
		
		if($monster==0)
		{
			foreach((array)$attack_user_id as $key=>$value)
			{
				$at_f=$this->fairyism($value);
				if(intval($at_f["tmp_competition"])==0 and intval($at_f["competition"])==2)
				{
					$asum++;
				}
			}
			
			foreach((array)$defend_user_id as $key=>$value)
			{
				$dt_f=$this->fairyism($value);
				if(intval($dt_f["tmp_competition"])==0 and intval($dt_f["competition"])==2)
				{
					$dsum++;
				}
			}
		}else{
			foreach((array)$attack_user_id as $key=>$value)
			{
				$at_f=$this->fairyism($value);
				if(intval($at_f["tmp_competition"])==0)
				{
					$asum++;
				}
			}
			
			$dsum=count($defend_user_id);
		}

		if($monster==0)
		{
			if($asum!=count($attack_user_id))
			{
				$this->error["mem"]=1423;//自己还在战斗状态或新手无法对人物发起攻击
				return false;
			}
			
			if($dsum!=count($defend_user_id))
			{
				$this->error["mem"]=1424;//对方还在战斗状态或对方还是新手
				return false;
			}
				
		}else{
			if($asum!=count($attack_user_id))
			{
				$this->error["mem"]=1423;//自己还在战斗状态或新手无法对人物发起攻击
				return false;
			}
		}
		
		//$war_list=$this->mem->get("war_list");
		$war_idtmp=restoarray($this->mongo->find("war")->sort(array("war_id"=>-1))->limit(1));
		$war_idtmp=$war_idtmp[0];
		if($war_idtmp)
		{
			$war_id=$war_idtmp["war_id"]+1;
		}else{
			$war_id=1;
		}
		
		$war_arr=array(
			"war_id"=>$war_id,
			"attack_user_id"=>$attack_user_id,
			"defend_user_id"=>$defend_user_id,
			"time"=>$time,
			"attack_info"=>$attack_info,
			"defend_info"=>$defend_info,
			"monster"=>$monster,
			"war_obg_id"=>$attack_user_id[0],//下一次由这个用户开始攻击
			"war_obg"=>0,//战争对象
			"war_obg_d"=>"attack",//哪方战争 attack攻击方发起攻击， defend防御方发起攻击
			"type_of_war"=>$type_of_war,//战争类型，1遭遇战 2攻城战 3国战
			'next_war_obg_d'=>"attack",//下一个攻击对象
			'assemblymonster'=>$assemblymonster,//如果是怪物则传入怪物集合ID
			"start"=>false,
		);
		
		$this->mongo->inster("war",$war_arr);
		
		foreach((array)$attack_user_id as $key=>$value)
		{
			$at_f=$this->fairyism($value);
			$at_f["tmp_competition"]=1;
			$this->upfairyism($at_f,$value);
			
			
		}
		
		foreach((array)$defend_user_id as $key=>$value)
		{
			$dt_f=$this->fairyism($value);
			$dt_f["tmp_competition"]=1;
			$this->upfairyism($dt_f,$value);
		}
		return $mmicrotime;
	}
	
	/*更新战争数据
	$war_id  战争号
	$info  更新的数据 允许数组
	$key  指定的健名 可以为false 允许数组
	返回  false true
	*/
	function upwar($war_id,$info,$key=false)
	{
		if($key)
		{
			$filed=array($key=>$info);
		}else{
			$filed=$info;
		}
		return $this->mongo->update("war",$filed,array("war_id"=>$war_id));
	}
	
	/*删除战争
	$war_id  战争号
	返回  false true
	*/
	function delwar($war_id)
	{
		$war_id.="";
		if(!$war_id)
		{
			return false;
		}
		
		$war=$this->mongo->findone("war",array("war_id"=>$war_id));
		
		foreach((array)$war["attack_user_id"] as $key=>$value)
		{
			$at_f=$this->fairyism($value);
			$at_f["tmp_competition"]=0;
			$this->upfairyism($at_f,$value);
		}
		
		if($war["monster"]==0)
		{
			foreach((array)$war["defend_user_id"] as $key=>$value)
			{
				$dt_f=$this->fairyism($value);
				$dt_f["tmp_competition"]=0;
				$this->upfairyism($dt_f,$value);
			}
		}
		
		$this->mongo->delete("war",array("war_id"=>$war_id));
	}
	
		
	/*获取战争数据
	$war_id  战争号
	返回  false data
	*/
	function getwar($war_id=0)
	{
		$war_id.="";
		if($war_id==0)
		{
			return false;
		}else if($war_id){
			return $this->mongo->findone("war",array("war_id"=>$war_id));
		}else{
			return false;
		}
	}
	
	/*获取战争列表
	$war_id  战争号
	返回  false data
	*/
	function getwar_list()
	{
		return restoarray($this->mongo->find("war",array(),array("war_id"=>1)));
	}

	/*战争物品存储
	$war_id_goods  战争号+goods标识
	$info  		要存入的内容
	返回  false data
	*/
	function addwargoods($war_id_goods,$info)
	{
		return $this->mem->set($war_id_goods,$info,999);
	}
	
	/*获取战物品数据
	$war_id_goods  战争号+goods标识
	返回  false data
	*/
	function getwargoods($war_id_goods)
	{
		return $this->mem->get($war_id_goods);
	}
	
	/*更新战争物品
	$war_id_goods  战争号+goods标识
	$info  		要更新的内容
	返回  false data
	*/
	function upwargoods($war_id_goods,$info)
	{
		return $this->mem->replace($war_id_goods,$info,999);
	}
	
	/*删除战争物品
	$war_id_goods  战争号+goods标识
	返回  false data
	*/
	function delwargoods($war_id_goods,$list_id=99999999)
	{
		if($list_id==99999999)
		{
			return $this->mem->delete($war_id_goods);
		}elseif($list_id>=0){
			$wargoods=$this->getwargoods($war_id_goods);
			unset($wargoods[$list_id]);
			return $this->upwargoods($war_id_goods,$wargoods);
		}else{
			return false;
		}
	}
	
		/*
         * 由昵称获取用户的信息
         * @param varchar $nickname
         * 
         */
        function nickname_user_info($nickname)
        {
        	$user=$this->mongo->findone("user_info",array("nickname"=>$nickname),array("id"=>1));
			$user_id=$user["id"];
			$user =$this->user_info($user_id);
			 if($user){
                return $user;
            }else{
                return false;
            }
        }

		/*
         * 获取攻坚战双方信息列表
         * 
         */
		function siege()
		{
			return restoarray($this->mongo->find("siege"));
		}
      
		/*
          *将战争号注入战争列表
		*$ingroup, 攻击目标国ID
		*$war_id,  战争号
         */
		function add_war_id_siege($ingroup,$war_id)
		{
			return $this->mongo->insert("siege",array("war_id"=>$war_id,"ingroup"=>$ingroup));
		}
			
		/*
         * 加入攻坚战双方信息列表
		 $user_id,
		$ingroup,  攻击目标国ID
		$e    攻击 还是防守attack   defend
         */
		function addsiege($user_id,$ingroup)
		{
			$user_info=$this->user_info($user_id);
			$dt_f=$this->fairyism($user_id);

			if($dt_f["competition"]!=2)
			{
				$this->error=1399;//您不是战争模式
				return false;
			}
			
			if($dt_f["tmp_competition"]!=0)
			{
				$this->error=1400;//请先推出战争或交易
				return false;
			}
			
			$siege=array();
			$siege=$this->siege("siege");
			$sy=$siege?1:0;
			 
			if((!$siege[$ingroup] or !$siege[$ingroup]["battle"]["defend"]))
			{
				$siege[$ingroup]["battle"]=array("attack"=>0,"defend"=>$ingroup);
			}
			
			if(!$siege[$ingroup]["war_id"])
			{
				$siege[$ingroup]["war_id"]=0;
			}
			
			
			$mmicrotime=str_replace(".","",mmicrotime_float());
			$siege[$ingroup]["ingroup"][$user_info["ingroup"]][$user_id]=$mmicrotime;
			
			//分析出攻击方数量
			if(!$siege[$ingroup]["battle"]["attack"])
			{
				foreach((array) $siege[$ingroup]["ingroup"] as $key=>$value)
				{
					if(count($value)>=5 and $key!=$ingroup)
					{
						if(!$siege[$ingroup]["battle"]["attack"])
						{
							$siege[$ingroup]["battle"]["attack"]=$user_info["ingroup"];
						}
					}
				}
			}
			
			$dt_f["tmp_competition"]=2;
			$this->upfairyism($dt_f,$user_id);
			
			return $this->mongo->insert("siege",$siege);
		}
		
		/*
         * 更新攻坚战双方信息列表 将玩家置为 战争状态
		 $user_id,
         */
		function upsiege($user_id)
		{
			$siege=array();
			$siege=$this->siege("siege");
			foreach((array)$siege as $key=>$value)
			{
					foreach((array)$value["ingroup"] as $k1=>$v1)
					{
						foreach((array)$v1 as $k2=>$v2)
						{
							if($k2==$user_id)
							{
								$siege[$key][$k1][$k2]=1;
								return $this->mongo->insert("siege",$siege);
							}
						}
					}
			}
			return false;
		}
		
		/*
         * 删除攻坚战双方信息列表
		 $user_id,
         */
		function delsiege($user_id=0)
		{
			if($user_id==0)
			{
				return $this->mongo->delete("siege");
			}else{
				$siege=array();
				$siege=$this->siege("siege");
				foreach((array)$siege as $key=>$value)
				{
						foreach((array)$value["ingroup"] as $k1=>$v1)
						{
							foreach((array)$v1 as $k2=>$v2)
							{
								if($k2==$user_id)
								{
									unset($siege[$key][$k1][$k2]);
									return $this->mongo->insert("siege",$siege);
								}
							}
						}
				}
			}
			return false;
		}
		
	/**
	 * 取得数据地图信息
	 * @param int $map_id 区域地图
	 * @param int $area_id 板块地图
	 * @param arr $query 查询条件
	 * @return arr
	 */
	function get_map($map_id,$area_id,$query=array())
	{
		$arr=array();
		if($map_id){$query["map_id"]=intval($map_id);}
		if($area_id){$query["area_id"]=intval($area_id);}
		
		if($map_id==_NOVICE_MAP)
		{
			$tab="map_data_novice";
		}else{
			$tab="map_data";
		}
		
		$tmp=restoarray($this->mongo->find($tab, $query));
		foreach($tmp as $value){$arr[$value["id"]]=$value;}
		return $arr;
	}
	
	/**
	 * 新增加一条数据地图
	 * @param int $map_id 区域地图
	 * @param int $area_id 板块地图
	 * @param arr $arr 插入的数据
	 * @return arr
	 */
	function add_map($map_id,$area_id,$arr=array())
	{
		$arr["map_id"]=intval($map_id);
		$arr["area_id"]=intval($area_id);
		$id=max_id($this,"map_data");
		$arr["id"]=$id;
		
		if($map_id==_NOVICE_MAP)
		{
			$tab="map_data_novice";
		}else{
			$tab="map_data";
		}
		return $this->mongo->insert($tab, $arr);
	}
	
	/**
	 * 更新一条数据地图
	 * @param int $map_id 区域地图ID
	 * @param int $data_id 数据地图ID
	 * @param arr $update 更新数据
	 * @param string $key 更新字段
	 * @return bool
	 */
	function up_map($map_id,$data_id,$update,$key=false)
	{
		if($key)
		{
			$tme=$update;
			unset($update);
			$update[$key]=$tme;
		}
		if($map_id==_NOVICE_MAP)
		{
			$tab="map_data_novice";
		}else{
			$tab="map_data";
		}
		return $this->mongo->update($tab,$update,array("id"=>intval($data_id)));
	}
	
	/**
	 * 删除一条数据地图
	 * @param int $map_id 区域地图ID
	 * @param int $data_id 数据地图ID
	 * @return bool
	 */
	function delete_map($map_id,$data_id)
	{
		if($map_id==_NOVICE_MAP)
		{
			$tab="map_data_novice";
		}else{
			$tab="map_data";
		}
		return $this->mongo->delete($tab,array("id"=>intval($data_id)));
	}
	
	
	/**
	 * 获取城市
	 * @param int $user_id 用户ID
	 * @param int $city_id 城市ID
	 * @return arr
	 */
	function get_city($user_id,$city_id=false)
	{
		if($city_id)
		{
			return $this->mongo->findone("user_city", array("id"=>intval($city_id)));
		}else{
			$arr=array();
			$tmp=restoarray($this->mongo->find("user_city", array("user_id"=>intval($user_id))));
			foreach($tmp as $value)
			{
				$arr[$value["id"]]=$value;
			}
			return $arr;
		}
	}
	
	/**
	 * 添加城市
	 * @param int $map_id 区域地图ID
	 * @param int $area_id 板块地图ID
	 * @param int $user_id 用户id
	 * @param int $name 城市名称
	 * @param int $capital 是否首都
	 * @param int $page 标识城市在新手地图的第几页
	 * @return int 城市ID
	 */
	function add_city($map_id,$area_id,$user_id,$name,$capital=0,$page=0)
	{
		$user_info=$this->user_info($user_id);
		$nickname=$user_info["nickname"];
		$flag=getonestr($nickname);
		$arr=array(
					'id'=>max_id($this,"user_city"),
					'map_id'=>intval($map_id),
					'area_id'=>intval($area_id),
					'page'=>intval($page),
					'user_id'=>intval($user_id),
					'capital'=>intval($capital),
					'flag'=>$flag,
					'name'=>$name,
					'urban_defense'=>1,
					'hero'=>"",
					'uptime'=>time(),
					'number_grid'=>7,
					'grid_e'=>0,
				);
				
		if($this->mongo->insert("user_city",$arr))
		{
			return $arr["id"];
		}else{
			return false;
		}
	}
	
	/**
	 * 更新城市
	 * @param int $user_id 用户ID
	 * @param int $city_id 城市ID
	 * @return arr
	 */
	function up_city($city_id,$info,$key=false)
	{
		$data=array();
		if($key)
		{
			$data[$key]=$info;
		}else{
			$data=$info;
		}
		return $this->mongo->update("user_city",$data, array("id"=>$city_id));
	}
	
	/**
	 * 删除城市
	 * @param int $city_id 城市ID
	 * @return bool
	 */
	function delete_city($city_id)
	{
		return $this->mongo->delete("user_city",array("id"=>$city_id));
	}
	
	/**
	 * 获取英雄（不包含下野的）
	 * @param int $user_id 用户ID
	 * @param int $hero_user_id 用户英雄ID
	 * @return arr
	 */
	function get_hero($user_id,$hero_user_id=false)
	{
		if($hero_user_id)
		{
			return $this->mongo->findone("user_hero", array("id"=>intval($hero_user_id)));
		}else{
			return restoarray($this->mongo->find("user_hero", array("user_id"=>intval($user_id),"herotype"=>array('$gt'=>-1))));
		}
	}
	
	/**
	 * 添加英雄
	 * @param int $user_id 用户ID
	 * @param int $hero_base_id 英雄基表ID
	 * @param int $herotype 英雄招募状态 0空闲 1筑城 2军队 -1下野
	 * @return arr
	 */
	function add_hero($user_id,$hero_base_id,$herotype=0)
	{
		$base_hero=$this->res->base_hero($hero_base_id);
		$base_hero["user_id"]=$user_id;
		unset($base_hero["id"],$base_hero["info"]);
		$base_hero["id"]=max_id($this,"user_hero");
		$base_hero["herotype"]=intval($herotype);
		$base_hero["lv"]=1;
		$this->mongo->insert("user_hero",$base_hero);
		
		//装备表中做相应的动作
		$arr=array(
			"id"=>max_id($this,"equip"),
			"user_id"=>$user_id,
			"hero_user_id"=>$base_hero["id"],
			"equip1"=>0,
			"equip2"=>0,
			"equip3"=>0,
			"equip4"=>0,
			"equip5"=>0,
			"equip6"=>0,
			"equip7"=>0,
			"equip8"=>0,	
			"equip9"=>0,	
			"equip10"=>0,
		);
		
		$this->mongo->insert("equip",$arr);
		return true;
	}
	
	/**
	 * 更新英雄
	 * @param int $hero_user_id 用户英雄ID
	 * @param int $info 更新内容
	 * @param int $key 更新key
	 * @return arr
	 */
	function up_hero($hero_user_id,$info,$key=false)
	{
		$data=array();
		if($key)
		{
			$data[$key]=$info;
		}else{
			$data=$info;
		}
		return $this->mongo->update("user_hero",$data, array("id"=>intval($hero_user_id)));
	}
	
	/**
	 * 删除英雄
	 * @param int $hero_user_id 用户英雄ID
	 * @return bool
	 */
	function delete_hero($hero_user_id)
	{
		$hero_user_id=intval($hero_user_id);
		$this->mongo->delete("equip",array("hero_user_id"=>$hero_user_id));
		return $this->mongo->delete("user_hero",array("id"=>$hero_user_id));
	}
	
	
	/**
	 * 获取军队
	 * @param int $user_id 用户ID
	 * @param int $army_id 军队ID
	 * @return arr
	 */
	function get_army($user_id,$army_id=false)
	{
		if($army_id)
		{
			return $this->mongo->findone("user_army", array("id"=>$army_id));
		}else{
			return restoarray($this->mongo->find("user_army", array("user_id"=>$user_id)));
		}
	}
	
	/**
	 * 添加军队
	 * @param int $user_id 用户ID
	 * @param arr $hero_list 英雄列表
	 * @return arr
	 */
	function add_army($user_id,$hero_list="")
	{
		$arr=array(
				'id'=>max_id($this,"user_army"),
				'user_id'=>$user_id,
				'hero_list'=>$hero_list,
				'location'=>"",
				'ongoing'=>"",
				'target'=>"",
			);
			
		if($this->mongo->insert("user_army",$arr))
		{
			return $arr["id"];
		}else{
			return false;
		}
	}
	
	/**
	 * 更新军队
	 * @param int $army_id 军队ID
	 * @param arr $info 	要更新的内容
	 * @param string $key 要更新的key
	 * @return bool
	 */
	function up_army($army_id,$info,$key=false)
	{
		$data=array();
		if($key)
		{
			$data[$key]=$info;
		}else{
			$data=$info;
		}
		return $this->mongo->update("user_army",$data, array("id"=>$army_id));
	}
	
	/**
	 * 删除军队
	 * @param int $army_id 军队ID
	 * @return bool
	 */
	function delete_army($army_id)
	{
		return $this->mongo->delete("user_army",array("id"=>$army_id));
	}
	
	/**
	 * 刷新民忠
	 * @param int $user_id 用户ID
	 * @return bool
	 */
	function up_loyalty($user_id,$loyalty)
	{
		return $this->mongo->update("user_army",array("loyalty_formula"=>$loyalty), array("id"=>$user_id));
	}
	
	
	/**
	 * 获取世界英雄
	 * @param int $user_id 用户ID
	 * @param int $world_hero_id 世界英雄ID
	 * @return arr
	 */
	function get_hero_world($user_id=false,$world_hero_id=false)
	{
		if($world_hero_id)
		{
			return $this->mongo->findone("hero_world", array("id"=>$world_hero_id));
		}elseif($user_id){
			return restoarray($this->mongo->find("hero_world", array("user_id"=>$user_id)));
		}else{
			return restoarray($this->mongo->find("hero_world"));
		}
	}
	
	/**
	 * 更新世界英雄得主
	 * @param int $army_id 军队ID
	 * @param arr $info 	要更新的内容
	 * @param string $key 要更新的key
	 * @return bool
	 */
	function up_hero_world($world_hero_id,$user_id)
	{
		return $this->mongo->update("hero_world",array("user_id"=>$user_id), array("id"=>$world_hero_id));
	}
}
?>