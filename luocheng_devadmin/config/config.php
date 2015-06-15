<?php 
define("TEMPLATE","default");//启用那个模板

define("_START_MYSQL",1);		//1启用 0不启用
define("_START_ORACLE",0);		//1启用 0不启用
define("_START_SQLSERVER",0);	//1启用 0不启用
define("START_MYSQLTONOSQL",1);	//1启用 0不启用

define("_START_MEMCACHE",0);		//1启用 0不启用
define("_START_MONGO",1);		//1启用 0不启用

define("_NOVICE_MAP",json_encode(array(16,17,18,19)));		//新手地图ID
define("GAME_DIR","/www/web/game/");//游戏在服务器的目录
define("ADMINWEBURL","admin1.zzql.com");//后端地址


//0关闭，1启用 缓存数据库语句即计算语句执行时间，缓存POST，GET,SESSION，并在网页最后输出
define("CACHEDB",1);
define("DOMAIN",domain());
define("WEBNAME","kf");
define("WEBWWW","test");
define("ALL_56UU_KEY","ajdflkadjfklajfklasdfjkalsdfjalksd4654684");
?>