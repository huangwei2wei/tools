<?php 
namespace plugins\index;
use controllers\module as MODULE;
class iindex extends module
{
	
	function show()
	{
		$data=array();
		$this->gamekefulist($data);
		$this->kefucommonlist($data);

		return $data;
	}
	
	//游戏列表
	function gamekefulist(&$data)
	{
		$newgame=array();
		$sql="SELECT * FROM `game` WHERE status=1 ORDER BY orders desc"; 
		$gamedata=dbarr($sql);		
		$data["gamekefulist"]=$gamedata;
	}
	
	//客服常见问题列表
	function kefucommonlist(&$data){
		$sql = "SELECT kefuinfo.* FROM kefuinfo
				WHERE kefuinfo.status=1
				ORDER BY kefuinfo.orderd DESC ,kefuinfo.createtime DESC
				LIMIT 10";
		$gamedata=dbarr($sql);
		
	
		$data["kefucommonlist"]=$gamedata;
	
	}
	

}
?>