<?php
namespace plugins\server_analysis;
use controllers\controllers as CONT;
class server_analysis{
	//物品查询
	function analysis($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("server_analysis");
		$post=$obj->post;
		$analysis_data=array();
		if(isset($post["analysis"]) and isset($post["time"])){
			$post["analysis"]=str_replace(array("，",","),",",trim($post["analysis"]));
			$analysis=explode(",",$post["analysis"]);
			$analysis_data=$cont->obj->analysis($analysis,intval($post["time"]));
		}
		
		$dataurl="/?module=server_analysis&methods=show_pay_line&date=".urlencode(json_encode($analysis_data));
		//echo $dataurl;
		$showstr=open_flash_chart_object_str("100%",400,$dataurl);
		
		require_once parse_tmp("/data_analysis/server_analysis.html");//引入模版
	}
	
	function show_pay_line($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
 
		$title = new \OFC_Elements_Title( '在线充值关系图');
	
		$analysis_data=(array)json_decode(urldecode($get["date"]));

		$arr1=array();
		$arr2=array();
		$arr_lb=array();
		$_max=0;
		$_max1=0;
		$max_lv=0;
		
		foreach($analysis_data as $key=>$v)
		{
			$value=(array)$v;
			$arr1[]=round($value["average_online"],2);
			$arr2[]=round($value["money_count"]/$value["average_online"],2);
			$arr_lb[]=$key."服";
			
			if($value["average_online"]>$_max)$_max=$value["average_online"];
			if(round($value["money_count"]/$value["average_online"],2)>$_max1)$_max1=round($value["money_count"]/$value["average_online"],2);
		}
		if($_max1>$_max)$_max=$_max1;
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );	//减少
		$line_dot->set_colour('#F5B50D');
		$line_dot->set_key("平均在线",16);
		
		$line_dot1 = new \OFC_Charts_Line();
		$line_dot1->set_values( $arr2 );	//增加
		$line_dot1->set_colour('#00FF00');//绿色
		$line_dot1->set_key("在线充值比",16);
		
		
		
		/*
		$line_dot = new \OFC_Charts_Line();
		$line_dot->set_values( $arr1 );
		$line_dot->set_colour('#00FF00');
		*/
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		

		$x = new \OFC_Elements_Axis_X();
		$x->set_offset(false);
		$x -> set_colour('#A2ACBA');
		//用数组设定X轴下标内容
		$x -> set_labels_from_array($arr_lb);
		$range=ceil($_max/25);
		$y=new \OFC_Elements_Axis_Y();
		$y->set_range(0,$_max+$range,$range);
		$y->set_colour( '#A2ACBA');
		
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		$chart->add_element( $line_dot );
		$chart->add_element( $line_dot1 );
		echo $chart->toPrettyString();
	}
	
}
?>