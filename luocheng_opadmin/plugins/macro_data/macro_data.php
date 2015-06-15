<?php
namespace plugins\macro_data;
use controllers\controllers as CONT;

class macro_data{
	function index($obj)
	{
//		$title="注册::看客网，就是我的";
//		require_once parse_tmp("/login/login.html");//引入模版
	}
	
		//*************************************用户基础表**************************//
	
	//修改用户数据
	function query_user_info($obj)
	{
		$obj->_bug=0;
		$post=isset($obj->post["query_nickname"])?1:0;
		$post=($post==0)?(isset($obj->get["query_query"])?1:0):$post;
		if($post)
		{
			$cont=new CONT("macro_data");
			$obj->post["table"]=isset($obj->post["table"])?$obj->post["table"]:"user_info";
			$nickname=isset($obj->post["query_nickname"])?$obj->post["query_nickname"]:$obj->get["query_nickname"];
			$user_name=isset($obj->post["query_user_name"])?$obj->post["query_user_name"]:$obj->get["query_user_name"];
			$user_info=$cont->obj->query_user_info($obj->post["table"],$nickname,$user_name);
			if($user_info[0])
			{
				unset($user_info[0]["_id"]);
				ksort($user_info[0]);
			}
			unset($user_info[0]["qq"],$user_info[0]["formation"],$user_info[0]["ext"]["section_plot"]);
			$actquery=$nickname;
		}
		
		$table=isset($obj->post["table"])?$obj->post["table"]:"";
		require_once parse_tmp("/macro_data/query_user_info.html");//引入模版
		
	}
	
	//更新用户数据
	function update_user_info($obj)
	{
		$obj->_bug=0;
		
		$nickname=$obj->post["query_nickname_tmp"];
		$user_name=$obj->post["query_user_name_tmp"];
		$table=$obj->post["table"];
		
		if($obj->post)
		{
			$cont=new CONT("macro_data");
			$table=$obj->post["table"];
			unset($obj->post["table"],$obj->post["query"]);
			$cont->obj->update_user_info($table,intval($obj->post["user_id"]),$obj->post);
		}
		
		unset($obj->post);
		$obj->post=array("query_nickname"=>$nickname,"query_user_name"=>$user_name,"table"=>$table);
		$this->query_user_info($obj);
	}
	
	
			//*************************************用户实时表**************************//
			
	
	
	//更新用户数据
	function update_user_ext_ft($obj)
	{
		$obj->_bug=0;
		
		if($obj->post)
		{
			$cont=new CONT("macro_data");
			$table=$obj->post["table"];
			unset($obj->post["table"],$obj->post["query"]);
			$user_info=$cont->obj->update_user_ext_ft($table,intval($obj->post["user_id"]),$obj->post);
		}
		
		$obj->post["table"]=$table;
		if($table!="user_info" && $table!="user_ext_ft" && $table!="user_task" && $table!="login_user")
		{
			$obj->post["nickname"]=intval($obj->post["id"]);
		}
		
		$this->query_user_info($obj);
	
	
		
	}
	
	//****************************************禁言用户**************************************//
	
	
	//禁言列表
	function gag_list($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->gag_list();
		require_once parse_tmp("/macro_data/block_user.html");//引入模版
	}
	
	//添加禁言用户
	function add_gag($obj)
	{
		$cont=new CONT("macro_data");
		$run=$cont->obj->add_gag($obj->post);
		if(!$run)
		{
			header("Location:/?module=macro_data&methods=gag_list");
		}else{
			echo "以下这些用户不存在<br>";
			foreach($run as $value)
			{
				echo $value."<br>";
			}
			echo "<br><a href='/?module=macro_data&methods=gag_list'>返回</a>";
		}
	}
	
	//删除禁言
	function dele_gag($obj)
	{
		if(isset($obj->get["user_id"]))
		{
			$cont=new CONT("macro_data");
			$prohibit_speech=$cont->obj->dele_gag($obj->get["user_id"]);
		}
		header("Location:/?module=macro_data&methods=gag_list");
	}
	
	//************************************词语过滤******************************************//
	
	
	//词语过滤
	function key_list($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->key_list($obj->post);
		require_once parse_tmp("/macro_data/msg_info.html");//引入模版
	}
	//添加词语
	function add_key($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->add_key($obj->post);
		header("Location:/?module=macro_data&methods=key_list");
	}
	
