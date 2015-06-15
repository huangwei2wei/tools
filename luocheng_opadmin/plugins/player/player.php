<?php
namespace plugins\player;
use controllers\controllers as CONT;

class player{

//**********************************人名币玩家**************************************//
function play_rmb($obj)
{
		$cont=new CONT("player");
		$get=$obj->get;

		$play_rmb_lv=$cont->obj->play_rmb_lv();
		$data=$play_rmb_lv["content"];
		$pa=$play_rmb_lv["page"];
		require_once parse_tmp("/player/player_rmb.html");//引入模版

	
}
	
//**********************************活跃玩家(经常登陆)**************************************//
	function play_active($obj)
	{		
		$obj->_bug=0;
		$cont=new CONT("player");
		$get=$obj->get;
		$play_ac_lv=$cont->obj->play_ac_lv($get["type"]);		
		$data=$play_ac_lv["content"];
		$pa=$play_ac_lv["page"];
		require_once parse_tmp("/player/player_Active.html");//引入模版
	}
//************************************************************************//
}
?>