<?php
namespace plugins\money;
use controllers\controllers as CONT;
class money{
//*********************************************统计饼型图************************************************//
	function pies($obj)
	{
		$get=$obj->get; 
		$post=$obj->post; 
		$dataurl="/?module=money&methods=pies_count&type=".$get["type"]."&e=".$get["e"]."&time1=".trim($post["time1"])."&time2=".trim($post["time2"]);
		echo $dataurl;
		$showpie3=open_flash_chart_object_str("100%",550,$dataurl);
		$time1=$post["time1"];
		$time2=$post["time2"];
		require_once parse_tmp("/money/pies.html");//引入模版
	}
	
	function pies_count($obj)
	{
		$obj->_bug=0;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$cont=new CONT("money");
		$get=$obj->get; 
		$get["e"]=($get["e"]=="del")?"-":"+";
		$time1=isset($get["time1"])?$get["time1"]:"";
		$time2=isset($get["time2"])?$get["time2"]:"";		
		$user_money_count=$cont->obj->user_money_count($get["type"],$get["e"],$time1,$time2);
		foreach($user_money_count[0] as $key=>$value)
		{
			if(isset($user_money_count[1][$value["_id"]["module_sub_id"]])){
				$tmp_name=$user_money_count[1][$value["_id"]["module_sub_id"]];
			}else{
				$tmp_name=$value["_id"]["module_sub_id"];
			}
			$benyue[]=array("id"=>$value["_id"]["module_sub_id"],"money"=>$value["money"],"name"=>$tmp_name);
			
		}
		
		$title = new \OFC_Elements_Title('  从    ' ."<echo>$time1</echo>".'   到    ' ."<echo>$time2</echo>".' 各模块使用比例统计');
		while ($time1=0 and $time2=0)
		{
			$title =0;
		}
		$pie = new \OFC_Charts_Pie();
		$pie->set_start_angle(10);
		$pie->set_animate(array(array("type"=>"fade")));
		$pie->tip		="Label: #label#<br>Value: #percent#";			
		$pie->initialize();
		
		$color=rand_color(50);
		$i=0;
		
		
		unset($pie->border);
		foreach((array)$benyue as $value)
		{
			$i++;
			$pie->colours[]	=$color[$i];
			$val=array(
							"value"=>intval($value["money"]),
							"label"=>$value["name"]."-模块  (".intval($value["money"])."元宝)",
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
	//************************************************金币曲线统计图**************************************************************//	
	function line_show($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$dataurl="/?module=money&methods=line_show_count&type=".$get["type"]."&module_sub_id=".$get["module_sub_id"];
		$showpie3=open_flash_chart_object_str("100%",550,$dataurl);
		$time1=$post["time1"];
		$time2=$post["time2"];
		$base_module_list=$cont->obj->base_module_list();
		require_once parse_tmp("/money/line_show.html");//引入模版
	}
	
	function line_show_count($obj)
	{
		
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( "增减曲线趋势图");
		
		$cont=new CONT("money");
		$get=$obj->get; 
		
		$money_jin_count=$cont->obj->jinbi_count($get["type"],intval($get["module_sub_id"]));
		//print_r($money_jin_count);
		$quxian=array();
		$arr_lb=array();
		$quxian2=array();
		$money=0;
		$money_count1=0;
		$money_count2=0;
		
		$timelist1=array();
		$timelist2=array();
		$time1data=array();
		$time2data=array();
		//减少
		foreach($money_jin_count[0] as $key=>$value)
		{
			$timelist1[$value["_id"]["timecount"]]=$value["money"];
			$time1data[$value["_id"]["timecount"]]=$value;
			if($value["money"]>$money_count1)
			{
				$money_count1=$value["money"];
			}
			
		}
		
		//增加
		foreach($money_jin_count[1] as $key=>$value)
		{
			$timelist2[$value["_id"]["timecount"]]=$value["money"];
			$time2data[$value["_id"]["timecount"]]=$value;
			if($value["money"]>$money_count1)
			{
				$money_count1=$value["money"];
			}
		}

		ksort($timelist1);
		ksort($timelist2);
		
		foreach ($timelist1 as $key=>$value)
		{
			if(isset($time1data[$key]))
			{
				$quxian[]=$time1data[$key]["money"];
			}else{
				$quxian[]=0;
			}
		}
		
		foreach ($timelist2 as $key=>$value)
		{
			if(isset($time2data[$key]))
			{
				$quxian2[]=$time2data[$key]["money"];
			}else{
				$quxian2[]=0;
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
			if(strlen($key)>8)
			{
				$arr_lb[]=substr($key,0,4)."-".substr($key,4,2)."-".substr($key,6,2).":".substr($key,8,2);
			} else{
				$arr_lb[]=substr($key,0,4)."-".substr($key,4,2)."-".substr($key,6,2);
			}
	 	}
		
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $quxian );	//减少
		$line_dot->set_colour('#F5B50D');
		$line_dot->set_key("减少",16);
		
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $quxian2 );	//增加
		$line_dot1->set_colour('#00FF00');//绿色
		$line_dot1->set_key("增加",16);
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );


		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#0000FF');	//蓝色
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
				
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$money_count1+10,10);
		$y->set_steps($money_count1/4);
		$y->set_colour( '#9900FF');	//紫色
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element($line_dot);
		$chart->add_element($line_dot1);
		echo $chart->toPrettyString();
	}
	
