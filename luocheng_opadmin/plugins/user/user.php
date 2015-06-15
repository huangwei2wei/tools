<?php
namespace plugins\user;
use controllers\controllers as CONT;
use kernel\base\sql\nosql\mongo as TMP_MONGO;

class user{
	//************************************************人物分布统计*********************************************//
	function user_map($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$user_map_list=$cont->obj->user_map_count();
		$user_main_map=$cont->obj->user_main_map_count();
		$base_map=$cont->obj->base_map();
		
		$retval=$user_map_list["retval"];
		$main_map=$user_main_map["retval"];
		$maxre=$user_map_list["count"]+$user_main_map["count"];
		require_once parse_tmp("/user/user_map.html");//引入模版
	}
	
	
	//************************************************统计在线人数*********************************************//
	function index($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post; 
		$time1=isset($post["time1"])?$post["time1"]:date("Y-m-d",time());
		$time2=isset($post["time2"])?$post["time2"]:date("Y-m-d",time());
		if(isset($post["time1"]) && $post["time1"]){
			$get_data=$cont->obj->get_data($time1,$time2);
		}else{
			$get_data=$cont->obj->get_data();
		}
		
		//用户在线总数
		$online_count=$cont->obj->on_user_sum();
		//总注册用户
		$reg_user_sum=$cont->obj->reg_user_sum();
		
		//总创号
		$c_user_sum=$cont->obj->c_user_sum();	
		require_once parse_tmp("/user/user_list.html");//引入模版
	}
	
	function bar($obj){
		
		$get=$obj->get; 
		$post=$obj->post; 
		$dataurl="/?module=test&methods=bar_count&type=".$get["type"]."&e=".$get["e"]."&time1=".trim($post["time1"])."&time2=".trim($post["time2"]);
		print($dataurl);
		$showpie3=open_flash_chart_object_str("100%",550,$dataurl);
		$time1=$post["time1"];
		$time2=$post["time2"];
		
		
		require_once parse_tmp("/test/test.html");//引入模版
		
		
	}
	
	
	
	function one($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$post=$obj->post;
	 
		//用户在线总数
		$online_count=$cont->obj->on_user_sum();
		//总注册用户
		$reg_user_sum=$cont->obj->reg_user_sum();
		//总激活用户
		$act_user_sum=$cont->obj->act_user_sum();
		
		$str_date = date('Y-m-d',time());
		$reg_user_sum_day=$cont->obj->user_list($str_date,1);
		$day_money = $cont->obj->recharge_line_day($str_date,$str_date);
		//７日活跃
		$time1 = date("Y-m-d",time()-24*60*60*7);
		$time2 = date("Y-m-d",time());
		$dataurl2="/?module=user_query&methods=user_active_count_data2&time1=".$time1."&time2=".$time2;
//		echo $dataurl2;
		$user_active_count_data=open_flash_chart_object_str("90%",200,$dataurl2);

		//acu pcu
		$time1 = date("Ymd",time()-24*60*60*10);
		$time2 = date("Ymd",time());
		$dataurl2="/?module=user_query&methods=user_acu_pcu&time1=".$time1."&time2=".$time2;
		$acu_pcu=open_flash_chart_object_str("90%",200,$dataurl2);
		
		//各数据
		$get_data=$cont->obj->get_data();
//		print_r($get_data);
		require_once parse_tmp("/user/one.html");//引入模版
	}
	
	//联运渠道数据统计
	function get_data1($obj){
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get;
		$post=$obj->post;
		
		$time1=false;
		$time2=false;
		$channel_id=false;
		$mobile_platforms=false;
		
		if(isset($post["time1"]) && isset($post["time2"]) && $post["time1"] && $post["time2"]){
			$time1 = $post["time1"];
			$time2 = $post["time2"];
		}
		if(isset($post["channel_id"])){
			if($post["channel_id"] !=  'All'){
				$channel_id = $post["channel_id"];
			}
		}
		if(isset($post["mobile_platforms"])){
			if($post["mobile_platforms"] !=  'All'){
				$mobile_platforms = $post["mobile_platforms"];
			}
		}
		$get_data=$cont->obj->get_data1($time1,$time2,$channel_id,$mobile_platforms);
		$cont=new CONT("user_query");
		$c = $cont->obj->getChannel();
		$d = $cont->obj->getPlatforms();
		
		
		require_once parse_tmp("/user/user_list_c.html");//引入模版
	}
	
	
	
