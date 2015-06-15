<?php
namespace plugins\user_query;
use controllers\controllers as CONT;
use kernel\base\sql\nosql\mongo as TMP_MONGO;


class user_query{
	
	//玩家查询
	function queryPlayer($obj){
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$queryPlayer = $cont->obj->queryPlayer($post);
		
		require_once parse_tmp("/user_query/queryPlayer.html");//引入模版
	}
	
	//物品查询
	function goods_query($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$goods_query=array();
		if(isset($post["nickname"])){
			$goods_query=$cont->obj->goods_query($post);
		}
		
		require_once parse_tmp("/user_query/goods_query.html");//引入模版
	}
	
	//玩家消费明细
	function consumer_query($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$consumer_query=array();
		if(isset($post["nickname"])){
			$consumer_query=$cont->obj->consumer_query($post);
			$module_name_list=$cont->obj->module_name_list();
		}
		
		require_once parse_tmp("/user_query/consumer_query.html");//引入模版
	}
	
	//vip　分布
	function vip_query($obj){
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$vip_query=$cont->obj->vip_query($post);
		$c = $cont->obj->getChannel(); 
		require_once parse_tmp("/user_query/vip_query.html");//引入模版
	}
	
	//钻石结余
	function diamond_query($obj){
//		print_r("aaa");
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$diamond_query=$cont->obj->diamond_query($post);
//		var_dump($diamond_query);
		$c = $cont->obj->getChannel();
		require_once parse_tmp("/user_query/diamond_query.html");//引入模版
	}
	
	
	//军团队员列表
	function league_list_query($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$league_list_query=array();
		if(isset($post["leaguename"])){
			$league_list_query=$cont->obj->league_list_query($post);
		}
		
		require_once parse_tmp("/user_query/league_list_query.html");//引入模版
	}
	
	//军团战情况
	function league_war_e($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$league_war_e=array();
		$league_war_e=$cont->obj->league_war_e();
		require_once parse_tmp("/user_query/league_war_e.html");//引入模版
	}
	
	//每日操作日志
	function log_query($obj)
	{
	}
	
	//模块更新查询
	function module_update_query($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$league_war_e=array();
		$module_list=array("过关斩将","");
		$up_hotel=$up_hotel_time=$sign=$salary_time=$diamond_time=$today_act_count_time=0;
		if(isset($post["user_name"])){
			$module_update_query=$cont->obj->module_update_query($post);
			if($module_update_query==false){echo "没有此用户";exit();}
			$module_list_name=array();
			
			$elite=$module_update_query["ext"]["elite"];
			$elite=arrayed($elite);

			$module_list_name["过关斩将"]=array();
			foreach((array)$elite as $key=>$value)
			{
				$base2_map2=dbarr("SELECT * FROM `base2_map2` WHERE `type`=2 and `order`=$key");
				$module_list_name["过关斩将"][]=array("time"=>$value[0],"status"=>$value[1],"id"=>$base2_map2[0]["info"]);
			}
			$today_act_count_time=$module_update_query["ext"]["today_act_count_time"];
			$diamond_time=$module_update_query["ext"]["diamond_time"];
			$salary_time=$module_update_query["ext"]["salary_time"];
			$sign=arrayed($module_update_query["ext"]["sign"]);
			$sign=date("Ymd",$sign[1][0]);
			$up_hotel_time=arrayed($module_update_query["ext"]["up_hotel_time"]);
			$up_hotel=arrayed($module_update_query["ext"]["up_hotel"]);
			
			$energy_time=arrayed($module_update_query["ext"]["energy_time"]);
		}
	
		/*
		print_r($module_update_query["ext"]["today_act_count_time"]);
		print_r($module_update_query["ext"]["today_act_count"]);
		print_r($module_update_query["ext"]["today_criends_count"]);
		print_r($module_update_query["ext"]["elite"]);
		
		print_r($module_update_query["ext"]["up_hotel"]);
		print_r($module_update_query["ext"]["up_hotel_time"]);
		*/
		require_once parse_tmp("/user_query/module_update_query.html");//引入模版
	}
	
