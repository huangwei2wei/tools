<?php
namespace plugins\test;
use controllers\controllers as CONT;
class test{
	function index($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		
		//$user_list=$cont->obj->update_user();
		//$user_list=$cont->obj->update_pay_logw();
		
		//$user_list=$cont->obj->updateuser_id();
		
		//$user_list=$cont->obj->uplongmai();
		//$user_list=$cont->obj->upgoods();
		
		//$user_list=$cont->obj->uphotel();
		//$user_list=$cont->obj->upuser_info_force();
		//$user_list=$cont->obj->imtoserver();
		//$user_list=$cont->obj->delgoods();
		//$user_list=$cont->obj->upwto();
		//$user_list=$cont->obj->upgoods_gem();
		//print_r(date("w"));
		
		$obj->_bug=0;
		
		$post=$obj->post;
		$md5="";
		if(isset($post["md5"]))
		{
			$md5=md5($post["md5"]);
		}
		
		require_once parse_tmp("/test/test.html");//引入模版
	}
	
	function delgoods($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		
		//$user_list=$cont->obj->update_user();
		//$user_list=$cont->obj->update_pay_logw();
		
		//$user_list=$cont->obj->updateuser_id();
		
		//$user_list=$cont->obj->uplongmai();
		//$user_list=$cont->obj->upgoods();
		
		//$user_list=$cont->obj->uphotel();
		//$user_list=$cont->obj->upuser_info_force();
		//$user_list=$cont->obj->imtoserver();
		$user_list=$cont->obj->delgoods();
		//print_r(date("w"));
		
		$obj->_bug=0;

		
		require_once parse_tmp("/test/test.html");//引入模版
	}
	
	function first_recharge($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		//$user_list=$cont->obj->first_recharge();	
		//$user_list=$cont->obj->user_ontime();
	}
	
	function hero_team($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		//$cont->obj->hero_team();
		//$cont->obj->today_diamond_time();
	}
	
	function bar($obj)
	{
		$get=$obj->get; 
		$post=$obj->post; 
		$dataurl="/?module=test&methods=bar_count&type=".$get["type"]."&e=".$get["e"]."&time1=".trim($post["time1"])."&time2=".trim($post["time2"]);
		print($dataurl);
		$showpie3=open_flash_chart_object_str("100%",550,$dataurl);
		$time1=$post["time1"];
		$time2=$post["time2"];
		require_once parse_tmp("/test/test.html");//引入模版
	}
	
	function bar_count($obj){
		/*
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');
		
		srand((double)microtime()*1000000);
		$data = array();
		
		// add random height bars:
		for( $i=0; $i<7; $i++ )
		  $data[] = rand(2,9);
		  
		// make the last bar a different colour:
		$bar = new \OFC_Charts_Bar_Value(5);
		
		$bar->set_colour( '#900000' );
		$bar->set_tooltip( 'Hello<br>#val#' );
		$data[] = $bar;
		
		$title = new \OFC_Elements_Title( date("D M d Y") );
		
		$bar = new \OFC_Charts_Bar_3d();
		$bar->set_values( $data );
		$bar->colour = '#D54C78';
		
		$x_axis = new \OFC_Elements_Axis_X();
		//$x_axis = new \OFC_Elements_Axis_Y();
		$x_axis->set_3d( 5 );
		$x_axis->colour = '#909090';
		$x_axis->set_labels_from_array(array('Mon','Tue','Wed','Thur','Fri','Sat','Sun','Sun1')); 
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_x_axis( $x_axis );
		
		echo $chart->toPrettyString();
		*/
		
		require_once(D_R.'kernel/open-flash-chart-2/OFC/OFC_Chart.php');

		$x_labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

		$title = new \OFC_Elements_Title("Total hours on project mayhem" );
		
		$hbar = new \OFC_Charts_Bar_Horizontal();
		$hbar->set_tooltip( 'Months: #val#' );
		$hbar->set_values( array(4,8,3,4,7,8) );
		
		$chart = new \OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $hbar );
		
		$x = new \OFC_Elements_Axis_X();
		$x->set_offset( false );
		$x->set_range( 0, 10 );
		$chart->set_x_axis( $x );
		
		$y = new \OFC_Elements_Axis_Y();
		$y->set_offset( true );
		$y->set_labels( array( "Jeff","Geoff","Bob","Terry","Duncan","monk.e.boy" ) );
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