	//************************************************统计等级*********************************************//
	
	function iuser_count($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get; 
		$post=$obj->post;
		$user_lv_list=$cont->obj->user_lv_count($post);
		require_once parse_tmp("/user/user_e.html");//引入模版
	}

	
     function show_pie3($obj)
	{
		$dataurl2="/?module=user&methods=user_count";
		$showpie3=open_flash_chart_object_str("100%",400,$dataurl2);
		 require_once parse_tmp("/user/dengji.html");//引入模版
	}
			
	function user_count($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '等级通过率：'.date("Y-m-d H:i") );
		
		$cont=new CONT("user");
		 
		$user_lv_list=$cont->obj->user_lv_count();

		$retval=$user_lv_list["retval"];
		
		$user_lv=array();
		for($i=0;$i<=100;$i++){
			$total=0;
			foreach($retval as $v)
			{
				if($v["lv"]==$i)
					$total+=$v['total'];
			}
			
			$user_lv[$i]=array("stop_count"=>$total);
		}
		
		$base=$cont->obj->reg_user_sum();
		$stop_count=$cont->obj->c_user_sum();
		$user_lv[0]["base"]=$cont->obj->reg_user_sum();
		$user_lv[0]["get_to"]=$cont->obj->c_user_sum();
		$user_lv[0]["stop_count"]=$user_lv[0]["base"]-$user_lv[0]["get_to"];
		for($i=1;$i<=100;$i++){
			$total1=0;
			foreach($retval as $v)
			{
				if($v["lv"]>$i)
					$total1+=$v['total'];
			}
			$user_lv[$i]=array("base"=>$user_lv[$i-1]["get_to"],"get_to"=>$total1,"stop_count"=>$user_lv[$i]["stop_count"]);
		}
		
		foreach($user_lv as $k=>$v)
		{
			$user_lv[$k]["stop_count_r"]=($v["stop_count"]==0 or $v["base"]==0)?0:round($v["stop_count"]/$v["base"],3)*100;
			$user_lv[$k]["get_to_r"]=($v["get_to"]==0 or $v["base"]==0)?0:round($v["get_to"]/$v["base"],3)*100;
		}

		ksort($user_lv);

		$arr1=array();
		$arr2=array();
		$arr_lb=array();
		$max=0;
		$max_lv=0;