	function recharge($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m",time());
			$time2=date("Y-m",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();	
		}
		
		$_recharge_list=$cont->obj->recharge($time1,$time2);
		$put_in_sum=$_recharge_list["put_in_sum"];
		$recharge_list=$_recharge_list["pay_sum"];
		$reg_base_count=$_recharge_list["reg_base_count"];
		krsort($recharge_list);
		require_once parse_tmp("/money/recharge_show.html");//引入模版
	}
	
	function recharge_line_day($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m-d",time()-86400);
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
		
		$_recharge_list=$cont->obj->recharge_line_day($time1,$time2);
		$newdata=array();
		foreach($_recharge_list as $value)
		{
			$day=date("Y-m-d",$value["ts"]);
			$H=intval(date("H",$value["ts"]));
			if(isset($newdata[$day])==false){
				$newdata[$day]=array();
				for($i=0;$i<24;$i++)
				{
					$newdata[$day][$i]=0;
				}
			}
			$newdata[$day][$H]+=$value["money"]/10;
		}
		ksort($newdata);
		require_once parse_tmp("/money/recharge_show_day.html");//引入模版
	}
	
	function recharge_m($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		if(isset($post["time1"])==false and isset($post["time2"])==false)
		{
			$time1=date("Y-m",time());
			$time2=date("Y-m",time());
		}elseif(isset($post["time1"]) and isset($post["time2"])==false){
			$time1=$time2=$post["time1"];
		}elseif(isset($post["time1"]) and isset($post["time2"])){
			$time1=$post["time1"];
			$time2=$post["time2"];
		}else{
			echo "条件错误";
			exit();	
		}
		
		$_recharge_list=$cont->obj->recharge_m($time1,$time2);
		$put_in_sum=$_recharge_list["put_in_sum"];
		$recharge_list=$_recharge_list["pay_sum"];
		krsort($recharge_list);
		require_once parse_tmp("/money/recharge_show_day.html");//引入模版
	}
	
	function recharge_line($obj)
	{
		$dataurl2="/?module=money&methods=recharge_line_count";
		$showpie3=open_flash_chart_object_str("100%",650,$dataurl2);
		require_once parse_tmp("/money/recharge_line.html");//引入模版
	}
	
	function recharge_line_count($obj)
	{
		$cont=new CONT("money");
		$_recharge_list=$cont->obj->recharge();
		
		$recharge_list=$_recharge_list["pay_sum"];
		ksort($recharge_list);	
		
		$cvalue=array();
		$cvalue[0]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[0]["value"][$key]=$value[1];
		}
		$cvalue[0]["key"]="总充值";
		$cvalue[0]["colour"]="#990000";
		
		
		