	//综合分析
	function userdate($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$userdate=array(array(),array(),array());
		if(isset($post["time1"])){
			$userdate=$cont->obj->userdate($post);
		}
		require_once parse_tmp("/user_query/userdate.html");//引入模版
	}
	
	//登录查询
	function login_award($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$login_award=array();
		$riqi=0;
		if(isset($post["nickname"])){
			$tmplogin_award=$cont->obj->login_award($post);
			$login_award=$tmplogin_award["up_login_award"];
			$riqi=$tmplogin_award["up_login_award_time"];
		}
		require_once parse_tmp("/user_query/login_award.html");//引入模版
	}
	
	//玩家资源日志
	function res_ori($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		$module_name_list=$res_ori=array();
		if(isset($post["user_name"])){
			$module_name_list=$cont->obj->module_name_list();
			$res_ori=$cont->obj->res_ori($post);
		}
		require_once parse_tmp("/user_query/res_ori.html");//引入模版
	}
	
	//邀请玩家
	function invite_user($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user_query");
		$post=$obj->post;
		if(isset($post["user_name"])){
			$invite_user=$cont->obj->invite_user($post["user_name"]);
		}else{
			$invite_user=array();
		}
		require_once parse_tmp("/user_query/invite_user.html");//引入模版
	}
	
	//活跃
	function user_active($obj)
	{
		$post=$obj->post; 
		$time1=isset($post["time1"])?$post["time1"]:0;
		$time2=isset($post["time2"])?$post["time2"]:0;
		
		$dataurl2="/?module=user_query&methods=user_active_count";
		if($time1 and $time2)
		{
			$dataurl2.="&time1=".$time1."&time2=".$time2;
		}
		
		$showpie3=open_flash_chart_object_str("100%",600,$dataurl2);
		 require_once parse_tmp("/user_query/user_active.html");//引入模版
	}
	
	//活跃
	function user_active1($obj)
	{
		$dataurl2="/?module=user_query&methods=user_active_count_data";
		
		$user_active_count_data=open_flash_chart_object_str("100%",600,$dataurl2);
		 require_once parse_tmp("/user_query/user_active_1.html");//引入模版
	}
	//最近７日活跃
	function user_active2($obj)
	{
		
		$time1 = date("Y-m-d",time()-24*60*60*7);
		$time2 = date("Y-m-d",time());
		$dataurl2="/?module=user_query&methods=user_active_count_data2&time1=".$time1."&time2=".$time2;
		$user_active_count_data=open_flash_chart_object_str("50%",250,$dataurl2);
		require_once parse_tmp("/user_query/user_active_1.html");//引入模版
	}
	
	
	
	
	function Honline($time1,$time2)
	{
		$time1=str_replace("-","",$time1);
		$time2=str_replace("-","",$time2);
		
		$cont=new CONT("user");
		 
		$user_lv_count=$cont->obj->dateonline(intval($time1),intval($time2));
		$onlinelist=array();
		foreach($user_lv_count as $key=>$value)
		{
			$_max=0;
			foreach ($value["ip"] as $v)
			{
				$_max+=$v;
			}
			
			if(isset($onlinelist[$value["date"]])==false)$onlinelist[$value["date"]]=array();
			
			if(isset($onlinelist[$value["date"]][$value["H"]])==false)$onlinelist[$value["date"]][$value["H"]]=0;
			$onlinelist[$value["date"]][$value["H"]]+=$_max;
		}
		return $onlinelist;
	}
	
	function user_active_count($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '用户活跃');
		
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$time1=isset($get["time1"])?$get["time1"]:date("Y-m-d",time());
		$time2=isset($get["time2"])?$get["time2"]:date("Y-m-d",time());
		