		foreach($user_lv as $key=>$value)
		{
			if($value["get_to_r"]>0 or $value["stop_count_r"]>0){
				$arr1[]=$value["get_to_r"];
				$arr2[]=$value["stop_count_r"];
				$arr_lb[]=$key."级";
				
				if($value["get_to_r"]>$max)
				{
					$max=$value["get_to_r"];
				}
			}
		}

		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#0066FF');
		$line_dot->set_key("通过率",16);
		
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $arr2 );
		$line_dot1->set_colour('#00FFCC');
		$line_dot1->set_key("停留率",16);
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$max+5,ceil(($max+5)/15));
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element( $line_dot );
		$chart->add_element( $line_dot1 );
		echo $chart->toPrettyString();
	  }		

	//*********************************************总在线人数************************************************//
	function ond_user_sum($user_list)
	{
		$sum=0;
		foreach($user_list as $value)
		{
			if($value["online"]==1)
			{
				$sum++;	
			}
		}
		return $sum;
	}
	//*********************************************人物在线分布曲线图************************************************//
	function show_line($obj)
	{
		$dataurl="/?module=user&methods=show_line_data";
		$showstr=open_flash_chart_object_str("100%",250,$dataurl);
		
		$dataurl="/?module=user&methods=show_line_data30";
		$showstr30=open_flash_chart_object_str("100%",250,$dataurl);
		
		require_once parse_tmp("/user/income.html");//引入模版
	}
	
	
	function show_line_data($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '最近2小时曲线图（每个5分钟）：'.date("Y-m-d H:i") );
		
		$cont=new CONT("user");
		 
		$_user_lv_count=$cont->obj->newlog_online(1);
		$user_lv_count=array();
		foreach($_user_lv_count as $value)
		{
			if(strlen($value["H"])==1)$value["H"]="0".$value["H"];
			if(strlen($value["M"])==1)$value["M"]="0".$value["M"];
			$user_lv_count[intval($value["date"].$value["H"].$value["M"])]=$value;
		}
		ksort($user_lv_count);

		$arr1=array();
		$arr_lb=array();
		$max=0;
		$max_lv=0;
		$mi=0;
		foreach($user_lv_count as $key=>$value)
		{
			$mi++;
			if($mi>24)continue;
			$_max=0;
			foreach ($value["ip"] as $v)
			{
				$_max+=$v;
			}
			$arr1[]=$_max;
			$arr_lb[]=$value["H"].":".$value["M"];
			
			if($_max>$max)
			{
				$max=$_max;
			}
		}
		$max_lv=count($arr1);
		
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#00FF00');
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$max+5,ceil(($max+5)/15));
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element( $line_dot );
		echo $chart->toPrettyString();
	}
	
	function show_line_data30($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '今天半小时曲线：'.date("Y-m-d H:i") );
		
		$cont=new CONT("user");
		 
		$user_lv_count=$cont->obj->newlog_online(2);
		
		$arr1=array();
		$arr_lb=array();
		$max=0;
		$max_lv=0;
		
		foreach($user_lv_count as $key=>$value)
		{
			if($value["M"]==30 or $value["M"]==0){
				$_max=0;
				foreach ($value["ip"] as $v)
				{
					$_max+=$v;
				}
				$arr1[]=$_max;
				if(strlen($value["M"])==1)$value["M"]="0".$value["M"];
				$arr_lb[]=$value["H"].":".$value["M"];
				
				if($_max>$max)
				{
					$max=$_max;
				}
			}
		}
		$max_lv=count($arr1);
		
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#00FF00');
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$max+5,ceil(($max+5)/15));
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element( $line_dot );
		echo $chart->toPrettyString();
	}
	
	function show_line1($obj)
	{
		$obj->_bug=0;
		$post=$obj->post;
		if(isset($post["date"])){
			$date=$post["date"];
		}else{
			$date=date("Y-m",time());
		}
		
		$dataurl="/?module=user&methods=show_line_data1&date=".$date;
		$showstr=open_flash_chart_object_str("100%",500,$dataurl);
		require_once parse_tmp("/user/income_1.html");//引入模版
	}
	
	function show_line_data1($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '每天最高在线');
		
		$cont=new CONT("user");
		$__user_lv_count=$cont->obj->newlog_online($get["date"]);
		$user_lv_count=array();
		foreach($__user_lv_count as $key=>$value)
		{
			if(isset($user_lv_count[$value["date"]])==false)
			{
				$user_lv_count[$value["date"]]=0;
			}
			
			$_max=0;
			foreach ($value["ip"] as $v)
			{
				$_max+=$v;
			}
			if($_max>$user_lv_count[$value["date"]])
				$user_lv_count[$value["date"]]=$_max;
		}
		
		$arr1=array();
		$arr_lb=array();
		$max=0;
		$max_lv=0;

		foreach($user_lv_count as $key=>$value)
		{
			$arr1[]=$value;
			if(strlen($key)>8)
			{
				$arr_lb[]=substr($key,0,4)."-".substr($key,4,2)."-".substr($key,6,2).":".substr($key,8,2);
			}else{
				$arr_lb[]=substr($key,6,2);
			}
			
			if($value>$max)
			{
				$max=$value;
			}
		}
		
		$max_lv=count($arr1);
		
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#00FF00');
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$max+5,ceil(($max+5)/25));
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element( $line_dot );
		echo $chart->toPrettyString();
	}
	

	//************************************************用户注册渠道(统计不同渠道进来的人数)*********************************************//
			function show_pie($obj)
			{
				$dataurl2="/?module=user&methods=qudao_data";
				$showpie=open_flash_chart_object_str("100%",550,$dataurl2);
				require_once parse_tmp("/macro_data/qudao.html");//引入模版
			}
			
			function qudao_data($obj)
			{
			require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
			$cont=new CONT("user");
			$user_channel_count=$cont->obj->user_channel_count();
			foreach($user_channel_count["retval"] as $key=>$value)
			{
				$benyue[]=array("channel"=>$value["channel"],"total"=>$value["total"]);

			}
			$title = new \OFC_Elements_Title('游戏开始  到  ' . date("20y-m-d").' 各用户注册渠道统计');
			$pie = new \OFC_Charts_Pie();
			$pie->set_start_angle(10);
			$pie->set_animate(array(array("type"=>"fade")));
			$pie->tip="Label: #label#<br>Value: #percent#";			
			$color=array("#00FF00","#33CC99","#33FFFF","#3366FF","#000099","#FFCCFF","#FF00FF","#996600","#CC0066","#4184b6","#417daa","#4379a1","#447497","#436e8e","#446b87","#466881","#45637a","#445f73","#445b6d","#415564","#40525f","#3f4f5a","#37444d","#2b3339","#23292d","#1e2225","#1a1d1f","#181a1b","#101112","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606");
			$i=0;
			
			$pie->initialize();
			unset($pie->border);
			foreach($benyue as $value)
			{	
				$i++;
				if(empty($value["channel"]))
				{
					continue;
					
				}
				$pie->colours[]	=$color[$i];

				$val=array(

					"value"=>intval($value["total"]),
	//				$value["channel"]=="0"=>$value["channel"]=="其他渠道",
					"channel" => $value["channel"] == 0 ? "其他" : "",
	
	//				 empty($value["channel"])? $value["channel"] = "其他" : "",
					
					"label"=>$value["channel"]." 号渠道  (".intval($value["total"])."个)",
					"label_colour"=>$color[$i],
					"font_size"=>16,
						);
				$js="my_go('benyue_data_d','benyue_data_server','0',0)";
				$pie->values[]	=$pie->set_Pie_Value($val,$js);
			}
			
			$chart = new \OFC_Chart();
			$chart->set_title($title);  
			$chart->add_element($pie);
			$chart->x_axis = null;
			echo $chart->toPrettyString();
	}		