		$cvalue[1]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[1]["value"][$key]=$value[2];
		}
		$cvalue[1]["key"]="老用户充值";
		$cvalue[1]["colour"]="#0066CC";
		
		
		$cvalue[2]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[2]["value"][$key]=$value[0];
		}
		$cvalue[2]["key"]="总充值次数";
		$cvalue[2]["colour"]="#6666CC";
		
		
		
		$cvalue[3]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[3]["value"][$key]=$value[3];
		}
		$cvalue[3]["key"]="老用户充值次数";
		$cvalue[3]["colour"]="#9933CC";
		
		
		$cvalue[4]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[4]["value"][$key]=round($value[1]/$value[0],0);
		}
		$cvalue[4]["key"]="ARPU";
		$cvalue[4]["colour"]="#99CC33";
		
		$cvalue[5]=array("value"=>array());
		foreach($recharge_list as $key=>$value)
		{
			$cvalue[5]["value"][$key]=round($value[2]/$value[1],2)*100;
		}
		$cvalue[5]["key"]="老用户充值额占比";
		$cvalue[5]["colour"]="#FF99FF";
		
		$lineshow=create_line_show("充值曲线",$cvalue);
		echo $lineshow;
	}
	
	function queryrecharge($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$queryrecharge=$cont->obj->queryrecharge($post["nickname"]);
		require_once parse_tmp("/money/queryrecharge.html");//引入模版
	}
	
	function newrechargelist($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$newrechargelist=$cont->obj->newrechargelist();
		$moneylist=$newrechargelist[0];
		$p=$newrechargelist[1];
		$heroname=$newrechargelist[2];
		$online=$newrechargelist[3];
		require_once parse_tmp("/money/newrechargelist.html");//引入模版
	}
	
	function rechargelvlist($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$rechargelvlist=$cont->obj->rechargelvlist();
		require_once parse_tmp("/money/rechargelvlist.html");//引入模版	
	}
	
	function user_info($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$user_info=$cont->obj->user_info($get["user_name"]);
		require_once parse_tmp("/money/user_info.html");//引入模版	
	}
	
	function user_infos($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$user_info=array();
		if(isset($post["name"]))
		{
			$post["name"]=str_replace("，",",",$post["name"]) ;
			$name=explode(",",$post["name"]);
			foreach($name as $value){
				$user_info[]=$cont->obj->user_infos(trim($value));
			}
		}
		require_once parse_tmp("/money/user_infos.html");//引入模版	
	}
	
	function rechargelv100($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$rechargelv100=$cont->obj->rechargelv100();
		require_once parse_tmp("/money/rechargelv100.html");//引入模版	
	}
	
	function rechargeuserlvlist($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$rechargeuserlvlist=$cont->obj->rechargeuserlvlist();
		require_once parse_tmp("/money/rechargeuserlvlist.html");//引入模版	
	}
	
	function analog_recharge($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		if(isset($post["nickname"])){
			$rechargeuserlvlist=$cont->obj->analog_recharge($post);
		}
		require_once parse_tmp("/money/analog_recharge.html");//引入模版	
	}
	
	#资源消耗
	function resource_consumption($obj){
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		if(isset($post["time1"])==false){
			$time1=isset($post["time1"])?$post["time1"]:date("Y-m-d",time()-86400*7);
			$time2=isset($post["time2"])?$post["time2"]:date("Y-m-d",time());
		} else {
			$time1=$post["time1"];
			$time2= $post["time2"];
		}
		
		$level_start = 1;
		$level_end = 1;
		if(isset($post['level_start'])==false){
			$level_start = 45;
			$level_end   = 60;
		} else {
			$level_start=intval($post["level_start"]);
			$level_end= intval($post["level_end"]);
		}
			
		$type = 1;
		if($get["type"]) {
			$type = intval($get["type"]);
		}
		
		$resource_consumption = $cont->obj->resource_consumption($time1,$time2,$mobile_platforms, $type, $level_start, $level_end);
		
		$select_value = isset($post['Platform']) ? $post['Platform'] : '';
		$select_start = isset($post['level_start']) ? $post['level_start'] : '45';
		$select_end = isset($post['level_end']) ? $post['level_end'] : '60';
		
		if ($type == 1)
			require_once parse_tmp("/money/line_opmoney.html");//引入模版
		elseif ($type == 2)
			require_once parse_tmp("/money/line_opmoney2.html");//引入模版
		elseif ($type == 3)
			require_once parse_tmp("/money/line_opmoney3.html");//引入模版
		elseif ($type == 4)
			require_once parse_tmp("/money/line_opmoney4.html");//引入模版
	}
	
	function game_player_pay($obj){
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$time1= $post["time1"] ;
		$time2= $post["time2"];
 		$channel_id = $post["channel_id"];
 		$mobile_platforms = $post["mobile_platforms"];
 		$type = isset($get["type"])?$get["type"]:1;
 		$cont1=new CONT("user_query");
		$c = $cont1->obj->getChannel();
		$d = $cont1->obj->getPlatforms();
 		
		if($type == 1){
			$PaySituation = $cont->obj->PaySituation($time1,$time2,$channel_id,$mobile_platforms);
			require_once parse_tmp("/money/game_player_pay.html");//引入模版
			return;
		}elseif ( $type == 2 ) {
			$dataurl2="/?module=money&methods=game_player_pay_data2&time1=".$time1."&time2=".$time2;
//			echo $dataurl2;
			$userLvFb=open_flash_chart_object_str("50%",250,$dataurl2);
			require_once parse_tmp("/money/game_player_pay2.html");//引入模版
			return;
		}elseif ( $type == 3 ) {//首付等级
			$dataurl2="/?module=money&methods=game_player_pay_data3&time1=".$time1."&time2=".$time2."&mobile_platforms=".$mobile_platforms;
//			echo $dataurl2;
			$userLvFb=open_flash_chart_object_str("50%",250,$dataurl2);
			require_once parse_tmp("/money/game_player_pay3.html");//引入模版
			return;
		}elseif ( $type == 4 ) {//首付游戏时长
			$dataurl2="/?module=money&methods=game_player_pay_data4&time1=".$time1."&time2=".$time2."&mobile_platforms=".$mobile_platforms;
//			echo $dataurl2;
			$flush_data = open_flash_chart_object_str("50%",250,$dataurl2);
			require_once parse_tmp("/money/game_player_pay4.html");//引入模版
			return;
		}
	}
	
	// 	首付等级
	function game_player_pay_data4($obj){
		$obj->_bug=0;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("首付游戏时长" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val#' );
		$cont=new CONT("money");
		$get=$obj->get; 
		$time1 = $get["time1"];
		$time2 = $get["time2"];
		$mobile_platforms = $get["mobile_platforms"];
		///首付道具 数据
		$PaySituation = $cont->obj->ShoufuGameTime($time1,$time2,$mobile_platforms);
//		print_r($PaySituation);
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		
		if($PaySituation){
			foreach ($PaySituation["list"] as $k => $v){
				if($max<$v ){
					$max =$v ;
				}
				if($min > $v ){
					$min = $v ;
				}
				$data[] = $v ;
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
	
	// 	首付道具 数据
	function game_player_pay_data2($obj){
		$obj->_bug=0;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("首付道具" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val#' );
		
		$cont=new CONT("money");
		$get=$obj->get; 
		$time1 = $get["time1"];
		$time2 = $get["time2"];
		///首付道具 数据
		$PaySituation = $cont->obj->ShoufuProps($time1,$time2,false,false);
//		print_r($PaySituation);
		
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		
		if($PaySituation){
			foreach ($PaySituation["list"] as $k => $v){
				if($max<$v["num"]){
					$max =$v["num"];
				}
				if($min > $v["num"]){
					$min = $v["num"];
				}
				$data[] = $v["num"];
				$labels[] = $v["name"];
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
	
	// 	首付等级
	function game_player_pay_data3($obj){
		$obj->_bug=0;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		$title = new \OFC_Elements_Title("首付等级" );
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( '人数: #val#' );
		$cont=new CONT("money");
		$get=$obj->get; 
		$time1 = $get["time1"];
		$time2 = $get["time2"];
		$mobile_platforms = $get["mobile_platforms"];
		///首付道具 数据
		$PaySituation = $cont->obj->ShoufuLv($time1,$time2,$mobile_platforms);
//		print_r($PaySituation);
		$data = array();
		$labels = array();
		
		$max = 0;
		$min = 9999999;
		
		if($PaySituation){
			foreach ($PaySituation["list"] as $k => $v){
				if($max<$v ){
					$max =$v ;
				}
				if($min > $v ){
					$min = $v ;
				}
				$data[] = $v ;
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

	function line_opmoney_consume($obj)
	{
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 	
		$cont=new CONT("money");
		$get=$obj->get; 
		if($get["type"] == 1) { 
			$title = new \OFC_Elements_Title( "消耗产出曲线趋势图");
			$money_diamond_count=$cont->obj->diamond_count($get["type"],intval($get["module_sub_id"]), $get["time1"], $get["time2"]);
		
			$quxian=array();
			$arr_lb=array();
			$quxian2=array();
			$money=0;
			$money_count1=0;
			$money_count2=0;
			
			$timelist1=array();
			$timelist2=array();
			$time1data=array();
			$time2data=array();
			//减少
			foreach($money_diamond_count[0] as $key=>$value)
			{
				$timelist1[$value["_id"]["timecount"]]=$value["money"];
				$time1data[$value["_id"]["timecount"]]=$value;
				if($value["money"]>$money_count1)
				{
					$money_count1=$value["money"];
				}
				
			}
			
			//增加
			foreach($money_diamond_count[1] as $key=>$value)
			{
				$timelist2[$value["_id"]["timecount"]]=$value["money"];
				$time2data[$value["_id"]["timecount"]]=$value;
				if($value["money"]>$money_count1)
				{
					$money_count1=$value["money"];
				}
			}
	
			ksort($timelist1);
			ksort($timelist2);
			
			foreach ($timelist1 as $key=>$value)
			{
				if(isset($time1data[$key]))
				{
					$quxian[]=$time1data[$key]["money"];
				}else{
					$quxian[]=0;
				}
			}
			
			foreach ($timelist2 as $key=>$value)
			{
				if(isset($time2data[$key]))
				{
					$quxian2[]=$time2data[$key]["money"];
				}else{
					$quxian2[]=0;
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
				if(strlen($key)>8)
				{
					$arr_lb[]=substr($key,0,4)."-".substr($key,4,2)."-".substr($key,6,2).":".substr($key,8,2);
				} else{
					$arr_lb[]=substr($key,0,4)."-".substr($key,4,2)."-".substr($key,6,2);
				}
			}
			
			$line_dot = new \OFC_Charts_Line();
			$line_dot->set_values( $quxian );	//减少
			$line_dot->set_colour('#F5B50D');
			$line_dot->set_key("消耗",16);
			
			$line_dot1 = new \OFC_Charts_Line();
			$line_dot1->set_values( $quxian2 );	//增加
			$line_dot1->set_colour('#00FF00');//绿色
			$line_dot1->set_key("产出",16);
			
			$chart = new \OFC_Chart();
			$chart->set_title( $title );
	
	
			$x = new \OFC_Elements_Axis_X();
			$x->set_offset(false);
			$x -> set_colour('#0000FF');	//蓝色
			//用数组设定X轴下标内容
			$x -> set_labels_from_array($arr_lb);
					
			$y=new \OFC_Elements_Axis_Y();
			$y->set_range(0,$money_count1+10,10);
			$y->set_steps($money_count1/4);
			$y->set_colour( '#9900FF');	//紫色
			
			$chart->set_x_axis($x);
			$chart->set_y_axis($y);
		
			$chart->add_element($line_dot);
			$chart->add_element($line_dot1);
			echo $chart->toPrettyString();
		} elseif($get["type"] == 2) {
			$title = new \OFC_Elements_Title( "消耗次数曲线趋势图");
			$money_consume_times=$cont->obj->consume_times($get["type"],intval($get["module_sub_id"]), $get["time1"], $get["time2"]);
			#$print_r("xxxxx".$money_consume_times);
			$quxian=array();
			$arr_lb=array();
			$money=0;
			$times=0;
			
			$timelist1=array();
			$time1data=array();
			//消耗
			foreach($money_consume_times as $key=>$value)
			{
				$timelist1[$key]=$value;
				$time1data[$key]=$value;
				if($value>$times)
				{
					$times=$value;
				}
			}

			ksort($timelist1);
			foreach ($timelist1 as $key=>$value)
			{
				if(isset($time1data))
				{
					$quxian[]=$time1data[$key];
				}else{
					$quxian[]=0;
				}
			}
			
			$lb_time=$timelist1;
			
			foreach ($lb_time as $key=>$value)
			{
				$arr_lb[]=$key;
			}
			
			$line_dot = new \OFC_Charts_Line();
			$line_dot->set_values( $quxian );
			$line_dot->set_colour('#F5B50D');
			
			$chart = new \OFC_Chart();
			$chart->set_title( $title );
	
	
			$x = new \OFC_Elements_Axis_X();
			$x->set_offset(false);
			$x -> set_colour('#0000FF');	//蓝色
			//用数组设定X轴下标内容
			$x -> set_labels_from_array($arr_lb);
					
			$y=new \OFC_Elements_Axis_Y();
			$y->set_range(0,$times+10,10);
			$y->set_steps($times/4);
			$y->set_colour( '#9900FF');	//紫色
			
			$chart->set_x_axis($x);
			$chart->set_y_axis($y);
		
			$chart->add_element($line_dot);
			echo $chart->toPrettyString();
		} elseif($get["type"] == 3) {
			$title = new \OFC_Elements_Title( "消耗人数曲线趋势图");
			$money_consume_nums=$cont->obj->consume_nums($get["type"],intval($get["module_sub_id"]), $get["time1"], $get["time2"]);
			#$print_r("xxxxx".$money_consume_times);
			$quxian=array();
			$arr_lb=array();
			$money=0;
			$times=0;
			
			$timelist1=array();
			$time1data=array();
			//消耗
			foreach($money_consume_nums as $key=>$value)
			{
				$timelist1[$key]=$value;
				$time1data[$key]=$value;
				if($value>$times)
				{
					$times=$value;
				}
			}

			ksort($timelist1);
			foreach ($timelist1 as $key=>$value)
			{
				if(isset($time1data))
				{
					$quxian[]=$time1data[$key];
				}else{
					$quxian[]=0;
				}
			}
			
			$lb_time=$timelist1;
			
			foreach ($lb_time as $key=>$value)
			{
				$arr_lb[]=$key;
			}
			
			$line_dot = new \OFC_Charts_Line();
			$line_dot->set_values( $quxian );
			$line_dot->set_colour('#F5B50D');
			
			$chart = new \OFC_Chart();
			$chart->set_title( $title );
	
	
			$x = new \OFC_Elements_Axis_X();
			$x->set_offset(false);
			$x -> set_colour('#0000FF');	//蓝色
			//用数组设定X轴下标内容
			$x -> set_labels_from_array($arr_lb);
					
			$y=new \OFC_Elements_Axis_Y();
			$y->set_range(0,$times+10,10);
			$y->set_steps($times/4);
			$y->set_colour( '#9900FF');	//紫色
			
			$chart->set_x_axis($x);
			$chart->add_y_axis($y);
		
			$chart->add_element($line_dot);
			echo $chart->toPrettyString();
		} elseif($get["type"] == 4) {
			$title = new \OFC_Elements_Title( "商城消耗趋势图");
			$market_consume=$cont->obj->market_consume($get["type"],intval($get["module_sub_id"]), $get["time1"], $get["time2"]);
			
			$quxian=array();
			$arr_lb=array();
			$money=0;
			$money_count1=0;
		
			$timelist1=array();
			$time1data=array();
			
			foreach($market_consume as $key=>$value)
			{
				$timelist1[$value["_id"]["goods_ext_id"]]=$value["money"];
				$time1data[$value["_id"]["goods_ext_id"]]=$value;
				if($value["money"]>$money_count1)
				{
					$money_count1=$value["money"];
				}
			}
	
			ksort($timelist1);
	
			foreach ($timelist1 as $key=>$value)
			{
				if(isset($time1data[$key]))
				{
					$quxian[]=$time1data[$key]["money"];
				}else{
					$quxian[]=0;
				}
			}
			
			$lb_time=$timelist1;
			
			foreach ($lb_time as $key=>$value)
			{
				$name = $cont->obj->get_goods_name($key);
				#print_r("xxxx".$name);
				$arr_lb[]= $name;
			}
			
			$line_dot = new \OFC_Charts_Bar_Horizontal();
			$line_dot->set_tooltip('消耗钻石数: #val#');
			$line_dot->set_values( $quxian );
			$line_dot->set_colour('#F5B50D');
			
			$chart = new \OFC_Chart();
			$chart->set_title( $title );

			$y = new \OFC_Elements_Axis_Y();
			$y->set_offset(true);
			//用数组设定X轴下标内容
			$y->set_labels($arr_lb);
					
			$x=new \OFC_Elements_Axis_X();
			$x->set_offset( false );
			$x->set_range(0,$money_count1+10,10);
			$x->set_steps($money_count1/4);
			
			$chart->set_x_axis($x);
			$chart->add_y_axis($y);
		
			$chart->add_element($line_dot);
			echo $chart->toPrettyString();
		}
	}
	#用户充值清单
	function user_recharge_list($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get; 
		$post=$obj->post; 
		$nickname = $post["nickname"];
		if(isset($post["Platform"])==false){
			$select_type = 1;
			$is_first = true;
		} else { 
			$select_type = $post["Platform"];
			$is_first = false;
		}
		$user_recharge_list=$cont->obj->user_recharge_list($post["nickname"], intval($select_type), $is_first);
		
		require_once parse_tmp("/money/user_recharge_list.html");//引入模版
	}
	#收入分析
	function revenue_analysis($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get;
		$post=$obj->post;
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
		
		if(isset($post["Platform"])==false){
			$select_platform = '0';
			
		} else {
			$select_platform = $post["Platform"];
		}
		
		$select_value = isset($post['Platform']) ? $post['Platform'] : '';
		
		$revenue_analysis = $cont->obj->revenue_analysis($time1,$time2,$select_platform);
		
		require_once parse_tmp("/money/revenue_analysis.html");//引入模版
	} 
	
	#充值排行
	function recharge_ranking($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get;
		$post=$obj->post;
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
		$channel_id = $post["channel_id"];
		if(isset($channel_id)==false){
			$channel_id = 'All';
		} 
		
		//$select_value = isset($post['Platform']) ? $post['Platform'] : '';
		$c = $cont->obj->getChannel();
	
		$recharge_ranking = $cont->obj->recharge_ranking($time1,$time2,$channel_id);
	
		require_once parse_tmp("/money/recharge_ranking.html");//引入模版
	}
	
	#充值金额段分析
	function recharge_money_analyze($obj)
	{
		$cont=new CONT("money");
		$get=$obj->get;
		$post=$obj->post;
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
		$channel_id = $post["channel_id"];
		if(isset($channel_id)==false){
			$channel_id = 'All';
		}
	
		//$select_value = isset($post['Platform']) ? $post['Platform'] : '';
		$c = $cont->obj->getChannel();
	
		$recharge_money_analyze = $cont->obj->recharge_money_analyze($time1,$time2,$channel_id);
	
		require_once parse_tmp("/money/recharge_money_analyze.html");//引入模版
	}
}
?> 