		//注册数据
		$user_list=$cont->obj->regtimelist($time1,$time2);
		
		//登录数据
		$logintimelist=$cont->obj->logintimelist($time1,$time2);
		
		//在线数据
		$Honline=$this->Honline($time1,$time2);
		
		$reg_user_list=array();
		foreach($user_list as $key=> $value)
		{
			$tmp_time=substr($value["H"],0,8);
			if(isset($reg_user_list[$tmp_time])==false)
			{
				$reg_user_list[$tmp_time]=array();
			}
			if(isset($reg_user_list[$tmp_time][$key])==false)$reg_user_list[$tmp_time][$key]=0;
			$reg_user_list[$tmp_time][$key]+=$value["total"];
		}


		$zdata=array();
		foreach($logintimelist as $key=>$value)
		{
			foreach($value as $k=>$v)
			{
				$resc=(isset($reg_user_list[$key])&&$reg_user_list[$key][$k])?$reg_user_list[$key][$k]:0;
				if(isset($zdata[$key])==false)$zdata[$key]=array();
				if(isset($zdata[$key][$k])==false)$zdata[$key][$k]=0;
				$zdata[$key][$k]=$v-$resc;
			}
		}
				
		ksort($zdata);

		$arr1=array();
		$arr2=array();
		$arr_lb=array();
		$max=0;
		$min=0;
		$max_lv=0;

		foreach($zdata as $key=>$value)
		{
			ksort($value);
			$arr1[$key]=array();
			$arr_lb[$key]=array();
			foreach($value as $k=>$v)
			{
				if(isset($Honline[$key][$k])==false)$Honline[$key][$k]=0;
				$arr1[$key][]=$v;
				$arr2[$key][]=$Honline[$key][$k];
				$arr_lb[$key][]=$k."点";
				if($Honline[$key][$k]>$v)$v=$Honline[$key][$k];
				if($v>$max)
				{
					$max=$v;
				}
				
				if($v<$min)
				{
					$min=$v;
				}
			}
			
		}
		
		$maxld=array();
		foreach($arr1 as $key=>$value)
		{
			$line_dot[$key] = new \OFC_Charts_Line();
			$line_dot[$key]->set_values( $value );
			$line_dot[$key]->set_colour('#0066FF');
			$line_dot[$key]->set_key($key."日活跃",16);
			if(count($arr_lb[$key])>count($maxld))$maxld=$arr_lb[$key];
			
		}
		
		foreach($arr2 as $key=>$value)
		{
			$line_dot1[$key] = new \OFC_Charts_Line();
			$line_dot1[$key]->set_values( $value );
			$line_dot1[$key]->set_colour('#f066FF');
			$line_dot1[$key]->set_key($key."日在线",16);
		}
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($maxld);
				
		$y=new \OFC_Elements_Axis_Y();
		$b=ceil(($max+15)/15)<15?15:ceil(($max+15)/15);
		$y->set_range($min,$max+15,$b);
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		foreach($line_dot as $key=>$value)
		{
			$chart->add_element( $value );
		}
		