//*********************************************模块操作饼状图(模块*次数)************************************************//
			function show_pie2($obj)
			{
				$dataurl="/?module=user&methods=module_count";
				$showpie2=open_flash_chart_object_str("100%",550,$dataurl);
				require_once parse_tmp("/macro_data/module.html");//引入模版
			}
			
			function module_count($obj)
			{
				require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
				$cont=new CONT("user");
				$user_module_count=$cont->obj->user_module_count();
				
				foreach($user_module_count[0] as $key=>$value)
				{
					if(empty($user_module_count[1][$value["module_sub_id"]]))
					{
						continue;
					}
					
					$benyue[]=array("id"=>$value["module_sub_id"],"count"=>$value["count"],"name"=>$user_module_count[1][$value["module_sub_id"]]);
					
				}

				$title = new \OFC_Elements_Title('游戏开始  到  ' . date("20y-m-d").' 各模块点击次数占比统计');
				$pie = new \OFC_Charts_Pie();
				$pie->set_start_angle(10);
				$pie->set_animate(array(array("type"=>"fade")));
				$pie->tip		="Label: #label#<br>Value: #percent#";			
				$color=array("#00FF00","#33CC99","#33FFFF","#3366FF","#000099","#FFCCFF","#FF00FF","#996600","#CC0066","#4184b6","#417daa","#4379a1","#447497","#436e8e","#446b87","#466881","#45637a","#445f73","#445b6d","#415564","#40525f","#3f4f5a","#37444d","#2b3339","#23292d","#1e2225","#1a1d1f","#181a1b","#101112","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606","#060606");
				$i=0;
				
				$pie->initialize();
				unset($pie->border);
				foreach($benyue as $value)
				{

					$i++;
					$pie->colours[]	=$color[$i];
					$value["name"]=empty($value["name"])?"其他":$value["name"];
					$val=array(
									"value"=>intval($value["count"]),
									"label"=>$value["name"]."-模块  (".intval($value["count"])."次)",
									"label_colour"=>$color[$i],
									"font_size"=>16,
							);
					
					$js="my_go('benyue_data_d','benyue_data_server','0',0)";
					$pie->values[]	=$pie->set_Pie_Value($val,$js);
				}
				
				$chart = new \OFC_Chart();
				$chart->set_title($title);  
				$chart->add_element($pie);
				$chart->x_axis = null;
				echo $chart->toPrettyString();
		}	

		function delonlink($obj)
		{
			$cont=new CONT("user");
			$user_module_count=$cont->obj->delonlink();
			header("Location: /?module=user");
		}

		
