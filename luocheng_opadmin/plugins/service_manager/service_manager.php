<?php
namespace plugins\service_manager;
use controllers\controllers as CONT;
class service_manager{
	//开服列表
	function open_service_list($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		
		$open_service_list=$cont->obj->open_service_list($operators_id);
		
		$service_list=$open_service_list[0];
		$pages=$open_service_list[1];
		
		require_once parse_tmp("/service_manager/open_service_list.html");//引入模版
	}
	
	//更新状态
	function upsestatus($obj)
	{
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id =intval($get['operators_id']);
		$server_id =intval($get['server_id']);
		$status =intval($get['e']);
		$cont->obj->upsestatus($operators_id,$server_id,$status);
		$this->open_service_list($obj);
	}
	
	//合服列表
	function combined_service_list($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		
		$combined_service_list=$cont->obj->combined_service_list($operators_id);
		
		$service_list=$combined_service_list[0];
		$pages=$combined_service_list[1];
		
		require_once parse_tmp("/service_manager/combined_service_list.html");//引入模版
	}
	
	//添加游戏服
	function add_server_show($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$add_server_show=$cont->obj->add_server_show($operators_id);
		
		preg_match_all("/[0-9]*/", $add_server_show["server_name"] , $match);
		$server_name=str_replace($match[0][6],intval($match[0][6])+1,$add_server_show["server_name"]);
	
		require_once parse_tmp("/service_manager/add_server_show.html");//引入模版
	}
	
	//添加游戏服数据
	function add_server($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		if(isset($get["operators_id"]))
			$operators_id=$get["operators_id"];
		else
			$operators_id=$post["operators_id"];
			
		$cont->obj->add_server($post);
		ob_end_clean();  
		print("添加成功，2秒后返回开服列表");
		flush();
		sleep(2);
		header("Location: /?module=service_manager&methods=open_service_list&operators_id=".$operators_id); 
	}
	
	//自由服务器列表
	function free_service_list($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$mainid=$get["mainid"];
		$free_service_list=$cont->obj->free_service_list($operators_id);
		
		require_once parse_tmp("/service_manager/free_service_list.html");//引入模版	
	}
	
	//往已存在的合服里添加一个新服
	function select_service($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$server_id=$get["server_id"];
		$mainid=$get["mainid"];
		$free_service_list=$cont->obj->select_service($operators_id,$server_id,$mainid);
	}
	
	//删除一个合服里的的一个子服
	function del_combined_service($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$server_id=$get["server_id"];
		$mainid=$get["mainid"];
		$free_service_list=$cont->obj->del_combined_service($operators_id,$server_id,$mainid);
	}
	
	//创建合服
	function create_combined($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$server_id=$get["server_id"];
		$cont->obj->create_combined($operators_id,$server_id);
		$this->combined_service_list($obj);
	}
	
	//js修改
	function upservice_data($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		
		$query_filed=$get["query_filed"];
		$query_value=$get["query_value"];
		$filed=$get["filed"];
		$data=$get["data"];
		//query_filed
		//query_value
		//filed
		//data
		$cont->obj->upservice_data($query_filed,$query_value,$filed,$data);
	}
	
	//js修改开服时间
	function upopen_service_time($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;

		$time=$get["time"];
		$operators_id=$get["operators_id"];
		$server_id=$get["server_id"];

		$cont->obj->upopen_service_time(intval($operators_id),intval($server_id),intval(strtotime($time)));
		echo "ok";
	}
	
	#开服统计
	function open_server_statistics($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		
		if(isset($post["time1"])==false){
			$time1=isset($post["time1"])?$post["time1"]:date("Y-m-d",time()-86400*7);
			$time2=isset($post["time2"])?$post["time2"]:date("Y-m-d",time());
		} else {
			$time1=$post["time1"];
			$time2= $post["time2"];
		}
		if(isset($post["Platform1"])==false){
			$server_id = 0;
		} else { 
			$server_id = $post["Platform1"];
		}
		if(isset($post["Platform"])==false){
			$select_platform = '0';
		} else {
			$select_platform = $post["Platform"];
		}
		$serv_info_list = $cont->obj->open_server_statistics($operators_id, intval($server_id), $time1, $time2, $select_platform);
		$service_list=$serv_info_list[0];
		$pages=$serv_info_list[1];
		$other_list = $serv_info_list[2];
		
		$select_serv = isset($post['Platform1']) ? $post['Platform1'] : '';
		$select_value = isset($post['Platform']) ? $post['Platform'] : '';
		
		require_once parse_tmp("/service_manager/open_server_statistics.html");//引入模版	
	}
	//设备监控
	function device_monitor($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
	
		if(isset($post["Platform"])==false){
			$select_type = 1;
		} else {
			$select_type = $post["Platform"];
		}
		$gateway_listen_list = $cont->obj->gateway_listen($operators_id, intval($select_type));
		$select_value = isset($post['Platform']) ? $post['Platform'] : '';
	
		require_once parse_tmp("/service_manager/device_monitor.html");//引入模版
	}
	
