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
	
	function tihuan($data)
	{
		$data=str_replace('{/font}','</font>',$data);
		$data=str_replace('{/u}','</u>',$data);
		$data=str_replace('{/a}','</a>',$data);
		$data=str_replace('{/b}','</b>',$data);
		$data=str_replace('{/i}','</i>',$data);
		
		$data=str_replace('{b}','<b>',$data);
		$data=str_replace('{u}','<u>',$data);
		$data=str_replace('{i}','<i>',$data);
		
		$data=str_replace('{font~color','<font color',$data);
		$data=str_replace('{a~href','<a href',$data);
		$data=str_replace('}','>',$data);
		return $data;
	}
	
	function link($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		$post=$obj->post;
		
		$edit_name="edit_link";
		$height=300;
		$width="100%";
		$ght=$height-115;
		$error="";
		if(isset($post["errid"])){
			$error=$cont->obj->geterror($post["errid"]);
			$error=$this->tihuan($error[0]["info"]);
		}
		
		$edit= "<iframe name='editt' id='editt' scrolling='no' hspace=0 vspace=0 src='/module/Edit/edit_link/index.php?id=$edit_name&height=$ght&width=$width' frameborder='0' height='$height' width='$width'></iframe>";
		require_once parse_tmp("/test/link.html");//引入模版
	}
	
	function uplink($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("test");
		$post=$obj->post;
		
		if($post["okedit"]=="")return false;
		if($post["id"]==0)return false;
		
		$cont->obj->uplink($post);
		echo "更新成功";
		
	}
	
	function repair_base2_monster_list($obj)
	{
		$cont=new CONT("test");
		$cont->obj->repair_base2_monster_list4();
		echo "修复成功";	
	}
}
?>