//	$value["channel"]=$value["channel"]=="0"?"其它":$value["channel"];		
	function taskstop($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		
		$taskstop=$cont->obj->taskstop($post);
		$reg_user_sum=$cont->obj->reg_user_sum();
		require_once parse_tmp("/user/task_stop.html");//引入模版
	}
	//关卡分析
	function checkpoint($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->post;
		
		$type = $get["type"];
		$time1 = $get["time1"];
		$time2 = $get["time2"];
		
		$checkpoint=$cont->obj->checkpoint($type,$time1,$time2);
//		print_r($checkpoint);
		$sele = array(1=>'大关卡',0=>'小关卡');
		require_once parse_tmp("/user/checkpoint.html");//引入模版
	}
	
	
	
	function login2($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$get=$obj->get;
		$login2=$cont->obj->login2();
		$login2x=$cont->obj->login2x();
		require_once parse_tmp("/user/login2.html");//引入模版
	}
	
	function actlog($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		if($post)
		{
			$actlog=$cont->obj->actlog($post["nickname"],$post["teb"]);
			$tab=$post["teb"];
		}else{
			$actlog=array();
		}
		require_once parse_tmp("/user/actlog.html");//引入模版
	}
	
	function globlist($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$globlist=$cont->obj->globlist();
		require_once parse_tmp("/user/globlist.html");//引入模版
	}
	
	function operators_query($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		if($post["name"]){
			$operators_query=$cont->obj->operators_query($post["name"],$post["type"]);
		}else{
			$operators_query=array();
		}
		require_once parse_tmp("/user/operators_query.html");//引入模版
	}
	
	function ranking($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$get=$obj->get;
		$ranking=$cont->obj->ranking($get["type"]);
		require_once parse_tmp("/user/ranking.html");//引入模版
	}
	//用户习惯（平均每日时长和次数）
	function user_habit($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$get=$obj->get;
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m-d",time()-86400*6);
			$time2=date("Y-m-d",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();
		}
		
		$dataurl="/?module=user&methods=user_habit_info&time1=".$time1."&time2=".$time2;
		$showpie3=open_flash_chart_object_str("100%",550,$dataurl);
		
		$avearge_info = $cont->obj->avearge_user_habit($time1, $time2);
		
		#$base_module_list=$cont->obj->base_module_list();
		
		require_once parse_tmp("/user/user_habit.html");//引入模版
	}
		
	function user_habit_info($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$get=$obj->get;
		
		$title = new \OFC_Elements_Title( "每日平均游戏时长与次数");
		$user_habit_info = $cont->obj->user_habit($get["time1"], $get["time2"]);
			
		$bar=array();
		$arr_lb=array();
		$quxian=array();
		$money=0;
		$login_count=0;
		$today_LT=0;
		
		$timelist1=array();
		$timelist2=array();
		$timedata=array();
		
		//平均游戏时长与次数
		foreach($user_habit_info as $key=>$value)
		{
			$timelist1[$key]=$value["today_login_count"]==0?0:$value["today_LT"]/$value["today_login_count"];
			$timelist2[$key]=$value["today_login_count"]==0?0:$value["today_login_sum"]/$value["today_login_count"];
			$timedata[$key]=$value;
			if($value["today_login_sum"]>$login_count)
			{
				$login_count=$value["today_login_count"]==0?0:$value["today_login_sum"]/$value["today_login_count"];
			}
			if($value["today_LT"]>$today_LT)
			{
				$today_LT=$value["today_login_count"]==0?0:$value["today_LT"]/$value["today_login_count"];
			}
			
		}
			
		ksort($timelist1);
		ksort($timelist2);
		
		foreach ($timelist1 as $key=>$value)
		{
			if(isset($timedata[$key]))
			{
				$bar[]=$value;
			}else{
				$bar[]=0;
			}
		}
		
		foreach ($timelist2 as $key=>$value)
		{
			if(isset($timedata[$key]))
			{
				$quxian[]=$value;
			}else{
				$quxian[]=0;
			}
		}
		
		$lb_time=array();
		if(count($timelist2)>count($timelist1))
		{
			$lb_time=$timelist2;
		}else{
			$lb_time=$timelist1;
		}
		
		foreach ($lb_time as $key=>$value)
		{
			$arr_lb[] = strval($key);
		}
		
		$bar_dot = new \OFC_Charts_Bar();
		$bar_dot->set_tooltip('游戏时长: #val#');
		$bar_dot->set_values( $bar );	
		$bar_dot->set_colour('#22ABF3');
		$bar_dot->set_key("游戏时长",16);
		
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $quxian);	
		$line_dot->set_colour('#F5B50D');//绿色
		$line_dot->set_key("游戏次数",16);
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );


		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#22ABF3');	//
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$today_LT+10,10);
		$y->set_steps($today_LT);
		$y->set_colour( '#9900FF');	//紫色
		
  		$y1=new \OFC_Elements_Axis_Y_Right();
  		$y1->set_range(0,$login_count+10,10);
  		$y1->set_steps($login_count);
  		$y1->set_colour( '#9900FF');	//紫色
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
  		$chart->set_y_axis_right($y1);
	
		$chart->add_element($bar_dot);
		$chart->add_element($line_dot);
		echo $chart->toPrettyString();
	}
	
	function user_online_duration($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$get=$obj->get;
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m-d",time()-86400*6);
			$time2=date("Y-m-d",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();
		}
		
		$online_duration = $cont->obj->user_online_duration($time1, $time2);
		
		require_once parse_tmp("/user/user_online_duration.html");//引入模版
	}
	
	function novice_progress($obj){
		$obj->_bug=0;
		$cont=new CONT("user");
		$post=$obj->post;
		$get=$obj->get;
		
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m-d",time()-86400*6);
			$time2=date("Y-m-d",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();
		}
		
		$_novice_progress = $cont->obj->novice_progress($time1, $time2);
		//数据组合
		$data=array(
		  "total"=>0,
		  "total_reg"=>0,
		  "Outflow3"=>0,
		  "step_name"=>"",
		  "preclude_total"=>0
		);
		$datalist=array();
		foreach($_novice_progress as $value){
			if(isset($datalist[$value["step_id"]])==false){
				$datalist[$value["step_id"]]=$data;
			}
			$datalist[$value["step_id"]]["total"]+=$value["total"];
			$datalist[$value["step_id"]]["total_reg"]+=$value["total_reg"];
			$datalist[$value["step_id"]]["Outflow3"]+=$value["Outflow3"];
			$datalist[$value["step_id"]]["preclude_total"]+=$value["preclude_total"];
			$datalist[$value["step_id"]]["step_name"]=$value["step_name"];
		}
		require_once parse_tmp("/user/novice_progress.html");//引入模版
	}
}
?>