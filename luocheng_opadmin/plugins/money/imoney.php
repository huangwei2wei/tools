<?php 
namespace plugins\money;
use controllers\module as MODULE;
use kernel\base\base_class\mongopage as MONGOPAGE;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class imoney extends module
{
	
	//金币
	function user_money_count($type,$e="-",$time1,$time2)
	{		
		$money=dbarr("SELECT * FROM `base_module_sub` WHERE 1");
		$newdata=array();
		foreach ($money as $key=>$value)
		{
			$newdata[$value["id"]]=$value["sub_module_name"];
		}

		if($time1)
		{
			$time1s=explode("-",$time1);
			$time1=$time1s[0].$time1s[1].$time1s[2];
		}
		
		if($time2)
		{
			$time2s=explode("-",$time2);
			$time2=$time2s[0].$time2s[1].$time2s[2];
		}
		
		$query=array(
				"money_type"=>$type,
				"e"=>$e
				);
		
		if($time1 and $time2)
		{
			$query["timecount"]=array('$gte'=>intval($time1),'$lte'=>intval($time2));	
		}elseif($time1)
		{
			$query["timecount"]=intval($time1);	
		}
		
		$oarr1 =$this->phpmongo->aggregate("log_money_act_count",array("module_sub_id"),array("money"),$query,false,false,0,0,0);
		return array($oarr1,$newdata); 
	}

	
	function base_module_list()
	{
		return dbarr("SELECT a.`id`,a.`module_sub_id`,a.`sub_module_name` as module_name  FROM `base_module_sub` as a inner join base_module as b on   a.module_sub_id=b.id WHERE b.count=1");
	}
	
	function jinbi_count($type,$module_sub_id)
	{
		$money=dbarr("SELECT * FROM `base_module_sub` WHERE 1");
		$newdata=array();
		foreach ($money as $key=>$value)
		{
			$newdata[$value["id"]]=$value["sub_module_name"];
		}
		
		
		if(intval($module_id)>0)
		{
			$query1=array("money_type"=>$type,"e"=>"-","module_sub_id"=>intval($module_sub_id));
			$query2=array("money_type"=>$type,"e"=>"+","module_sub_id"=>intval($module_sub_id));
		}else{
			$query1=array("money_type"=>$type,"e"=>"-");
			$query2=array("money_type"=>$type,"e"=>"+");
		}
		
		$oarr1 =$this->phpmongo->aggregate("log_money_act_count",array("timecount"),array("money"),$query1,false,false,0,0,0);
		$oarr2 =$this->phpmongo->aggregate("log_money_act_count",array("timecount"),array("money"),$query2,false,false,0,0,0);
		return array($oarr1,$oarr2,$newdata);
	  }
	
	function recharge($_time1,$_time2)
	{
		$time1=intval(str_replace("-","",$_time1));
		$time2=intval(str_replace("-","",$_time2));
		$q=array(
			"month"=>array('$gte'=>$time1,'$lte'=>$time2),
		);
		
		$time3=strtotime($_time1."-01 00:00:00");
		$time4=strtotime($_time2."-31 23:59:59");
		$q1=array(
				"createtime"=>array('$gt'=>$time3,'$lt'=>$time4),
			);
			
		$pay_log=$this->phpmongo->find("log_pay",$q); 
		$base_user_id=array();
		$pay_sum=array();
		$p_sum=0;
		$zmoney=0;
		foreach($pay_log as $value)
		{
			if(isset($pay_sum[$value["timecount"]])){
				$pay_sum[$value["timecount"]][0]++;
				$pay_sum[$value["timecount"]][1]+=$value["money"];
			}else{
				$pay_sum[$value["timecount"]]=array(1,$value["money"],0,0);
			}
			$p_sum++;	
			$base_user_id[$value["openid"]]=1;
			$zmoney+=$value["money"];
		}
		$newbase_id=array();
		foreach($base_user_id as $key=>$value)
		{
			$newbase_id[]=$key;
		}

		$user_base=$this->phpmongo->find("user_base",array("user_name"=>array('$in'=>$newbase_id))); 
		$buser_id=array();
		foreach($user_base as $value)
		{
			$buser_id[]=$value["id"];
		}
		
		$user_info=$this->phpmongo->find("user_info",array("old_server"=>array('$gt'=>0),"base_user_id"=>array('$in'=>$buser_id)),array("qq.openid"=>1,"old_server"=>1)); 
		$openid=array();
		foreach($user_info as $value)
		{
			$openid[]=$value["qq"]["openid"];
		}
		
		$q["openid"]=array('$in'=>$openid);
		$pay_log1=$this->phpmongo->find("log_pay",$q); 
		foreach($pay_log1 as $value)
		{
			$pay_sum[$value["timecount"]][2]+=$value["money"];
			$pay_sum[$value["timecount"]][3]++;
		}
		
		
		
		$put_in=$this->phpmongo->find("put_in"); 
		$put_in_sum=array();
		foreach($put_in as $value)
		{
			$put_in_sum[$value["timecount"]]=$value["money"];
		}
		
		$_user_base=$this->phpmongo->find("user_base",$q1); 
		$reg_base_count=array();
		foreach($_user_base as $value)
		{
			$d=date("Y-m-d",$value["createtime"]);
			if(isset($reg_base_count[$d])==false)$reg_base_count[$d]=0;
			$reg_base_count[$d]+=1;
		}
		
		
		return array("pay_sum"=>$pay_sum,"p_sum"=>$p_sum,"zmoney"=>$zmoney,"user_sum"=>count($newbase_id),"put_in_sum"=>$put_in_sum,"reg_base_count"=>$reg_base_count);
	  }
	  
	  function recharge_line_day($time1,$time2)
	  {
			$time1=strtotime($time1." 00:00:00");
			$time2=strtotime($time2." 23:59:59");
			$q=array(
				"ts"=>array('$gt'=>$time1,'$lt'=>$time2),
			);
				
			$pay_log=$this->phpmongo->find("log_pay",$q); 
		  	return $pay_log;
	  }
	  
	  function recharge_m($time1,$time2)
	  {
			$time1=intval(str_replace("-","",$time1));
			$time2=intval(str_replace("-","",$time2));
			$q=array(
				"month"=>array('$gte'=>$time1,'$lte'=>$time2),
			);
				
			$pay_log=$this->phpmongo->find("log_pay",$q); 
			$base_user_id=array();
			$pay_sum=array();
			$p_sum=0;
			$zmoney=0;
			foreach($pay_log as $value)
			{
				if(isset($pay_sum[$value["month"]])){
					$pay_sum[$value["month"]][0]++;
					$pay_sum[$value["month"]][1]+=$value["money"];
				}else{
					$pay_sum[$value["month"]]=array(1,$value["money"],0,0);
				}
				$p_sum++;	
				$base_user_id[$value["openid"]]=1;
				$zmoney+=$value["money"];
			}
			$newbase_id=array();
			foreach($base_user_id as $key=>$value)
			{
				$newbase_id[]=$key;
			}
	
			$user_base=$this->phpmongo->find("user_base",array("user_name"=>array('$in'=>$newbase_id))); 
			$buser_id=array();
			foreach($user_base as $value)
			{
				$buser_id[]=$value["id"];
			}
			
			$user_info=$this->phpmongo->find("user_info",array("old_server"=>array('$gt'=>0),"base_user_id"=>array('$in'=>$buser_id)),array("qq.openid"=>1,"old_server"=>1)); 
			$openid=array();
			foreach($user_info as $value)
			{
				$openid[]=$value["qq"]["openid"];
			}
			
			$q["openid"]=array('$in'=>$openid);
			$pay_log1=$this->phpmongo->find("log_pay",$q); 
			foreach($pay_log1 as $value)
			{
				$pay_sum[$value["month"]][2]+=$value["money"];
				$pay_sum[$value["month"]][3]++;
			}
			
			
			
			$put_in=$this->phpmongo->find("put_in"); 
			$put_in_sum=array();
			foreach($put_in as $value)
			{
				if(isset($put_in_sum[date("Ym",$value["timecount"])])==false)$put_in_sum[date("Ym",$value["timecount"])]=0;
				$put_in_sum[date("Ym",$value["timecount"])]+=$value["money"];
			}
			
			return array("pay_sum"=>$pay_sum,"p_sum"=>$p_sum,"zmoney"=>$zmoney,"user_sum"=>count($newbase_id),"put_in_sum"=>$put_in_sum);
	  }
	  
	  function queryrecharge($nickname)
	  {
//		$user_hero=$this->phpmongo->findone("user_hero",array("name"=>$nickname)); 
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nickname));  
		$user_base=$this->phpmongo->findone("user_base",array("id"=>$user_info["base_user_id"]));  
		$pay_log=$this->phpmongo->find("log_pay",array("openid"=>$user_base["user_name"]));
		$paylist=array();
		foreach($pay_log as $key=>$value)
		{
			$paylist[]=$value;
		}
		
		return $paylist;
	  }
	  
	function newrechargelist()
	{
		//return $this->phpmongo->find("log_pay",array())->sort(array("ts"=>-1))->limit(200); 
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=100;
		$pay_log=$mongopage->mongodb($this->phpmongo,"log_pay",array(),array("ts"=>-1));
		$mypages=$mongopage->p(true);
		$user_list=array();
		foreach((array)$pay_log as $value)
		{
			$user_list[]=$value["openid"];
		}
		
		$dataname1=array();
		$user_base=$this->phpmongo->find("user_base",array("user_name"=>array('$in'=>$user_list))); 
		$user_base_list=array();
		foreach($user_base as $value)
		{
			$user_base_list[]=intval($value["id"]);
			$dataname1[intval($value["id"])]=$value["user_name"];
		}
		
		$dataname2=array();
		$dataname3=array();
		$user_info=$this->phpmongo->find("user_info",array("base_user_id"=>array('$in'=>$user_base_list)),array("user_id"=>1,"base_user_id"=>1,"online"=>1)); 
		$user_info_list=array();
		foreach($user_info as $value)
		{
			$user_info_list[]=intval($value["user_id"]);
			$dataname2[intval($value["user_id"])]=$dataname1[$value["base_user_id"]];
			$dataname3[$dataname1[$value["base_user_id"]]]=$value["online"];
		}
		$user_hero=$this->phpmongo->find("user_hero",array("main"=>1,"user_id"=>array('$in'=>$user_info_list)),array("user_id"=>1,"name"=>1)); 
		$user_hero_list=array();
		foreach($user_hero as $value)
		{
			$user_hero_list[$value["user_id"]]=$value["name"];
		}
		
		$dataname4=array();
		foreach($user_hero_list as $key=>$value)
		{
			$dataname4[$dataname2[$key]]=$value;
		}
		return array($pay_log,$mypages,$dataname4,$dataname3);
		
	}
	
	function rechargelvlist()
	{
		$query2=array("money_type"=>1,"e"=>"-");
		$countdata2 =$this->phpmongo->aggregate("log_money_act",array("user_lv"),array("money"),$query2,false,false,0,0,0);
		$accuracy=array();
		foreach ($countdata2 as $key => $row) 
		{
            $accuracy[$row["_id"]["user_lv"]] =$row["money"];
		}
		ksort($accuracy);
		return $accuracy;
	}
	
	function user_info($user_name)
	{
		$user_base=$this->phpmongo->findone("user_base",array("user_name"=>$user_name)); 
		$user_info=$this->phpmongo->findone("user_info",array("base_user_id"=>$user_base["id"]));
		
		$user_hero=$this->phpmongo->findone("user_hero",array("user_id"=>$user_info["user_id"],"main"=>1)); 
		
		$user_hero_count=$this->phpmongo->find("user_hero",array("user_id"=>$user_info["user_id"]))->count(); 
		$user_goods_count=$this->phpmongo->find("user_goods",array("user_id"=>$user_info["user_id"]))->count(); 
		$pay_log=$this->phpmongo->find("log_pay",array("openid"=>$user_name));
		$pay_log_count=0;
		foreach($pay_log as $value)
		{
			$pay_log_count+=$value["money"];
		}
		
		return array(
		"user_base"=>$user_base,
		"user_hero"=>$user_hero,
		"user_info"=>$user_info,
		"user_hero_count"=>$user_hero_count,
		"user_goods_count"=>$user_goods_count,
		"pay_log_count"=>$pay_log_count,
		);
	}
	
	function user_infos($user_name)
	{		
		$user_hero=$this->phpmongo->findone("user_hero",array("name"=>$user_name,"main"=>1)); 
		
		$user_hero_count=$this->phpmongo->find("user_hero",array("user_id"=>$user_hero["user_id"]))->count(); 
		$user_goods_count=$this->phpmongo->find("user_goods",array("user_id"=>$user_hero["user_id"]))->count(); 
		
		$user_info=$this->phpmongo->findone("user_info",array("user_id"=>$user_hero["user_id"]));
		$user_base=$this->phpmongo->findone("user_base",array("id"=>$user_info["base_user_id"])); 
		$pay_log=$this->phpmongo->find("log_pay",array("openid"=>$user_base["user_name"]));
		$pay_log_count=0;
		foreach($pay_log as $value)
		{
			$pay_log_count+=$value["money"];
		}
		
		return array(
		"user_base"=>$user_base,
		"user_hero"=>$user_hero,
		"user_info"=>$user_info,
		"user_hero_count"=>$user_hero_count,
		"user_goods_count"=>$user_goods_count,
		"pay_log_count"=>$pay_log_count,
		"sports_rank"=>$user_info["sports_rank"],
		);
	}
	
	
	function rechargelv100()
	{
		$user_info=$this->phpmongo->find("user_info",array("sports_rank"=>array('$lt'=>101,'$gt'=>0)),array("sports_rank"=>1,"user_id"=>1,"base_user_id"=>1,"old_server"=>1));
		$base_rankuser_id=$rankuser_id=array();
		$base_randid=$randid=array();
		$old_server=array();
		foreach($user_info as $value)
		{
			$rankuser_id[]=$value["user_id"];
			$base_rankuser_id[]=$value["base_user_id"];
			$randid[$value["user_id"]]=$value["base_user_id"];
			$base_randid[$value["base_user_id"]]=$value["sports_rank"];
			$old_server[$value["base_user_id"]]=$value["old_server"];
		}
		
		$user_hero=$this->phpmongo->find("user_hero",array("user_id"=>array('$in'=>$rankuser_id),"main"=>1),array("name"=>1,"user_id"=>1)); 
		$user_hero_name=array();
		foreach($user_hero as $value)
		{
			$user_hero_name[$randid[$value["user_id"]]]=$value["name"];
		}
		
		$user_base=$this->phpmongo->find("user_base",array("id"=>array('$in'=>$base_rankuser_id))); 
		
		$openid=array();
		$openid_list=array();
		foreach($user_base as $value)
		{
			$openid[]=$value["user_name"];
			$openid_list[$value["user_name"]]=array("sports_rank"=>$base_randid[$value["id"]],"name"=>$user_hero_name[$value["id"]],"old"=>$old_server[$value["id"]]);
		}
		
		$query2=array("openid"=>array('$in'=>$openid));
		$countdata2 =$this->phpmongo->aggregate("log_pay",array("openid"),array("money"),$query2,false,false,0,0,0);
		
		foreach($countdata2 as $key=>$value)
  		{
			$countdata2[$key]["sports_rank"]=$openid_list[$value["_id"]["openid"]]["sports_rank"];
			$countdata2[$key]["name"]=$openid_list[$value["_id"]["openid"]]["name"];
			$countdata2[$key]["old"]=$openid_list[$value["_id"]["openid"]]["old"];
		}
		
		$newdata=array();
		$zmoney=0;
		foreach($countdata2 as $key=>$value)
  		{
			$zmoney+=$value["money"];
			$newdata[$openid_list[$value["_id"]["openid"]]["sports_rank"]]=array(
														"name"=>$openid_list[$value["_id"]["openid"]]["name"],
														"old"=>$openid_list[$value["_id"]["openid"]]["old"],
														"money"=>$value["money"],
														);
		}
		
		ksort($newdata);
		return array($newdata,$zmoney);
	}
	
	
	function rechargeuserlvlist()
	{
		$countdata2 =$this->phpmongo->aggregate("log_pay",array("openid","lv"),array("money"),$query2,false,false,0,0,0);
		$newdata=array();
		foreach($countdata2 as $key=>$value)
		{	
			$value["_id"]["lv"]==""?$lv=-1:$lv=$value["_id"]["lv"];
			if(isset($newdata[$lv])==false)$newdata[$lv]=array(1=>array(0,0),10=>array(0,0),100=>array(0,0),500=>array(0,0));
			
			if($value["money"]>=500){$newdata[$lv][500][0]+=1;$newdata[$lv][500][1]+=$value["money"];}
			if($value["money"]>=100 and $value["money"]<500){$newdata[$lv][100][0]+=1;$newdata[$lv][100][1]+=$value["money"];}
			if($value["money"]>=10 and $value["money"]<100){$newdata[$lv][10][0]+=1;$newdata[$lv][10][1]+=$value["money"];}
			if($value["money"]>=1 and $value["money"]<10){$newdata[$lv][1][0]+=1;$newdata[$lv][1][1]+=$value["money"];}
		}
		ksort($newdata);
		return $newdata;
	}
	
	function analog_recharge($post)
	{
		$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$post["nickname"]),array("money"=>1,"diamond"=>1,"today_diamond_time"=>1,"today_diamond"=>1,"base_user_id"=>1));
		if(!$user_info)
		{
			print("用户不存在");
			exit();
		}
		
		$update=array();
		if(contrast_dayH($user_info["today_diamond_time"]))
		{
			$update["today_diamond"]=0;
			$update["today_diamond_time"]=intval(date("Ymd"));
		}
		
		$update["money"]=intval($post["money"]+$user_info["money"]);
		$update["diamond"]=intval($post["money"]+$user_info["diamond"]);
		$this->phpmongo->update("user_info",$update,array("user_id"=>$user_hero["user_id"]));
		
		$user_base=$this->phpmongo->findone("user_base",array("id"=>$user_info["base_user_id"]),array("user_name"=>1));
		$addpaylog=array(
				  "amt"=>"-1",
				  "appid"=>-1,
				  "billno"=>"-1",
				  "e"=>(0),
				  "lv"=>-1,
				  "money"=>intval($post["money"]),
				  "month"=>intval(date("Ym",time())),
				  "openid"=>$user_base["user_name"],
				  "payamt_coins"=>"0",
				  "payitem"=>"-1",
				  "providetype"=>"-1",
				  "pubacct_payamt_coins"=>"-1",
				  "sig"=>"-1",
				  "timecount"=>intval(date("Ymd",time())),
				  "token"=>"-1",
				  "ts"=>time(),
				  "version"=>"v3",
				  "w"=>(-1),
				  "zoneid"=>(-1)
				);
		$this->phpmongo->insert("log_pay",$addpaylog);
		print("充值成功");
		exit();
	}
	#钻石消耗与产出
	function diamond_count($type,$module_sub_id, $time1, $time2)
	{
		$money=dbarr("SELECT * FROM `base_module_sub` WHERE 1");
		$newdata=array();
		foreach ($money as $key=>$value)
		{
			$newdata[$value["id"]]=$value["sub_module_name"];
		}
		
		$time1=strtotime($time1." 00:00:00");
		$time2=strtotime($time2." 23:59:59");
		if(intval($module_id)>0)
		{
			$query1=array("money_type"=>"diamond","e"=>"-","module_sub_id"=>intval($module_sub_id),"time"=>array('$gte'=>$time1,'$lte'=>$time2));
			$query2=array("money_type"=>"diamond","e"=>"+","module_sub_id"=>intval($module_sub_id),"time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}else{
			$query1=array("money_type"=>"diamond","e"=>"-","time"=>array('$gte'=>$time1,'$lte'=>$time2));
			$query2=array("money_type"=>"diamond","e"=>"+","time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}
		
		$oarr1 =$this->phpmongo->aggregate("log_money_act_count",array("timecount"),array("money"),$query1,false,false,0,0,0);
		$oarr2 =$this->phpmongo->aggregate("log_money_act_count",array("timecount"),array("money"),$query2,false,false,0,0,0);
		
		return array($oarr1,$oarr2);
	 }
	 #消耗次数
	 function consume_times($type,$module_sub_id, $time1, $time2)
	 {
		$oneday = 24 * 60 * 60;
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$arr_ret = array();
		for($time=$time1; $time<$time2; ) {
			if ($time + $oneday -1 <= $time2) {
				if(intval($module_sub_id)>0)
				{
					$query=array("money_type"=>"diamond","e"=>"-","module_sub_id"=>intval($module_sub_id),"time"=>array('$gte'=>$time,'$lt'=>$time+$oneday));
				}else{
					$query=array("money_type"=>"diamond","e"=>"-","time"=>array('$gte'=>$time,'$lt'=>$time+$oneday));
				}
			
				$oarrcount =$this->phpmongo->find("log_money_act_count",$query,array("user_id"=>true))->count();
				$date1=date('Y-m-d', $time);
				
				$arr_ret[$date1] = $oarrcount;
			}
			$time = $time+$oneday;
		}
		
		return $arr_ret;
	  }
	  
	  #消耗人数
	 function consume_nums($type,$module_sub_id, $time1, $time2)
	 {
		$oneday = 24 * 60 * 60;
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$arr_ret = array();
		for($time=$time1; $time<$time2; ) {
			if ($time + $oneday -1 <= $time2) {
				if(intval($module_sub_id)>0)
				{
					$query=array("money_type"=>"diamond","e"=>"-","module_sub_id"=>intval($module_sub_id),"time"=>array('$gte'=>$time,'$lt'=>$time+$oneday));
				}else{
					$query=array("money_type"=>"diamond","e"=>"-","time"=>array('$gte'=>$time,'$lt'=>$time+$oneday));
				}
			
				$oarr =$this->phpmongo->find("log_money_act_count",$query,array("user_id"=>true));
				$date1=date('Y-m-d', $time);
				$arr=array();
				foreach ($oarr as $id => $value) {
					$arr[]=$value["user_id"];
				}
				if (count($arr)>1) 
					$arr=array_unique($arr);
				$arr_ret[$date1] = count($arr);
			}
			$time = $time+$oneday;
		}
		
		return $arr_ret;
	 }
	  
	  #商城消耗
	 function market_consume($type,$module_sub_id, $time1, $time2)
	 {
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		
		if(intval($module_sub_id)>0)
		{
			$query=array("module_sub_id"=>intval($module_sub_id),"time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}else{
			$query=array("time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}
			
		$oarr = $this->phpmongo->aggregate("log_market_buy",array("goods_ext_id"),array("money"),$query,false,false,0,0,0);
		
		return $oarr;
	  }
	  
	function get_goods_name($goods_ext_id)
	{
		$db_goods_id = dbarr("SELECT goods_base_id FROM `base_goods_ext` WHERE goods_ext_id=".$goods_ext_id);
		$g_id = 0;
	
		foreach ($db_goods_id as $key=>$value)
		{
			$g_id = $value["goods_base_id"];
		}     
		$goods_ary = dbarr("SELECT name FROM base_goods WHERE id= ".$g_id);
		$name = "null";
		if (count($goods_ary))
			foreach ($goods_ary as $key=>$value)
			{
				$name = $value["name"];
			}
		
		return $name;
	}
	 //用户充值清单 
	 function user_recharge_list($nick, $select_type, $is_first)
	 {
		$pay_log = array();
		$mongopage=new MONGOPAGE;
		$mongopage->pagesize=50;
		
		if ($is_first) {
			$pay_log=$mongopage->mongodb($this->phpmongo,"log_pay",array(),array());
		} else {
			if($select_type == 1) { #角色名
				$user_info=$this->phpmongo->findone("user_info",array("nickname"=>$nick));  
				$user_base=$this->phpmongo->findone("user_base",array("id"=>$user_info["base_user_id"]));  
				$pay_log=$mongopage->mongodb($this->phpmongo,"log_pay",array("openid"=>$user_base["user_name"]),array());
			} elseif($select_type ==2){#充值流水号
				$pay_log=$mongopage->mongodb($this->phpmongo,"log_pay",array("billno"=>$nick),array());
			}elseif($select_type == 3) { #账号id
				$pay_log=$mongopage->mongodb($this->phpmongo,"log_pay",array("openid"=>$nick),array());
			}
		}
		
		$mypages=$mongopage->p(true);
		
		$paylist=array();
		$valuelist=array();
		foreach($pay_log as $key=>$value)
		{
			$valuelist = $value;
			if ($is_first == false and $select_type == 1) {
				$valuelist["nickname"] = $nick;
			} else {
				$user_base=$this->phpmongo->findone("user_base",array("user_name"=>$value["openid"]));
				$user_info=$this->phpmongo->findone("user_info",array("base_user_id"=>$user_base["id"]), array("nickname"=>true));
				if(count($user_info))
					$valuelist["nickname"] = $user_info["nickname"];
				else 
					$valuelist["nickname"] = "";
			}
			
			$paylist[]=$valuelist;
		}
		
		return array($paylist, $mypages);
	  } 
	  
	  //付费情况
	  function PaySituation($time1,$time2,$channel_id,$mobile_platforms){
		$q = array();
		if($time1 && $time2){
			$time1=intVal(date("Ymd", strtotime(strval($time1))));
			$time2=intVal(date("Ymd", strtotime(strval($time2))));
			$q=array(
				"today"=>array('$gte'=>$time1,'$lte'=>$time2),
			);
		}
		if($channel_id){
			$q["channel_id"] = intval( $channel_id);
		}
		if($mobile_platforms && $mobile_platforms != "All"){
			$q["mobile_platforms"] = $mobile_platforms;
		}
//		print_r($q);
		// new_aggregate($table,$groupkey,$group=false,$countkeys=false,$query=false,$show=false,$sort=false,$skip=0,$limit=0,$mtype=1)
		$r = $this->phpmongo->new_aggregate("log_two_login",array("today"),false,array("today_login_count",
					  "todayloginmax5",
					  "todayloginmax30",
					  "today_pay_count30",
					  "activation",
					  "pay_number",
					  "total_LT",
					  "todayloginmax15",
					  "pay_money",
					  "reg_pay_number_people",
					  "activation_login",
					  "todayloginmax7",
					  "today_reg",
					  "today_pay_count7",
					  "pay_number_people",
					  "today_pay7",
					  "reg_pay_money",
					  "todayloginmax6",
					  "reg_pay_number",
					  "todayloginmax2",
					  "todayloginmax3",
					  "todayloginmax4",
					  "today_pay30"),$q, false, array('today'=>-1), 0, 0, 2);

		$run = array();
		foreach($r as $k => $value){
			$value["today"]=$value["_id"]["today"];
			$run[] = $value;
		}
//		print_r($run);
		return $run;
	}
	
	//首付道具
	  function ShoufuProps($time1,$time2,$channel_id,$mobile_platforms){
		
		
		$query = array();
		if($time1){
			
			$time1 = strtotime($time1." 00:00:00");
			if(!isset($query["time"])){
				$query["time"]=array();
			}
			$query["time"]['$gte']=$time1;
		}
		if($time2){
			$time2 = strtotime($time2." 23:59:59");
			if(!isset($query["time"])){
				$query["time"]=array();
			}
			$query["time"]['$lte']=$time2;
		}
		if($channel_id){
			$query["channel_id"]=$channel_id;
		}
		if($mobile_platforms){
			$query["mobile_platforms"]=$mobile_platforms;
		}
//		echo 'aaaaa';
//		var_dump($query);
//		return;
		
		$group=array("time"=>array('$min'=>'$time'),"goods_ext_id"=>array('$first'=>'$goods_ext_id'));
		$log_market_buy=$this->phpmongo->new_aggregate("log_market_buy",array("user_id"),$group,false,$query,false,false,0,0,1);
		$newdata = array("max"=>count($log_market_buy),"list"=>array());
		foreach($log_market_buy as  $value){
			$name = dbarr("SELECT base_goods.name FROM base_goods_ext left join base_goods on base_goods_ext.goods_ext_id = base_goods.id where base_goods_ext.goods_ext_id=".$value["goods_ext_id"]);
			if($name){
				$name = $name[0]["name"];
			}
//			print_r($value);
			$newdata["list"][$value["goods_ext_id"]]["num"]+=1;
			$newdata["list"][$value["goods_ext_id"]]["name"] = $name;
		}
//		print_r($newdata);
		
		return $newdata;
	}
	
	//首付等级
	  function ShoufuLv($time1,$time2,$mobile_platforms){
 		$query = array();
//		if($time1){
//			$time1 = strtotime($time1." 00:00:00");
//			if(!isset($query["ts"])){
//				$query["ts"]=array();
//			}
//			$query["ts"]['$gte']=$time1;
//		}
//		if($time2){
//			$time2 = strtotime($time2." 23:59:59");
//			if(!isset($query["ts"])){
//				$query["ts"]=array();
//			}
//			$query["ts"]['$lte']=$time2;
//		}
 
		if($mobile_platforms && $mobile_platforms != "All"){
			$query["mobile_platforms"] = $mobile_platforms;
		}
		$group=array("ts"=>array('$min'=>'$ts'),"lv"=>array('$first'=>'$lv'));
		$log_pay=$this->phpmongo->new_aggregate("log_pay",array("openid"),$group,false,$query,false,false,0,0,1);
//		print_r($log_pay);
		$newdata=array("max"=>count($log_pay),"list"=>array());
		foreach($log_pay as  $value)
		{
			$newdata["list"][$value["lv"]]+=1;
		}
//		print_r($newdata);
		return $newdata;
	}
	
	//游戏时长首付
	  function ShoufuGameTime($time1,$time2,$mobile_platforms){
	  	
 		$query = array();
		if($time1){
			$time1 = strtotime($time1." 00:00:00");
			if(!isset($query["ts"])){
				$query["ts"]=array();
			}
			$query["ts"]['$gte']=$time1;
		}
		if($time2){
			$time2 = strtotime($time2." 23:59:59");
			if(!isset($query["ts"])){
				$query["ts"]=array();
			}
			$query["ts"]['$lte']=$time2;
		}
		if($mobile_platforms && $mobile_platforms != "All"){
			$query["mobile_platforms"] = $mobile_platforms;
		}
		
//		print_r($query);
		$group=array("ts"=>array('$min'=>'$ts'),"game_time"=>array('$first'=>'$game_time'));
		$log_pay=$this->phpmongo->new_aggregate("log_pay",array("openid"),$group,false,$query,false,false,0,0,1);
		$newdata=array("max"=>count($log_pay),"list"=>array());
		
//		print_r($log_pay);
		foreach($log_pay as  $value)
		{
			$newdata["list"][$value["game_time"]]+=1;
		}
//		print_r($newdata);
		return $newdata;
	}
	
	function revenue_analysis($time1,$time2,$select_platform)
	{
// 		$oneday = 24 * 60 * 60;
// 		$time1 = strtotime($time1." 00:00:00");
// 		$time2 = strtotime($time2." 23:59:59");
// 		$arr_ret = array();
// 		for($time=$time1; $time<$time2; ) {
// 			if ($time + $oneday -1 <= $time2) {
// 				$query=array("ts"=>array('$gte'=>$time,'$lt'=>$time+$oneday));
			
// 				$oarr =$this->phpmongo->find("log_pay",$query,array("money"=>true, "openid"=>true));
// 				$date1=date('Y-m-d', $time);
// 				$arr=array();
// 				$pay_info = array();
// 				$money = 0;
// 				foreach ($oarr as $id => $value) {
// 					$arr[]=$value["openid"];
// 					$pay_info[0] += $value["money"];   #收入
// 				}
// 				$pay_info[1] = count($arr); #付费次数
// 				if (count($arr)>1) {
// 					$arr=array_unique($arr);
// 				}
// 				$pay_info[2] = count($arr); #付费人数
				
// 				$arr_ret[$date1] = $pay_info;
// 			}
// 			$time = $time+$oneday;
// 		}
		
// 		return $arr_ret;
		
 		
// 		$countkeys = array("pay_money", "pay_number_people", "pay_number", "today_reg","reg_pay_number_people", "today_pay_count7","today_pay_count30");
// 		$revenue_info = $this->phpmongo->aggregate("log_two_login",array("today"),$countkeys,$query,false,false,0,0,0);

 		if ($select_platform == 'ios') {
 			$mobile_platforms_d ="Ios";
 			$mobile_platforms_x = "ios";
 		} elseif ($select_platform == 'android') {
 			$mobile_platforms_d ="Android";
 			$mobile_platforms_x = "android";
 		} else {
 			$mobile_platforms_d ="";
 		}
 		
		$oneday = 24 * 60 * 60;
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		$arr_ret = array();
		for($time=$time1; $time<$time2; ) {
			if ($time + $oneday -1 <= $time2) {
				$time_s = $time;
				$time_e = $time+$oneday-1;
				if ($mobile_platforms_d != "") {
					$query = array("time"=>array('$gte'=>$time_s,'$lte'=>$time_e),"mobile_platforms"=>$mobile_platforms_d);
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e),"mobile_platforms"=>$mobile_platforms_x);
				} else {
					$query = array("time"=>array('$gte'=>$time_s,'$lte'=>$time_e));
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e));
				}
				$arr=array();
				$pay_log=$this->phpmongo->find("log_pay",$query_x);
				$data = array();
				$rundata=array();
				
				foreach($pay_log as $key=>$value)
				{
					$openid = $value["openid"];
					if (array_key_exists($openid, $rundata))
						$rundata[$openid] += $value["money"];
					else 
						$rundata[$openid] = $value["money"];
				}
				
				$pay_money = 0;
				foreach($rundata as $key=>$val)
				{
					$pay_money += $val;
				}
			
				$pay_times = $this->phpmongo->ret_count("log_pay", $query_x);
				
				#今日活跃人数，7日活跃人数，30日活跃人数
				$time_s_7 =  $time_s - (6*86400);
				$time_s_30 = $time_s - (29*86400);
				if ($mobile_platforms_d != "") {
					$query1 = array("time"=>array('$gte'=>$time_s_7,'$lt'=>$time_e), "mobile_platforms"=>$mobile_platforms_d);
					$query2 = array("time"=>array('$gte'=>$time_s_30,'$lt'=>$time_e), "mobile_platforms"=>$mobile_platforms_d);
				} else {
					$query1 = array("time"=>array('$gte'=>$time_s_7,'$lt'=>$time_e));
					$query2 = array("time"=>array('$gte'=>$time_s_30,'$lt'=>$time_e));
				}
				$todaylogin_arr = $this->phpmongo->find("log_two_login", $query);
				$todaylogin7_arr =  $this->phpmongo->find("log_two_login", $query1);
				$todaylogin30_arr = $this->phpmongo->find("log_two_login", $query2);
				$todaylogin = $this->get_login_num($todaylogin_arr);
				$todaylogin7 = $this->get_login_num($todaylogin7_arr);
				$todaylogin30 = $this->get_login_num($todaylogin30_arr);
				
				#今日付费人数， 7日付费人数，30日付费人数
				$time_s = $time_s_7;
				if ($mobile_platforms_d != "") {
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e),"mobile_platforms"=>$mobile_platforms_x);
				} else {
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e));
				}
				$pay_log7=$this->phpmongo->find("log_pay",$query_x);
				
				$time_s = $time_s_30;
				if ($mobile_platforms_d != "") {
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e),"mobile_platforms"=>$mobile_platforms_x);
				} else {
					$query_x = array("ts"=>array('$gte'=>$time_s,'$lte'=>$time_e));
				}
				$pay_log30=$this->phpmongo->find("log_pay",$query_x);
				
				$todaypay = count($rundata);
				$rundata = array();
				foreach($pay_log7 as $key=>$value)
				{
					$openid = $value["openid"];
					$rundata[$openid] = $openid;
				}
				$todaypay7 = count($rundata);
				$rundata = array();
				foreach($pay_log30 as $key=>$value)
				{
					$openid = $value["openid"];
					$rundata[$openid] = $openid;
				}
				$todaypay30 = count($rundata);
				
				$date1=date('Y-m-d', $time);
				$arr["pay_money"] = $pay_money;
				$arr["today_pay"] = $todaypay;
				$arr["pay_times"] = $pay_times;
				if ($time + $oneday -1 == $time2) { #如果最后一天为今天，另算活跃人数
					$this_date = getdate();
					$later_date = getdate(strtotime($time2." 00:00:00"));
					$time_s = strtotime($time2." 00:00:00");
					$time_e = strtotime($time2." 23:59:59");
					if(($last_date['year']===$this_date['year'])&&($this_date['yday']===$last_date['yday'])){#包含今天
						if ($mobile_platforms_d != "") {
							$query = array("ontime"=>array('$gte'=>$time_s,'$lt'=>$time_e), "mobile_platforms"=>$mobile_platforms_d);
						} else {
							$query = array("ontime"=>array('$gte'=>$time_s,'$lt'=>$time_e));
						}
						$todaylogin = $this->phpmongo->ret_count("user_base", $query);
						$todaylogin7 += $todaylogin;
						$todaylogin30 += $todaylogin;
					}
				}
				
				$arr["pay_1"] = $todaylogin ? $todaypay/$todaylogin : 0;
				$arr["pay_7"] = $todaylogin7 ? $todaypay7/$todaylogin7 : 0;
				$arr["pay_30"] = $todaylogin30 ? $todaypay30/$todaylogin30 : 0;
				//print_r($arr);
				$arr_ret[$date1] = $arr;
			}
			$time = $time+$oneday;
		}
		
		return  $arr_ret;
	}
	
	function get_login_num($todaylogin_arr) {
		$todaylogin = 0;
		foreach($todaylogin_arr as $key=>$value) {
			$todaylogin += $value["today_login_count"];
		}
		
		return  $todaylogin;
	}
	
	#资源消耗
	function resource_consumption($time1,$time2,$mobile_platforms, $type, $level_start, $level_end)
	{
		$time1=strtotime($time1." 00:00:00");
		$time2=strtotime($time2." 23:59:59");

		if($type == 1)
		{
			$query1=array("money_type"=>"diamond","e"=>"-","user_lv"=>array('$gte'=>$level_start,'$lte'=>$level_end),"time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}elseif ($type == 2){
			$query1=array("money_type"=>"gold","e"=>"-","user_lv"=>array('$gte'=>$level_start,'$lte'=>$level_end),"time"=>array('$gte'=>$time1,'$lte'=>$time2));
		} elseif ($type == 3) {
			$query1=array("time"=>array('$gte'=>$time1,'$lte'=>$time2));
		} elseif ($type == 4) {
			$query1=array("e"=>"-","time"=>array('$gte'=>$time1,'$lte'=>$time2));
			$query2=array("e"=>"+","time"=>array('$gte'=>$time1,'$lte'=>$time2));
		}
		
		if($mobile_platforms && $mobile_platforms != "0"){
			$query1["mobile_platforms"] = $mobile_platforms;
		}

		$arr_ = array();
		$arr_money = array();
		$arr_num = array();
		if ($type == 1 or $type == 2) {
			$money=dbarr("SELECT * FROM `base_module_sub` WHERE 1");
			$newdata=array();
			foreach ($money as $key=>$value)
			{
				$newdata[$value["id"]]=$value["sub_module_name"];
			}
			
			$oarr =$this->phpmongo->find("log_money_act_count" ,$query1)->sort(array("module_sub_id"=>1));
			
			foreach ($oarr as $key=>$value)
			{
				if (array_key_exists($value["module_sub_id"], $arr_money)) {
					$arr_["money"] += $value["money"];
					$arr_["times"] += 1;
					if (!array_key_exists($value["user_id"], $arr_num)) {
						$arr_num[$value["user_id"]] = 1;
						$arr_["user_num"] += 1;
					}
				} else {
					$arr_ = array();
					$arr_num = array();
					$arr_["money"] = $value["money"];
					$arr_["times"] = 1;
					$arr_num[$value["user_id"]] = 1;
					$arr_["user_num"] = 1;
				}
				$arr_money[$value["module_sub_id"]] = $arr_;
			}
			
			return array($arr_money,$newdata);
		} elseif ($type == 3) { #商城消耗
			$db_goods_id = dbarr("SELECT goods_base_id ,goods_ext_id FROM `base_goods_ext` ");
			$goods_ary = dbarr("SELECT id, name FROM base_goods ");
			$name_arr = array();
			$name_list = array();
			foreach ($goods_ary as $key=>$value) {
				$name_arr[$value["id"]] = $value["name"];
			}
			foreach ($db_goods_id as $key=>$value) {
				$name_list[$value["goods_ext_id"]] = $name_arr[$value["goods_base_id"]];
			}
			
			$oarr =$this->phpmongo->find("log_market_buy" ,$query1)->sort(array("goods_ext_id"=>1));
			foreach ($oarr as $key=>$value)
			{
				//$name = $this->get_goods_name($value["goods_ext_id"]);
				if (array_key_exists($value["goods_ext_id"], $arr_money)) {
					$arr_["money"] += $value["money"];
					$arr_["sum"]   += $value["sum"];
					$arr_["times"] += 1;
					if (!array_key_exists($value["user_id"], $arr_num)) {
						$arr_num[$value["user_id"]] = 1;
						$arr_["user_num"] += 1;
					}
				} else {
					$arr_ = array();
					$arr_num = array();
					$arr_["money"] = $value["money"];
					$arr_["sum"]   = $value["sum"];
					$arr_["times"] = 1;
					$arr_num[$value["user_id"]] = 1;
					$arr_["user_num"] = 1;
				}
				
				$arr_money[$value["goods_ext_id"]] = $arr_;
			}
			
			return array($arr_money, $name_list);
		} elseif ($type == 4) {
			$db_goods_id = dbarr("SELECT goods_base_id ,goods_ext_id FROM `base_goods_ext` ");
			$goods_ary = dbarr("SELECT id, name FROM base_goods ");
			$name_arr = array();
			$name_list = array();
			foreach ($goods_ary as $key=>$value) {
				$name_arr[$value["id"]] = $value["name"];
			}
			foreach ($db_goods_id as $key=>$value) {
				$name_list[$value["goods_ext_id"]] = $name_arr[$value["goods_base_id"]];
			}
			
			$oarr1 =$this->phpmongo->find("log_goods_act" ,$query1)->sort(array("goods_ext_id"=>1));
			$oarr2 =$this->phpmongo->find("log_goods_act" ,$query2)->sort(array("goods_ext_id"=>1));
			
			foreach ($oarr1 as $key=>$value)
			{
				//$name = $this->get_goods_name($value["goods_ext_id"]);
				if (array_key_exists($value["goods_ext_id"], $arr_money)) {
					$arr_["x_sum"]   += $value["max"];
					if (!array_key_exists($value["user_id"], $arr_num)) {
						$arr_num[$value["user_id"]] = 1;
						$arr_["x_user_num"] += 1;
					}
				} else {
					$arr_ = array();
					$arr_num = array();
					$arr_["x_sum"]   = $value["max"];
					$arr_num[$value["user_id"]] = 1;
					$arr_["x_user_num"] = 1;
					$arr_["c_sum"]   =0;
					$arr_["c_user_num"] = 0;
				}
				$arr_money[$value["goods_ext_id"]] = $arr_;
			}
			foreach ($oarr2 as $key=>$value)
			{
				//$name = $this->get_goods_name($value["goods_ext_id"]);
				if (array_key_exists($value["goods_ext_id"], $arr_money)) {
					$arr_["c_sum"]   += $value["max"];
					if (!array_key_exists($value["user_id"], $arr_num)) {
						$arr_num[$value["user_id"]] = 1;
						$arr_["c_user_num"] += 1;
					}
				} else {
					$arr_ = array();
					$arr_num = array();
					$arr_["c_sum"]   = $value["max"];
					$arr_num[$value["user_id"]] = 1;
					$arr_["c_user_num"] = 1;
				}
				$arr_money[$value["goods_ext_id"]] = $arr_;
			}
			
			return array($arr_money, $name_list);
		}
		
	}
	
	function getChannel()
	{
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo=openmongodb($tmp_mongo,"all_user",$operators_id);
		$rsl = $tmp_mongo->find("channel")->sort(array("id"=>-1));
		$c = array();
		foreach($rsl as $k => $value){
			$c[] = $value;
		}
		return $c;
	}
	
	function recharge_ranking($time1,$time2,$channel_id) 
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
		
		if ($channel_id != "All") {
			$query_x = array("ts"=>array('$gte'=>$time1,'$lte'=>$time2),"channel_id"=>intval($channel_id));
		} else {
			$query_x = array("ts"=>array('$gte'=>$time1,'$lte'=>$time2));
		}
		$valuelist=array();
		$group = array("ts"=>array('$max'=>'$ts'), "money"=>array('$sum'=>'$money'));
		$rech_arr = $this->phpmongo->new_aggregate("log_pay",array("openid"),$group,array("timecount"),$query_x,false,false,0,0,1);
		$tmp_sort = array();
		$total_money=0;
		foreach ($rech_arr as $key=>$value) {
			//$valuelist[$i]["openid"] = $value["_id"]["openid"];
			$tmp_sort[$value["_id"]["openid"]] = $value["money"];
			$total_money += $value["money"];
			
			$valuelist[$value["_id"]["openid"]]["ts"] = $value["ts"];
			$valuelist[$value["_id"]["openid"]]["money"] = $value["money"];
			$valuelist[$value["_id"]["openid"]]["times"] = $value["timecount"];
			
			$user_base=$this->phpmongo->findone("user_base",array("user_name"=>$value["_id"]["openid"]));
			$user_info=$this->phpmongo->findone("user_info",array("base_user_id"=>$user_base["id"]), array("nickname"=>true, "user_lv"=>true));
			if(count($user_info)){
				$valuelist[$value["_id"]["openid"]]["nickname"] = $user_info["nickname"];
				$valuelist[$value["_id"]["openid"]]["user_lv"] = $user_info["user_lv"];
			} else {
				$valuelist[$value["_id"]["openid"]]["nickname"] = "";
				$valuelist[$value["_id"]["openid"]]["user_lv"] = 0;
			}
		}
		
		arsort($tmp_sort);
		$alert_arr = array();
		$ind = 0;
		foreach ($tmp_sort as $key=>$value) {
			if ($ind < 20) {
				$user_base=$this->phpmongo->findone("user_base",array("user_name"=>$keys), array("downtime"=>true));
				$time_b = strtotime(strval(date("Y-m-d",$user_base["downtime"]))." 00:00:00");
				$time_n = strtotime(strval(date("Y-m-d",time()))." 00:00:00");
				if ((time_b + 3*24*3600) >=  $time_n) {
					$alert_arr[$key] = 1;
				} else {
					$alert_arr[$key] = 0;
				}
			} else 
				break;
			
			$ind++;
		}
		
		return array($valuelist, $tmp_sort, $alert_arr, $total_money);
	}
	
	function recharge_money_analyze($time1,$time2,$channel_id)
	{
		$time1 = strtotime($time1." 00:00:00");
		$time2 = strtotime($time2." 23:59:59");
	
		if ($channel_id != "All") {
			$query_x = array("ts"=>array('$gte'=>$time1,'$lte'=>$time2),"channel_id"=>intval($channel_id));
		} else {
			$query_x = array("ts"=>array('$gte'=>$time1,'$lte'=>$time2));
		}
		$valuelist=array();
		$total_num = 0;
		$rech_arr = $this->phpmongo->aggregate("log_pay",array("openid"),array("money"),$query_x,false,false,0,0,0);
		$valuelist["1~5"] = 0;
		$valuelist["6~49"] = 0;
		$valuelist["50~99"] = 0;
		$valuelist["100~199"] = 0;
		$valuelist["200~499"] = 0;
		$valuelist["500~999"] = 0;
		$valuelist["1000~1999"] = 0;
		$valuelist["2000~4999"] = 0;
		$valuelist["5000~9999"] = 0;
		$valuelist["10000~19999"] = 0;
		$valuelist["20000以上"] = 0;
		foreach ($rech_arr as $key=>$value) {
			$total_num++;
			if ($value["money"] >=1 and $value["money"] <=5) {
					$valuelist["1~5"] += 1;
			} elseif ($value["money"] >=6 and $value["money"] <=49) {
					$valuelist["6~49"] += 1;
			} elseif ($value["money"] >=50 and $value["money"] <=99) {
					$valuelist["50~99"] += 1;
				
			} elseif ($value["money"] >=100 and $value["money"] <=199) {
					$valuelist["100~199"] += 1;
			
			} elseif ($value["money"] >=200 and $value["money"] <=499) {
					$valuelist["200~499"] += 1;
				
			} elseif ($value["money"] >=500 and $value["money"] <=999) {
					$valuelist["500~999"] += 1;
				
			} elseif ($value["money"] >=1000 and $value["money"] <=1999) {
					$valuelist["1000~1999"] += 1;
				
			} elseif ($value["money"] >=2000 and $value["money"] <=4999) {
					$valuelist["2000~4999"] += 1;
				
			}elseif ($value["money"] >=5000 and $value["money"] <=9999) {
					$valuelist["5000~9999"] += 1;
			
			} elseif ($value["money"] >=10000 and $value["money"] <=19999) {
					$valuelist["10000~19999"] += 1;
			
			} elseif ($value["money"] >=20000 ) {
			 		$valuelist["20000以上"] += 1;
			}
		}
		
	
		return array($valuelist, $total_num);
	}
}