	//解除词语
	function del_key($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->del_key($obj->get["key"]);
		header("Location:/?module=macro_data&methods=key_list");
	}
	
	//***********************************封IP*******************************************//
	

    //ip列表
	function iplist($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->iplist();
		require_once parse_tmp("/macro_data/block_ip.html");//引入模版
		
	}
	
	//添加屏蔽IP
	function add_ip($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->add_ip($obj->post);
		header("Location:/?module=macro_data&methods=iplist");
	}
	
	//解除封锁
	function del_ip($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->del_ip($obj->get["ip"]);
		header("Location:/?module=macro_data&methods=iplist");
	}
	//解除封锁
	function del1_ip($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->del1_ip($obj->get["ip"]);
		header("Location:/?module=macro_data&methods=iplist");
	}
	
	
	//****************************************公告提示***************************************//
	
    //公告列表
	function notice_list($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->notice_list();
		require_once parse_tmp("/macro_data/bulletin_edtior_list.html");//引入模版
	}
	
	//编辑公告
	function edit_notice($obj)
	{
		$prohibit_speech="";
		$cont=new CONT("macro_data");
		if($obj->post)
		{
			$cont->obj->edit_notice($obj->post);
			header("Location:/?module=macro_data&methods=notice_list");
		}else{
			$prohibit_speech=$cont->obj->notice_list($obj->get["id"]);
			$info_=$prohibit_speech[0];
			require_once parse_tmp("/macro_data/edit_button.html");//引入模版
		}
	}
	
	//添加公告
	function addnotice($obj)
	{
		$cont=new CONT("macro_data");
		if($obj->post)
		{
			$cont->obj->addnotice($obj->post);
			header("Location:/?module=macro_data&methods=notice_list");
		}else{
			require_once parse_tmp("/macro_data/edit_button.html");//引入模版
		}
	}
	
	//删除公告
	function del_notice($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->del_notice($obj->get);
		header("Location:/?module=macro_data&methods=notice_list");
	}
	
	
	//**********************************封用户ID***************************************//


//封用户ID列表
	function id_list($obj)
	{
		$cont=new CONT("macro_data");
		$prohibit_speech=$cont->obj->id_list();

		require_once parse_tmp("/macro_data/closed_user.html");//引入模版
	}
	
	//添加封用户ID
	function add_id($obj)
	{
		$cont=new CONT("macro_data");
		$run=$cont->obj->add_id($obj->post);
		if(!$run)
		{
			header("Location:/?module=macro_data&methods=id_list");
		}elseif (nickname=="")
		{
			continue;
		}
		else{
			echo "以下这些用户不存在<br>";
			foreach($run as $value)
			{
				echo $value."<br>";
			}
			echo "<br><a href='/?module=macro_data&methods=id_list'>返回</a>";
		}
	}
	
	//开启用户
	function open_id($obj)
	{
		if(isset($obj->get["user_id"]))
		{
			$cont=new CONT("macro_data");
			$prohibit_speech=$cont->obj->open_id($obj->get["user_id"]);
		}
		header("Location:/?module=macro_data&methods=id_list");
	}

	function deleteuser($obj)
	{
		//$cont=new CONT("macro_data");
		require_once parse_tmp("/macro_data/deleteuser.html");//引入模版
	}	
	
	function deleteuser_act($obj)
	{
		$cont=new CONT("macro_data");
		$post=$obj->post;
		$prohibit_speech=$cont->obj->deleteuser_act($post["nickname"]);
		header("Location:/?module=macro_data&methods=deleteuser");
	}
	
	
	function querynickname($obj)
	{
		require_once parse_tmp("/macro_data/querynickname.html");//引入模版
	}
	
	function querynickname_act($obj)
	{
		$cont=new CONT("macro_data");
		$post=$obj->post;
		$user_name=$cont->obj->querynickname($post["nickname"]);
		require_once parse_tmp("/macro_data/querynickname.html");//引入模版
	}
	
	function inithuodong($obj)
	{
		$cont=new CONT("macro_data");
		$post=$obj->post;
		$get=$obj->get;
		$_huodong=$cont->obj->inithuodong();
		echo "初始化成功";
	}
	
