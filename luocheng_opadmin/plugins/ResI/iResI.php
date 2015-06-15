<?php 
namespace plugins\ResI;
use controllers\module as MODULE;
set_time_limit(0);

class iResI extends module
{
	
	//精力点:energy;晶石:spar;元宝:gold_point;玄铁:fe;
	//铜钱:copper;军资:exploit;蓝虎符:blue_point;紫虎符:purple_point;金虎符:gold_point;
	
	function resi_list()
	{
		$resi_list=array();
		$r_resi=restoarray($this->phpmongo->find("user_info"));
		if($r_resi)
		{
			foreach($r_resi as $value)
			{
				$user_info=$this->mongo->user_info($value["user_id"]);
				$user_hero=$this->phpmongo->findone("user_hero",array("user_id"=>$value["user_id"],"main"=>1));
				$value["nickname"]=$user_hero["name"];	
				$value["spar"]=$user_info["spar"];
				$value["gold_point"]=$user_info["gold_point"];
				$value["fe"]=$user_info["fe"];
				$value["nickname"]=$user_hero["name"];
				$value["copper"]=$user_info["copper"];
				$value["exploit"]=$user_info["exploit"];
				$value["blue_point"]=$user_info["blue_point"];
				$value["purple_point"]=$user_info["purple_point"];
				$value["gold_point"]=$user_info["gold_point"];
				$resi_list[]=$value;
			}
		}
		
		return $resi_list;
   }
   
	function updateResi($post)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$post["nickname"] ));
		
		if($user_info==false)
		{
			return false;
		}
		
		$user_info=$this->phpmongo->findone("user_info",array("user_id"=>$user_hero["user_id"]));
		if($user_info==false)
		{
			return false;
		}

		$yeskey=array("energy"=>1,"spar"=>1,"gold"=>1,"fe"=>1,"copper"=>1,"exploit"=>1,"blue_point"=>1,"purple_point"=>1,"gold_point"=>1);
		$update=array();
		foreach($post as $key=>$value)
		{
			if(intval($value)>0 and isset($yeskey[$key]))
			{
				$update[$key]=intval($value)+intval($user_info[$key]);
			}
		}
		
		$this->phpmongo->update("user_info",$update,array("user_id"=>$user_hero["user_id"]));
		
		//$this->phpmongo->update("user_info",array("spar","gold_point","fe","nickname","copper","exploit","purple_point","gold_point"),
		//array("spar","gold_point","fe","name","copper","exploit","purple_point","gold_point"));
	}
	
	//添加物品
	function goodslist($post)
	{
		$base_goods=dbarr("SELECT a.`name` ,b.goods_ext_id,a.wrap_num,b.start,b.quality FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_base_id WHERE a.`goods_type` in(1,2,3,4,5)");
			
		return $base_goods;
	}
	
	//发送目标
	function res_send_target($post)
	{
		$query=array();
		if($post["condition"]==0)
		{
			$tnickname=str_replace("，",",",$post["nickname"]);
			$nickname=explode(",",$tnickname);
			$query=array("nickname"=>array('$in'=>$nickname));

		}else{
			if($post["condition"]==1){//等极段
				$query=array(
						"user_lv"=>array(
								'$gte'=>intval($post["lv1"]),
								'$lte'=>intval($post["lv2"])
								),
						);
			}elseif($post["condition"]==2){//普通竞技排名
				$query=array(
						"rank_sports_a"=>array(
								'$gte'=>intval($post["lv1"]),
								'$lte'=>intval($post["lv2"])
								),
						);

			}elseif($post["condition"]==3){//战斗力排名
				$query=array(
						"average_rank"=>array(
								'$gte'=>intval($post["lv1"]),
								'$lte'=>intval($post["lv2"])
								),
						);

			}elseif($post["condition"]==4){//等级排名
				exit("等级排名尚未开发！");
				/*$query=array(
						"rank_sports_a"=>array(
								'$gte'=>intval($post["lv1"]),
								'$lte'=>intval($post["lv2"])
								),
						);*/
				
			}
		}
		
		if(count($query)==0){
			exit("条件不合法");	
		}
		
		$user_info=$this->phpmongo->find("user_info",$query,array("user_id"=>1,"nickname"=>1));
		$ucount=$user_info->count();
		print_r(json_encode($query));
		echo "<br>";
		if($ucount==0){
			echo "发送失败:";
			exit( "条件查询结果是:0");
		}else{
			echo "查询到：".$ucount." 个结果<br>";
		}
		return $user_info;
	}
	
	//添加邮件奖励
	function addmailRes($post)
	{
		$target=$this->res_send_target($post);
		$adddata=array( 'type'=>1,
						'send_id'=>0,
						'get_id'=>0,
						'title'=>$post["title"],
						'text'=>$post["text"],
						'award_goods'=>json_decode($post["info"]),
						'send_time'=>time(),
						'ip_from'=>"0.0.0.0",
						'read_state'=>0,
						"module_sub_id"=>-2,
					);
//			print_r($adddata);
		$i=0;
		foreach($target as $obj)
		{
			$i++;
			$adddata["get_id"]=$obj["user_id"];
			$this->phpmongo->insert("message",$adddata);
		}
		print("邮件发送成功 ".$i." 个 <br\><br\><br\><br\><br\><br\>");
	}
	
	//添加物品
	function addgoods($post)
	{
		$goods_info=json_decode($post["info"]);
		if(is_object($goods_info))
		{
			$goods_info = (array)$goods_info;
		}
		
		$new_goods_info=array();
		foreach((array)$goods_info as $obj){
			foreach($obj as $key=>$value){
				$new_goods_info[$key]=$value;
			}
		}
		
		$target=$this->res_send_target($post);
		$i=0;
		foreach($target as $value)
		{
			$j=0;
			foreach($new_goods_info as $goods_ext_id=>$sum)
			{
				$_goods_info=array();
				$_goods_info["goods_ext_id"]=intval($goods_ext_id);
				$_goods_info["goods_num"]=intval($sum);
				$addgoods=$this->mongo->addgoods($value["user_id"],$_goods_info);
	 
				if($addgoods==false)
				{
					ob_flush() ;
					echo "失败物品 ". $value["nickname"]."->".$goods_ext_id."<br>";
					flush() ;
				}else{
					$j++;	
				}
			}
			if($j==count($new_goods_info)){
				$i++;	
			}
		}
		
		print("物品发送成功 ".$i."个<br\><br\><br\><br\><br\><br\>");
	}
}
