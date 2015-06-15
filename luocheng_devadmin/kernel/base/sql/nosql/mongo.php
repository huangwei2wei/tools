<?php 
namespace kernel\base\sql\nosql;
ini_set('mongo.native_long', 1);

class mongo
{
	//http://www.php.net/manual/en/book.mongo.php 参考资料
	var $mongoobj;
	var $phpmongo;
	var $db;
	var $dbname;
	var $table;
	var $error;

	/**
	 * 连接mongo
	 * @access	Public
	 * @return	mongo obj
	 */
	function mongoconn($host,$port,$db,$username,$password,$rc,$auth)
	{
		try{
			if($auth){
				$uri="mongodb://".$username.":".$password."@".$host.":".$port;
			}else{
				$uri="mongodb://".$host.":".$port;
			}
			
			if($rc){$uri.="/?replicaSet=".$rc;}
			$this->phpmongo = new \MongoClient($uri,array("readPreference" =>\MongoClient::RP_PRIMARY_PREFERRED));
			if (method_exists($this->phpmongo, "setSlaveOkay")) {
				$this->phpmongo->setSlaveOkay(true);
			}
		
		}catch(Exception $e){
			print_r($e);
			exit();
		}
		\MongoCursor::$timeout =0;
		if($db)$this->selectdb($db);
	}
	
	/**
	 * 连接mongo
	 * @access	Public
	 * @return	mongo obj
	 */
	function tmpmongoconn($host,$port,$db,$username,$password,$rc,$auth)
	{
		if($auth){
			$uri="mongodb://".$username.":".$password."@".$host.":".$port;
		}else{
			$uri="mongodb://".$host.":".$port;
		}
		if($rc){$uri.="/?replicaSet=".$rc;}
		
		$this->phpmongo = new \MongoClient($uri,array("readPreference" =>\MongoClient::RP_PRIMARY_PREFERRED));
		if (method_exists($this->phpmongo, "setSlaveOkay")) {
			$this->phpmongo->setSlaveOkay(true);
		}
		//$this->phpmongo->setSlaveOkay(true);
		\MongoCursor::$timeout =0;
		if($db)$this->selectdb($db);
	}
	
	
	/**
	 * 关闭mongo
	 * @access	Public
	 * @return
	 */
	function close()
	{
		$this->phpmongo->close();
	}
	
	/**
	 * 选择数据库
	 * @access	Public
	 * @param	string	$db	库名
	 * @return	$db obj		数据库句柄
	 */
	function selectdb($db)
	{
		$this->dbname=$db;
		return $this->db=$this->phpmongo->$db;
	}
	