	function huodong($obj)
	{
		$typename=array(1=>"竞技排名",2=>"战力排名",3=>"等级排名"
		,10=>"首次充值",11=>"充值额",12=>"消费指定额",
		20=>"有指定装备",21=>"达到强化等级",22=>"招募指定武将",23=>"获得指定数量武将",24=>"达到官阶等级",25=>"达到无双等级"
		);
		$cont=new CONT("macro_data");
		$post=$obj->post;
		$get=$obj->get;
		$_huodong=$cont->obj->huodong();
		if($_huodong[0]==false){
			echo "此服没有活动，是否导入系统活动&nbsp;&nbsp;&nbsp;&nbsp;<a href='/?module=macro_data&methods=importsyshuodong&act=list'>导入</a>";
			exit();
		}

		$huodong_list=array();
		foreach((array)$_huodong[0] as $key=>$value)
		{
			$huodong_list[$value["id"]]=$value;
		}
		
		foreach((array)$_huodong[1] as $key=>$value)
		{
			$huodong_list[$value["oid"]]["list"][]=$value;
		}
		
		foreach($huodong_list as $key=>$value)
		{
			if($value["id"]==$get["id"])
			{
				$editdata=$value;
			}
		}
		
		$oalist=$cont->obj->mongohuodong();
		foreach($huodong_list as $key=>$value)
		{
			$huodong_list[$key]["status"]=$oalist[$value["id"]];
			$huodong_list[$key]["condition_type"]=$typename[$huodong_list[$key]["condition_type"]];
		}
		require_once parse_tmp("/macro_data/huodong.html");//引入模版
	}
	
	function huodong_act($obj)
	{
		$cont=new CONT("macro_data");
		$post=$obj->post;
		if($post["data"]["condition_type"]==0)
		{
			print("请选择条件类型");
			return false;	
		}
		
		if($post["data"]["cycle"]!=9)
		{
			$post["data"]["activity_time1"]=intval(strtotime($post["data"]["activity_time1"]));
			$post["data"]["activity_time2"]=intval(strtotime($post["data"]["activity_time2"]));
			
			$post["data"]["award_time1"]=intval(strtotime($post["data"]["award_time1"]));
			$post["data"]["award_time2"]=intval(strtotime($post["data"]["award_time2"]));
		}else{
			$post["data"]["activity_time1"]=intval($post["data"]["activity_time1"]);
			$post["data"]["activity_time2"]=intval($post["data"]["activity_time2"]);
			
			$post["data"]["award_time1"]=intval($post["data"]["award_time1"]);
			$post["data"]["award_time2"]=intval($post["data"]["award_time2"]);
		}
		
		foreach($post["data"] as $key=>$val)
		{
			$field[]=$key;
			$value[]=$val;
		}
		
		$field[]="sys";
		$value[]=$_SESSION["server_id"];
		$cont->obj->huodong_act($field, $value,$post["id"]);
				
		header("Location:/?module=macro_data&methods=huodong");
	}
	
	
	function huodong_act_x($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$cont->obj->huodong_act_x($get["p"],intval($get["value"]),intval($get["id"]));
		header("Location:/?module=macro_data&methods=huodong&act=list");
	}
	
	
	
	function user_goods_list($obj)
	{
		$cont=new CONT("macro_data");
		$post=$obj->post;
		$get=$obj->get;
		//$huodong_list=$cont->obj->huodong();
		require_once parse_tmp("/macro_data/user_goods_list.html");//引入模版
	}
	
	
	function user_goods_list_act($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$tmp_goods_list=$cont->obj->user_goods_list_act($post["nickname"],$post["goods_name"]);
		$goods_list=array();
		foreach($tmp_goods_list as $key=>$value)
		{
			$goods_list[]=$value;
		}
		require_once parse_tmp("/macro_data/user_goods_list.html");//引入模版
	}
	
	
	function huodongadd($obj)
	{
		$get=$obj->get;
		$oid=$get["id"];
		require_once parse_tmp("/macro_data/huodongadd.html");//引入模版
	}
	
	function huodongadd_act($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		foreach($post["data"] as $key=>$val)
		{
			if($key=="award_res" or $key=="award_goods_ext_id")
			{
				if(substr($val,-1,1)==","){
					$val=substr($val,0,-1);
				}
			}
			
			$field[]=$key;
			$value[]=$val;
		}
		
		$cont->obj->huodongadd_act($field,$value);
		print("添加成功");
	}
	
