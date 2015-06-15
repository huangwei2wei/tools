<?php
namespace plugins\db;
use controllers\controllers as CONT;
use kernel\base\sql\nosql\mongo as TMP_MONGO;
use kernel\base\base_class\string as STRING;
set_time_limit(0);

class db{
	//列表显示
	function index($obj)
	{
		$cont=new CONT("db");
		$collection_list=$cont->obj->collection_list();
		require_once parse_tmp("/db/collection_list.html");//引入模版
	}
}
?>