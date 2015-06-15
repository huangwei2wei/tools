<?php
//error_reporting(E_ERROR);
$cont_x=1;
require("kernel/base/lib/public.php");


/*
 * 这个文件可以不用改
 * ======================说明================================
 * 模型接口文件必须以i开头，类名必须和文件名一致，并定义相应的命名空间
 * 模型接口类和接口函数必须以i开头
 * 调用模块地址方式 http://localhost/index.php?module=模块目录&methods=模块方法
 * 每个有两个目录，分别是M（module）和V（views），M在/module/plugins里，比如首页（index.php）
 * 那么就在/module/plugins/index/iindex.php里，这里要注意：
 * index是目录名，iindex.php是接口文件，模型（module）里只有有一个接口，接口名是文件名前面加i，即变成了iindex.php
 * V在/views/里，即视图模块，比如首页（index.php）
 * 那么就在/views/index/index.php里，这里要注意：
 * index是目录名，index.php是文件名，视图（views）里只有有一个接口，即跟文件名一致，由于不在模型里，不必加i
 * 
 * 数据库支持 sql支持mysql、mysqli，nosql支持memcache、mongodb
 * sql可以打开多个库  nosql同样可以打开多个库，详细配置见config.php和config_storage.php
 * 
 * memcached 句柄 $mem 比如 $mem->set("key","value");
 * 
 * mongo 句柄 $mongo 比如 $mongo->selectdb($db);
 */
?>