	#cpu内存使用率
	function cpu_memory_used($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$type = $get["type"];
		$ip = $get["ip"];
		
 		$dataurl="/?module=service_manager&methods=line_cpu_memory_used&type=".$type."&operators_id=".$operators_id."&ip=".$ip;
 		$showline=open_flash_chart_object_str("100%",550,$dataurl);
 		
 		$dataurl="/?module=service_manager&methods=line_cpu_memory_used_30&type=".$type."&operators_id=".$operators_id."&ip=".$ip;
 		$showline30=open_flash_chart_object_str("100%",550,$dataurl);
	
		require_once parse_tmp("/service_manager/cpu_memory_usage_rate.html");//引入模版
	}
	
	function cpu_memory_used_day($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$type = $get["type"];
		$ip = $get["ip"];
		if(isset($post["date"])){
			$date=$post["date"];
		}else{
			$date=date("Y-m",time());
		}
	
		$dataurl="/?module=service_manager&methods=line_cpu_memory_used_day&type=".$type."&operators_id=".$operators_id."&ip=".$ip."&date=".$date;
		$showline=open_flash_chart_object_str("100%",550,$dataurl);
			
	
		require_once parse_tmp("/service_manager/cpu_memory_usage_rate1.html");//引入模版
	}
	
	function line_cpu_memory_used_day($obj)
	{
	
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
	
		$title = new \OFC_Elements_Title( " 每天cpu/内存使用率曲线图：".date("Y-m-d H:i"));
	
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$type = $get["type"];
		$ip = $get["ip"];
		$date = $get["date"];
	
		$cpu_memory_used=$cont->obj->cpu_memory_used($operators_id, intval($type), $ip, $date);
	
		$quxian=array();
		$arr_lb=array();
		$quxian2=array();
		$y_data=0.0;
		$cpu=0.0;
		$memory=0.0;
	
		$timelist1=array();
		$timelist2=array();
		$arr = array();
		//cpu
		foreach($cpu_memory_used as $key=>$value)
		{
			if (!array_key_exists($value["date"], $arr)){
				//$arr[$value["date"]] = $value;
				$value["CPU"] = round($value["CPU"]*100,2);
				$value["MemUsed_Ratio"] = round($value["MemUsed_Ratio"]*100,2);
				$timelist1[$value["YmdHM"]]=$value;
				$timelist2[$value["YmdHM"]]=$value;
					
				if($value["MemUsed_Ratio"]>$memory)
				{
					$memory=$value["MemUsed_Ratio"];
				}
				if($value["CPU"]>$cpu)
				{
					$cpu=$value["CPU"];
				}
			}
			$arr[$value["date"]] = 1;
		}
		ksort($timelist1);
		ksort($timelist2);
		foreach ($timelist1 as $key=>$value)
		{
			$quxian[]=$value["CPU"];
		}
	
		foreach ($timelist2 as $key=>$value)
		{
			$quxian2[]=$value["MemUsed_Ratio"] ;
		}
		
		if ($memory > $cpu)
			$y_data = $memory;
		else
			$y_data = $cpu;
	
		foreach ($timelist1 as $key=>$value)
		{
			//$arr_lb[]=substr($key,4,4);
			$arr_lb[]=substr(strval($value["date"]),4,4);
		}
	
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $quxian );	//减少
		$line_dot->set_colour('#F5B50D');
		$line_dot->set_key("cpu",16);
	
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $quxian2 );	//增加
		$line_dot1->set_colour('#00FF00');//绿色
		$line_dot1->set_key("内存",16);
	
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
	
	
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#FFFFE0');	//蓝色
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
	
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$y_data+10,intval(($y_data+10)/10));
		$y->set_colour( '#9900FF');	//紫色
	
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
	
		$chart->add_element($line_dot);
		$chart->add_element($line_dot1);
		echo $chart->toPrettyString();
	}
	
	function line_cpu_memory_used($obj)
	{
	
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
	
		$title = new \OFC_Elements_Title( " 最近2小时cpu/内存使用率曲线图（每个5分钟）：".date("Y-m-d H:i"));

		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$type = $get["type"];
		$ip = $get["ip"];
	
		$cpu_memory_used=$cont->obj->cpu_memory_used($operators_id, intval($type), $ip, 1);
		
		$quxian=array();
		$arr_lb=array();
		$quxian2=array();
		$y_data=0.0;
		$cpu=0.0;
		$memory=0.0;
	
		$timelist1=array();
		$timelist2=array();
		//cpu
		foreach($cpu_memory_used as $key=>$value)
		{
			$value["CPU"] = round($value["CPU"]*100,2);
			$value["MemUsed_Ratio"] = round($value["MemUsed_Ratio"]*100,2);
			$timelist1[$value["YmdHM"]]=$value;
			$timelist2[$value["YmdHM"]]=$value;
			
			if($value["MemUsed_Ratio"]>$memory)
			{
				$memory=$value["MemUsed_Ratio"];
			}
			if($value["CPU"]>$cpu)
			{
				$cpu=$value["CPU"];
			}
		}
		ksort($timelist1);
		ksort($timelist2);
		foreach ($timelist1 as $key=>$value)
		{
				$quxian[]=$value["CPU"];
		}
	
		foreach ($timelist2 as $key=>$value)
		{
				$quxian2[]=$value["MemUsed_Ratio"] ;
		}
		
		if ($memory > $cpu) 
			$y_data = $memory;
		else 
			$y_data = $cpu;
	
		foreach ($timelist1 as $key=>$value)
		{
			if(strlen($value["H"])==1)$value["H"]="0".$value["H"];
			if(strlen($value["M"])==1)$value["M"]="0".$value["M"];
			$arr_lb[]=$value["H"].":".$value["M"];
		}
	
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $quxian );	//减少
		$line_dot->set_colour('#F5B50D');
		$line_dot->set_key("cpu",16);
	
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $quxian2 );	//增加
		$line_dot1->set_colour('#00FF00');//绿色
		$line_dot1->set_key("内存",16);
	
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
	
	
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false); 
		$x -> set_colour('#FFFFE0');	//蓝色
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
	
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$y_data+10,intval(($y_data+10)/10));
		$y->set_colour( '#9900FF');	//紫色
	
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
	
		$chart->add_element($line_dot);
		$chart->add_element($line_dot1);
		echo $chart->toPrettyString();
	}
	
	function line_cpu_memory_used_30($obj)
	{
	
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
	
		$title = new \OFC_Elements_Title( " 今天半小时cpu/内存使用率曲线图：".date("Y-m-d H:i"));
	
		$obj->_bug=0;
		$cont=new CONT("service_manager");
		$get=$obj->get;
		$operators_id=$get["operators_id"];
		$type = $get["type"];
		$ip = $get["ip"];
	
		$cpu_memory_used=$cont->obj->cpu_memory_used($operators_id, intval($type), $ip, 2);
	
		$quxian=array();
		$arr_lb=array();
		$quxian2=array();
		$y_data=0.0;
		$cpu=0.0;
		$memory=0.0;
	
		$timelist1=array();
		$timelist2=array();
		//cpu
		foreach($cpu_memory_used as $key=>$value)
		{
			if($value["M"]==30 or $value["M"]==0){
				$value["CPU"] = round($value["CPU"]*100,2);
				$value["MemUsed_Ratio"] = round($value["MemUsed_Ratio"]*100,2);
				$timelist1[$value["YmdHM"]]=$value;
				$timelist2[$value["YmdHM"]]=$value;
					
				if($value["MemUsed_Ratio"]>$memory)
				{
					$memory=$value["MemUsed_Ratio"];
				}
				if($value["CPU"]>$cpu)
				{
					$cpu=$value["CPU"];
				}
			}
		}
		ksort($timelist1);
		ksort($timelist2);
		foreach ($timelist1 as $key=>$value)
		{
			$quxian[]=$value["CPU"];
		}
	
		foreach ($timelist2 as $key=>$value)
		{
			$quxian2[]=$value["MemUsed_Ratio"] ;
		}
		
		if ($memory > $cpu)
			$y_data = $memory;
		else
			$y_data = $cpu;
	
		foreach ($timelist1 as $key=>$value)
		{
			if(strlen($value["H"])==1)$value["H"]="0".$value["H"];
			if(strlen($value["M"])==1)$value["M"]="0".$value["M"];
			$arr_lb[]=$value["H"].":".$value["M"];
		}
	
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $quxian );	//减少
		$line_dot->set_colour('#F5B50D');
		$line_dot->set_key("cpu",16);
	
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $quxian2 );	//增加
		$line_dot1->set_colour('#00FF00');//绿色
		$line_dot1->set_key("内存",16);
	
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
	
	
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#FFFFE0');	//蓝色
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
	
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$y_data+10,intval(($y_data+10)/10));
		$y->set_colour( '#9900FF');	//紫色
	
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
	
		$chart->add_element($line_dot);
		$chart->add_element($line_dot1);
		echo $chart->toPrettyString();
	}
}
?>