	function huodongdel($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$cont->obj->huodongdel($get["id"]);
		print("删除成功");
		header("Location:/?module=macro_data&methods=huodong&act=list");
	}
	
	
	function gameupdate($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$gameupdatelist=array();
		$updateinfo=array();
		if($get["e"]=="list")
		{
			$gameupdatelist=$cont->obj->gameupdatelist();
		}else{
			$updateinfo=$cont->obj->updateinfo($get["id"]);
		}
		require_once parse_tmp("/macro_data/gameupdate.html");//引入模版
	}
	
	function addactgameupdate($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$updateinfo=$cont->obj->addactgameupdate($post);
		
		header("Location:/?module=macro_data&methods=gameupdate&e=list");
	}
	
	function query_info($obj)
	{
		
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		if($post)
		{
			$query_info=$cont->obj->query_info($post);
		}
		require_once parse_tmp("/macro_data/query_info.html");//引入模版
	}
	
	function richanghuodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		if(isset($get["id"]))
		{
			$cont->obj->richanghuodong_up($get["id"],$get["e"]);
			header("Location:/?module=macro_data&methods=richanghuodong");
		}
		
		$query_data=$cont->obj->richanghuodong();
		require_once parse_tmp("/macro_data/richanghuodong.html");//引入模版	
	}
	
	function signhuodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$base_sign = $cont->obj->signhuodong();
		require_once parse_tmp("/macro_data/signhuodong.html");//引入模版
	}
	function upsignhuodong($obj){
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$base_sign =$cont->obj->upsignhuodong(true);
		echo "导入成功";
//		header("Location:/?module=macro_data&methods=signhuodong");
	}
	
	function query_destiny($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$query_destiny=array();
		if(isset($post["name"]))
		{
			$query_destiny=$cont->obj->query_destiny($post["name"]);
		}
		require_once parse_tmp("/macro_data/query_destiny.html");//引入模版		
	}
	
	function hero_log($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$hero_log=array();
		if(isset($post["nickname"]) and $post["nickname"])
		{
			$hero_log=$cont->obj->hero_log($post["nickname"]);
		}
		
		if(isset($post["time1"]) and isset($post["time2"]) and $post["time1"])
		{
			$hero_log=$cont->obj->hero_log(false,$post["time1"],$post["time2"]);
		}
		
		$get_module=$cont->obj->get_module();
		require_once parse_tmp("/macro_data/hero_log.html");//引入模版		
	}

	function errgem($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$hero_log=array();
		if(isset($post["nickname"]))
		{
			$hero_log=$cont->obj->equip_gem_list($post["nickname"]);
		}
		require_once parse_tmp("/macro_data/errgem.html");//引入模版	
	}
	
	function roll_clothes($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$roll_clothes=$cont->obj->roll_clothes();
		require_once parse_tmp("/macro_data/roll_clothes.html");//引入模版	
	}
	
	function up01huodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$roll_clothes=$cont->obj->up01huodong($get);	
	}
	
	function up02huodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$roll_clothes=$cont->obj->up02huodong($get);	
	}
	
	function delhuodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$roll_clothes=$cont->obj->delhuodong($get["id"]);	
		echo "删除成功";
	}
	
	function importsyshuodong($obj)
	{
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		$roll_clothes=$cont->obj->importsyshuodong();	

		$this->huodong($obj);
	}
	function email_list($obj){
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		
		$time1=strtotime(trim($post["time1"])." 00:00:00");
		$time2=strtotime(trim($post["time2"])." 23:59:59");
		
		$email = $cont->obj->email_list($post["type"],$post["nickname"],$time1,$time2);
		require_once parse_tmp("/macro_data/email_list.html");//引入模版
	}
	
	function send_email($obj){
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
		if($post["title"]){
			$email = $cont->obj->send_email($post["type"],$get["playerId"],$post["title"]);
		}else{
			require_once parse_tmp("/macro_data/send_email.html");//引入模版
		}
	}
	
	function err_data($obj){
		$cont=new CONT("macro_data");
		$get=$obj->get;
		$post=$obj->post;
	
		$err_data = $cont->obj->err_data();
	
		require_once parse_tmp("/macro_data/err.html");//引入模版
		
	}
}
?>