		foreach($line_dot1 as $key=>$value)
		{
			$chart->add_element( $value );
		}
		echo $chart->toPrettyString();
	  }
	  
	function user_active_count_data($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '用户活跃：'.date("Y-m-d H:i") );
		
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$time1=isset($get["time1"])?$get["time1"]:0;
		$time2=isset($get["time2"])?$get["time2"]:0;

		//注册数据
		$reg_user_list=$cont->obj->regtimelist($time1,$time2);
		
		//登录数据
		$logintimelist=$cont->obj->logintimelist1($time1,$time2);
		
		$new_reg_user_list=array();
		foreach($reg_user_list as $value)
		{
			$tmp_time=substr($value["H"],0,8);
			if(isset($new_reg_user_list[$tmp_time])==false)
			{
				$new_reg_user_list[$tmp_time]=0;
			}
			$new_reg_user_list[$tmp_time]+=$value["total"];
		}
	
		$zdata=array();
		foreach($logintimelist as $key=>$value)
		{
	
			$resc=(isset($new_reg_user_list[$key]))?$new_reg_user_list[$key]:0;
			$zdata[$key]=$value-$resc;
			
		}
	
		ksort($zdata);

		$arr1=array();
		$arr_lb=array();
		$max=0;
		$min=0;
		$max_lv=0;
		
		foreach($zdata as $key=>$value)
		{
			$arr1[]=$value;
			$arr_lb[]=$key."日";
			
			if($value>$max)
			{
				$max=$value;
			}
			
			if($value<$min)
			{
				$min=$value;
			}
			
			
		}
		if(count($arr1)==0) {echo "{}";return;}
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#0066FF');
		$line_dot->set_key("日活跃",16);
		
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$b=ceil(($max+15)/50)<15?15:ceil(($max+15)/50);
		$y->set_range($min,$max+15,$b);
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);

		$chart->add_element( $line_dot );
		
		echo $chart->toPrettyString();
	  }

function user_active_count_data2($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 		$get=$obj->get;
		$time1=$get["time1"];
		$time2=$get["time2"];
		$title = new \OFC_Elements_Title( '7日用户活跃：');
		
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$time1=isset($get["time1"])?$get["time1"]:0;
		$time2=isset($get["time2"])?$get["time2"]:0;

		//注册数据
		$reg_user_list=$cont->obj->regtimelist($time1,$time2);
		
		//登录数据
		$logintimelist=$cont->obj->logintimelist1($time1,$time2);
		
		$new_reg_user_list=array();
		foreach($reg_user_list as $value)
		{
			$tmp_time=substr($value["H"],0,8);
			if(isset($new_reg_user_list[$tmp_time])==false)
			{
				$new_reg_user_list[$tmp_time]=0;
			}
			$new_reg_user_list[$tmp_time]+=$value["total"];
		}
	
		$zdata=array();
		foreach($logintimelist as $key=>$value)
		{
	
			$resc=(isset($new_reg_user_list[$key]))?$new_reg_user_list[$key]:0;
			$zdata[$key]=$value-$resc;
			
		}
	
		ksort($zdata);

		$arr1=array();
		$arr_lb=array();
		$max=0;
		$min=0;
		$max_lv=0;
		
		foreach($zdata as $key=>$value)
		{
			$arr1[]=$value;
			$arr_lb[]=date("Y-m-d", strtotime(strval($key)));
			
			if($value>$max)
			{
				$max=$value;
			}
			
			if($value<$min)
			{
				$min=$value;
			}
			
			
		}
		if(count($arr1)==0) {echo "{}";return;}
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#0066FF');
		$line_dot->set_key("7日活跃",16);
		
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$b=100;
		$y->set_range($min,$max+100,$b);
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);

		$chart->add_element( $line_dot );
		
		echo $chart->toPrettyString();
	  }