	/**
	 * 为选择好的数据库创建用户名和密码
	 * @access	Public
	 * @param	string	$user	库名
	 * @param	string	$pass	密码
	 * @return	$db obj		数据库句柄
	 */
	function adduser($user,$pass)
	{
		return $this->db->execute("function (username, pass, readonly)
		{ db.addUser(username, pass, readonly); }", array($user,$pass,false));
	}
	
	/**
	 * 创建一个数据库（分片适用）
	 * @access	Public
	 * @param	string	$dbname	库名
	 * @return	$db obj		数据库句柄
	 */
	function adddbname($dbname)
	{
		return $this->db->execute("function (dbname)
		{ db.runCommand({enableSharding:dbname}); }", array($dbname));
	}
	
	
	/**
	 * 为表创建分片
	 * @access	Public
	 * @param	string	$tabname	表名 test.tb1
	 * @return	$db obj		数据库句柄
	 */
	function addsharding($tabname,$key)
	{
		return $this->db->execute("function (tabname,select_key)
		{ db.runCommand({shardcollection:tabname,key:{select_key:1}}); }", array($tabname,$key));
	}
	/**
	 * 选择数据表或数据集
	 * @access	Public
	 * @param	string	$table	表名
	 * @return	$table obj		数据表句柄
	 */
	function selecttable($table)
	{
		return $this->table=$this->db->$table; 
	}

	    /** 
    * 创建索引：如索引已存在，则返回。 
    * 
    * 参数： 
	 * 设置索引
	 * @access	Public
	 * @param	string	$table	            表名
	 * @param	array	$indexarr	          条件	$indexarr = array( "i" => 1 );  1顺序 -1倒序 i是列
	 * @return	array	res		              信息
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
    function ensureIndex($table, $index,$index_name=array())
    {  
    	$this->selecttable($table);
    	return $this->table->ensureIndex($index,$index_name);
    }  
	
	
	
	/**
	 * 获取数据
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	返回字段	$fields=array("title"=>true,"online"=>true);
	 * @return	array	res		数据结果
	 */
	function find($table,$query=array(),$fields=array())
	{
		$this->selecttable($table);
		//$this->table->findOne(); //返回table集合中全部文档 
		return $this->table->find($query,$fields);
	}
	
	/**
	 * 获取数据只有一条
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	返回字段	$fields=array("title"=>true,"online"=>true);
	 * @return	array	res		数据结果
	 */
	function findone($table,$query=array(),$fields=array())
	{
		$this->selecttable($table);
		//$this->table->findOne(); //返回table集合中第一个文档 
		return $this->table->findOne($query,$fields);
	}
	
	/**
	 * 插入数据
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$fields	插入字段	$fields=array("title"=>true,"online"=>true);
	 * @return	array	res		插入信息
	 */
	function insert($table,$fields)
	{
		$collection_list=array(
				"user_goods"=>"goods_user_id",
				"user_goods_gem"=>"goods_gem_id",
				"user_info"=>"user_id",
		);
		
		if(isset($fields["id"])==false)
		{
			if(isset($collection_list[$table])){
				$fields[$collection_list[$table]]=$this->max_id($table);
			}else{
				$fields["id"]=$this->max_id($table);
			}
		}
		$this->selecttable($table);
		if($table=="user_goods"){
			if($this->table->insert($fields))
			{
				return 	$fields[$collection_list[$table]];
			}else{
				return false;	
			}
		}else{
			return $this->table->insert($fields); 
		}
	}

	/**
	 * 更新数据
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	  $query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	  $fields	更新字段	$fields=array("title"=>true,"online"=>true);
	 * @return	array	  res		插入信息
	 */
	function update($table,$fields,$query,$actflat='$set')
	{
		$this->selecttable($table);
		return $this->table->update($query,array($actflat=>$fields),array("multiple"=>true));
	}
	
	/**
	 * 高度自定义更新数据
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @param	array	$fields	更新字段	$fields=array("title"=>true,"online"=>true);
	 * @return	array	res		插入信息
	 */
	function update_free($table,$fields,$query)
	{
		$this->selecttable($table);
		return $this->table->update($query,$fields,array("multiple"=>true));
	}
	
	/**
	 * 删除数据
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$query	条件	$query = array( "title" =>"XKCD"); 
	 * @return	array	res		插入信息
	 */
	
	function delete($table,$query=array())
	{   
         
		$this->selecttable($table);
		return $this->table->remove($query); 
	}
	
	/**
	 *  删除集合
	 * @access	Public
	 * @param	string	$table	表名
	 */
	function drop($table)
	{   
		$this->selecttable($table);
		return $this->table->drop(); 
	}
	
	/**
	 * 设置索引
	 * @access	Public
	 * @param	string	$table	表名
	 * @param	array	$indexarr	条件	$indexarr = array( "i" => 1 );  1顺序 -1倒序 i是列
	 * @return	array	res		信息
	 */
	function setindex($table,$indexarr)
	{
		$this->selecttable($table);
		// 为i "这一列"加索引 降序排列
		//$coll->ensureIndex( array( "i" => 1 ) );
		// 为i "这一列"加索引 降序排列 j升序 
		//$coll->ensureIndex( array( "i" => -1, "j" => 1 ) ); 
		return $this->table->ensureIndex($indexarr); 
	}
	
	/**
	 * 数据表总数
	 * @access	Public
	 * @param	string	$table	表名
	 * @return	array	res		信息
	 */
	function count($table=false)
	{
		return $this->table->count(); //返回table集合中文档的数量
	}
	
	/**
	 * 此刻结果集不变导出  用法是在get函数下一条语句使用
	 * @access	Public
	 * @return	无
	 */
	function nowres()
	{
		return $this->table->snapshot();//要或的一个不变的结果集加这句，否则外面不断更新会影响此结果集
	}
	
	/**
	 * 数据表总数
	 * @access	Public
	 * @param	string	$res	将结果转换成数组
	 * @return	array	res		结果数组
	 */
	function restoarray($res)
	{
		return iterator_to_array($res);
	}
	
	
	function max_id($table)
	{ 
		$data  = $this->db->command(array( 'findandmodify' => 'autoid_system',
				'query'=> array("collection"=>$table),
				'limit'=> 1,
				"new"=>true,
				'update'=> array('$inc' => array($table.'_autoid' => 1)) ));
		
		if(isset($data["value"])==false || (isset($data["value"]) && isset($data["value"][$table.'_autoid'])==false)){
			$this->selecttable('autoid_system');
			$this->table->insert(array("collection"=>$table,$table.'_autoid'=>0));
			
			$data  = $this->db->command(array( 'findandmodify' => 'autoid_system',
					'query'=> array("collection"=>$table),
					'limit'=> 1,
					"new"=>true,
					'update'=> array('$inc' => array($table.'_autoid' => 1)) ));
		}
		
		return $data["value"][$table.'_autoid'];
	}
	
	function getCollectionNames()
	{
		return $this->db->getCollectionNames();
	}
	
	/**
	 * 数据分组
	 * @access	Public
	 * @param	string	$keys		要分组的字段
	 * @param	string	$condition	查询条件
	 * @param	string	$sort_key	排序字段
	 * @param	string	$sort_type	排序类型 SORT_DESC SORT_ASC
	 */
	function group($table,$keys,$condition=array(),$sort_key=false,$sort_type=SORT_DESC)
	{
		if(!$sort_key){$sort_key=$keys;}
		$this->selecttable($table);
		$gkeys = array($keys => 1);
		
		if($keys==$sort_key)
		{
			$initial = array("items" => array($keys=>1),"index"=>-1,"total"=>0); 
		}else{
			$initial = array("items" => array($keys=>1,$sort_key=>1),"index"=>-1,"total"=>0);
		}
		
		$reduce = "function (obj, prev) {prev.total++;}"; 
		
		$g = $this->table->group($gkeys, $initial, $reduce,$condition); 
		
		foreach ($g["retval"] as $key => $row) 
		{
            $accuracy[$key] = $row[$sort_key];
		}
		//SORT_ASC SORT_DESC
		if(count($accuracy)>1)
		{
			array_multisort($accuracy, $sort_type,$g["retval"]);
		}
		return $g;
	}
	
	/**
	* 数据统计（推荐使用这个）
	* @access    Public
	* @param    string    table      表名
	* @param    list    groupkey      要分组的字段    ["a","b"]
	* @param    list    countkeys     要统计的字段    ["a","b"]
	* @param    dict    query        查询条件    {'author':"dave"}
	* @param    dict    show        显示类容    {'author':1}
	* @param    dict    sort        排序   {'title':1,'author':1}
	* @param    int    skip    从第几条开始返回
	* @param    int    limit    从第skip条的返回行数 比如 5
	* @param    int    mtype    统计类型 1统计记录数 不为1统计字段值总数
	*/
	function aggregate($table,$groupkey,$countkeys,$query=false,$show=false,$sort=false,$skip=0,$limit=0,$mtype=1)
	{
        
        $this->selecttable($table);
        $group=array();
		$aggregatedata=array();
		if($show){
            $show=array('$project'=>$show);  #{title : 1 ,author : 1}
			$aggregatedata[]=$show;
		}
		
        if($query){
            $query=array('$match'=>$query);
			$aggregatedata[]=$query;
		}
		
        if($sort){
            $sort=array('$sort'=>$sort);    #{ age : -1, posts: 1 }
			$aggregatedata[]=$sort;
		}
		
        if($groupkey){
            $group['$group']=array('_id'=>array());
            foreach($groupkey as $key =>$value){
                $group['$group']["_id"][$value]='$'.$value;
			}
                
            if($countkeys){
                if($mtype==1){
					foreach($countkeys as $key =>$value){
                        $group['$group'][$value]= array('$sum'=>$mtype);
					}
				}else{
                    foreach($countkeys as $key =>$value){
                        $group['$group'][$value]= array('$sum'=>'$'.$value);
					}
				}
			}
			$aggregatedata[]=$group;
		}
		
        if($limit){
            $limit=array('$limit'=>$limit);   #整形 比如5
			$aggregatedata[]=$limit;
		}
            
        if($skip){
            $skip=array('$skip'=>$skip);   #整形 比如5
			$aggregatedata[]=$skip;
		}
		$rundata=$this->table->aggregate($aggregatedata);
        return $rundata["ok"]==1?$rundata["result"]:array();
	}
}
?>