function user_acu_pcu($obj){
	
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( 'ACU&PCU');
		
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$time1=isset($get["time1"])?$get["time1"]:date("Ymd",time());
		$time2=isset($get["time2"])?$get["time2"]:date("Ymd",time());
		
		//获取acu pcu 数据
		$acuPcudata = $cont->obj->getAcuPcu($time1,$time2);
		$max = 0;
		$min = 999999;
		$acu=array();
		$pcu=array();
		$labels = array();
		$max = 0;
		foreach($acuPcudata as $k => $v){
			$acu[] = intVal($v["total"]/$v["count"]);
			$pcu[] = $v["max"];
			$labels[] =date("Y-m-d", strtotime(strval($k)));
			if($max < $v["max"]){
				$max = $v["max"];
			}
			if($min > $v["min"]){
				$min = $v["min"];
			}
		}
		if(count($acu)==0) {echo "{}";return;}
		$acu_dot = new \OFC_Charts_Line();
		$acu_dot->set_values( $acu );
		$acu_dot->set_colour('#f066FF');
		$acu_dot->set_key("ACU",16);
		
		$pcu_dot = new \OFC_Charts_Line();
		$pcu_dot->set_values( $pcu );
		$pcu_dot->set_colour('#0066FF');
		$pcu_dot->set_key("PCU",16);
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($labels);
				
		$y=new \OFC_Elements_Axis_Y();
		$b=ceil(($max+15)/15)<15?30:ceil(($max+15)/15);
		$y->set_range($min,$max+30,$b);
		$y->set_colour('#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);

		$chart->add_element( $acu_dot );
		$chart->add_element( $pcu_dot );
		
		echo $chart->toPrettyString();
}

	//玩家等级分布
	function userLvFb($obj){
		$post=$obj->post;
		$time1=$post["time1"];
		$time2=$post["time2"];
		$channel_id = $post["channel_id"];
		$dataurl2="/?module=user_query&methods=userLvFbData&time1=".$time1."&time2=".$time2."&channel_id=".$channel_id."&mobile_platforms=".$mobile_platforms;

//		echo $dataurl2;
		$cont=new CONT("user_query");
		$c = $cont->obj->getChannel(); 
		$userLvFb=open_flash_chart_object_str("50%",250,$dataurl2);
		require_once parse_tmp("/user_query/user_lv_fb.html");//引入模版
	}
	//玩家等级分布数据
	
	function userLvFbData($obj){
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("等级分布" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val# ' );
		
		$cont=new CONT("user_query");
		$get=$obj->get; 
		//获取acu pcu 数据
		$userLvFbData = $cont->obj->userLvFbData($get);
		
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		foreach ($userLvFbData as $k => $v){
			if($max<$v["num"]){
				$max = $v["num"];
			}
			if($min > $v["num"]){
				$min = $v["num"];
			}
			$data[] = $v["num"];
			$labels[] = $v["user_lv"];
		}

		$data1 = array();
		$index = count($data)-1;
 		for ($x=$index; $x>=0; $x--) {
 			$data1[] = $data[$x];
 		}
 		if(count($data1)==0) {echo "{}";return;}
		$hbar->set_values( $data1 );
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $hbar );
		
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset( false );
		$x->set_range( 0, ceil($max/50)*50);
		$x->set_steps( 50 );
		
		$chart->set_x_axis( $x );
		
		$y = new \OFC_Elements_Axis_Y();
		$y->set_offset( true );
		$y->set_labels( $labels );
		$chart->add_y_axis( $y );

		$tooltip = new \OFC_Tooltip();
		$tooltip->set_hover();
		$tooltip->set_stroke( 1 );
		$tooltip->set_colour( "#000000" );
		$tooltip->set_background_colour( "#ffffff" ); 
		$chart->set_tooltip( $tooltip );
		echo $chart->toPrettyString();
	}
	
	//流失分析
	function userLS($obj){
		$get=$obj->post;
		$channel_id = $get["channel_id"];
		$time2=$get["time2"];
		$mobile_platforms = $get["mobile_platforms"];
		$field=$get["field"];
		$dataurl2="/?module=user_query&methods=userLSData&channel_id=".$channel_id."&mobile_platforms=".$mobile_platforms."&field=".$field;
//		echo $dataurl2;
		$cont=new CONT("user_query");
		$c = $cont->obj->getChannel(); 
		$d = $cont->obj->getPlatforms();
		
		$userLvFb=open_flash_chart_object_str("50%",250,$dataurl2);
		require_once parse_tmp("/user_query/userLS.html");//引入模版
	}
	
	
	//流失分析 数据
	function userLSData($obj){
		$obj->_bug=0;
		
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("等级分布" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val#' );
		
		$cont=new CONT("user_query");
		$get=$obj->get; 
		
		///流失分析 数据
		$userLSData = $cont->obj->userLSData($get);
//		print_r($userLSData);
		
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		
		if($userLSData){
			foreach ($userLSData as $k => $v){
				if($max<$v){
					$max =$v;
				}
				if($min > $v){
					$min = $v;
				}
				$data[] = $v;
				$labels[] = $k;
			}
		}

		$data1 = array();
		$index = count($data)-1;
 		for ($x=$index; $x>=0; $x--) {
 			$data1[] = $data[$x];
 		}
 		
 		if(count($data1)==0) {echo "{}";return;}
 		
		$hbar->set_values( $data1 );
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $hbar );
		
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset( false );
		$x->set_range( 0, ceil($max/50)*50);
		$x->set_steps( 50 );
		
		$chart->set_x_axis( $x );
		
		$y = new \OFC_Elements_Axis_Y();
		$y->set_offset( true );
		$y->set_labels( $labels );
		$chart->add_y_axis( $y );
		
		
		$tooltip = new \OFC_Tooltip();
		$tooltip->set_hover();
		$tooltip->set_stroke( 1 );
		$tooltip->set_colour( "#000000" );
		$tooltip->set_background_colour( "#ffffff" ); 
		$chart->set_tooltip( $tooltip );
		echo $chart->toPrettyString();
	}
	//用户设备统计
	function mPlayerQuery($obj){
		$obj->_bug=0;
		$post=$obj->post;
		
		$cont=new CONT("user_query");
		$data = $cont->obj->mPlayerQuery($post);
		$vType = array(null=>"型号","mobile_model"=>"型号","mobile_platforms"=>"系统","mobile_resolution"=>"分辨率"); 
		
		require_once parse_tmp("/user_query/mPlayerQuery.html");//引入模版
		return;
		
		
		
		
		
		
		
		$dataurl2="/?module=user_query&methods=mPlayerQueryData&time1=".$time1."&mobile_platforms=".$mobile_platforms ;
//		echo $dataurl2;
		$cont=new CONT("user_query");
//		$c = $cont->obj->getChannel(); 
		$d = $cont->obj->getPlatforms();
		$mPlayerQueryData=open_flash_chart_object_str("50%",250,$dataurl2);
		require_once parse_tmp("/user_query/mPlayerQuery.html");//引入模版
	}
	//用户设备统计数据
	function mPlayerQueryData($obj){
		$obj->_bug=0;
		
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("等级分布" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val#' );
		
		$cont=new CONT("user_query");
		$get=$obj->get; 
		
		///流失分析 数据
		$userLSData = $cont->obj->mPlayerQuery($get);
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		
		if($userLSData){
			foreach ($userLSData as $k => $v){
				if($max<$v["mobile_activation"]){
					$max =$v["mobile_activation"];
				}
				if($min > $v["mobile_activation"]){
					$min = $v["mobile_activation"];
				}
				$data[] = $v["mobile_activation"];
				$labels[] = $v["mobile_model"];
			}
		}

		$data1 = array();
		$index = count($data)-1;
 		for ($x=$index; $x>=0; $x--) {
 			$data1[] = $data[$x];
 		}
 		
 		if(count($data1)==0) {echo "{}";return;}
 		
		$hbar->set_values( $data1 );
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $hbar );
		
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset( false );
		$x->set_range( 0, ceil($max/50)*50);
		$x->set_steps( 50 );
		
		$chart->set_x_axis( $x );
		
		$y = new \OFC_Elements_Axis_Y();
		$y->set_offset( true );
		$y->set_labels( $labels );
		$chart->add_y_axis( $y );
		
		
		$tooltip = new \OFC_Tooltip();
		$tooltip->set_hover();
		$tooltip->set_stroke( 1 );
		$tooltip->set_colour( "#000000" );
		$tooltip->set_background_colour( "#ffffff" ); 
		$chart->set_tooltip( $tooltip );
		echo $chart->toPrettyString();
	}
	
	
	
	
	
}
?>