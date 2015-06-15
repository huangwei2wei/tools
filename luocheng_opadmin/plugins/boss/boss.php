<?php
namespace plugins\boss;
use controllers\controllers as CONT;
use kernel\base\sql\nosql\mongo as TMP_MONGO;
use kernel\base\base_class\string as STRING;
set_time_limit(0);

class boss{
	//列表显示
     function boss_list_views($obj)
		{
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		$boss_list=$cont->obj->boss_list_views();
		$goods_ary5=array();
		$goods_ary5=$cont->obj->getdata5(isset($post['d_id'])?$post['d_id']:false,isset($post['d_name'])?$post['d_name']:false);
		
		if($goods_ary5)
		{ 
		  $currentPage= isset($get['page'])?$get['page']:0;
		  if(!$currentPage or isset($post['d_id']) or isset($post['d_name']))
		  {
		      $currentPage=1;
		  }
		$this->foo($goods_ary5);
		$goods_page =spage($goods_ary5,10,$currentPage);   
		$page_string=pageft2($currentPage,$goods_page['totalPage']);  
		$html = '<p></p><table align="center" id="bosslist" style="width:100%">';
		$html.= '
		<tr>
		<td  bgcolor="#CCCCCC" width="10%"; >运营商ID</td>
		<td bgcolor="#CCCCCC"  width="20%"; >运营商登录名</td>
		<td bgcolor="#CCCCCC" width="20%"; >运营商名称</td>
		<td bgcolor="#CCCCCC" width="20%"; >公司名称</td>	
		<td bgcolor="#CCCCCC" >运营商识别码</td>	
		<td bgcolor="#CCCCCC"  >运营商电话</td>		
		<td bgcolor="#CCCCCC" width="30%";  >操作</td>	
		</tr>';
	if (is_array($goods_page))
	{
		foreach ($goods_page['content'] as $key => $value)
		{
			$html .= '<tr>';
			$html .= '<td>'.$value['id'].'</td>';
			$html .= '<td>'.$value['operators'].'</td>';
			$html .= '<td >'.$value['name'].'</td>';
			$html .= '<td >'.$value['company'].'</td>';						
			$html .= '<td >'.$value['identifier'].'</td>';
			$html .= '<td>                    
			<div style="width:100px;height:100%;word-wrap:break-word;" name="s_'.$value['name'].'" data="'.$value['boss'].'" 
			ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=operators\',\'boss\',\'name\','.$value["name"].','.strlen($value['boss']).')">'.$value['boss'].'
			</div>
			</td>';
			$html .= '<td >
			 <a href="/?module=service_manager&methods=open_service_list&operators_id='.$value['id'].'">【开服列表】</a>&nbsp; 
			 <a href="/?module=login&methods=purview&view_id='.$value['id'].'">【管理员】</a>&nbsp;
			<a href="/?module=boss&methods=boss_show&view_id='.$value['id'].'">【编辑】</a>
			</td>';
			$html .= '</tr>';
		}
	}
     $html .='<tr><td colspan=9>'.$page_string.'&nbsp;10服/页</td></tr>';
     $html .= "</table>";
		}
		$boss_data=$boss_list;
	require_once parse_tmp("/boss/boss.html");//引入模版
	}
	    //添加************************************************************************************************************
	function add_boss_list_views($obj)
	{
			$cont=new CONT("boss");
			$post=$obj->post;
			$get=$obj->get;
			//运营商添加
		
			unset($get['view_id']);
			$f_name = dbfield_note('operators','operators');
			$this->foo($f_name);
			$html = '<form method="post" action="/?module=boss&methods=boss_add_submit">
			<table align="center" style="width:48%">';
			if (is_array($f_name))
			{
					foreach ($f_name as $key => $value)
					{
					if( $value['COLUMN_NAME'] !='sharing_ratio_c'and $value['COLUMN_NAME'] !='id'and $value['COLUMN_NAME'] !='condition'and $value['COLUMN_NAME'] !='sharing_ratio' and $value['COLUMN_NAME'] !='purview')
					  {
						$html .= '<tr>';
						$html .= '<td style="background-color:#8FBC8F;width:150px;">'.$value['COLUMN_COMMENT'].'</td>';
						
						$html .= '<td style="background-color:#FFF;">';
						if($value['COLUMN_NAME'] =='company')
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="网络科技有限公司" style="width:220px;" />';
						}elseif($value['COLUMN_NAME'] =='name')
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="admin" style="width:220px;" />';
						}elseif($value['COLUMN_NAME'] =='operators')
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="admin"style="width:220px;" />';
						}elseif($value['COLUMN_NAME'] =='identifier')
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="21232f297a57a5a743894a0e4a801fc3" style="width:220px;"/>';
						}elseif($value['COLUMN_NAME'] =='platform')
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="zzql.com" style="width:220px;"/>';
						}else
						{
							$html .= '<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:220px;" />';
						}
						
						$html .= '</td><td style="background-color:#8FBC8F;width:150px;">'.$value['COLUMN_NAME'].'</td>';
						$html .= '</tr>';
					  }

					}
				}
				$html.='<td colspan=3 style="text-align:center;background-color:#FFF;">
				<input type="submit" name="boss_add_submit" value="确定添加"/></td>';
				
				$html .= "</table></form>";
				require_once parse_tmp("/boss/add_boss.html");//引入模版
	         }
//*******************************************运营商添加修改*******************************************//
	
	function boss_add_submit($obj)
	{
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		//提交
       foreach ($post as $key => $value)
       {
			$pre = substr($key,0,2);
			if ($pre == 'a|') {
				if(substr($key,2)!="id")
				{
					$a_fields[] = substr($key,2);
					$a_values[] = $value;
				}
			}elseif($pre == 'u|') {
				$u_fields[] = substr($key,2);
				$u_values[] = $value;
			}
		}

        if($a_fields && $a_values)
        {

           dbinsert('operators', $a_fields, $a_values,1,'operators');
           $get['view_id'] =lastlyid('operators',"id",'operators');
           echo "添加成功！";
        }
         
        if($u_fields && $u_values)
        {
           dbupdate('operators',$u_fields,$u_values,'id='.$post['u|id'],1,'operators');                 
           echo "更新成功！";                    
           $get['view_id'] =$post['u|id'];
        }
	}
	
	
   //修改运营商*//
	function boss_show($obj)
	{
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$game1=dbarr("SELECT id,username,name,company,identifier,password,boss FROM `operators` WHERE id= ".$view_id,0,"operators");
		$f_name= dbfield_note('operators','operators');
		$this->foo ($f_name);
		$this->foo ($game1);
		$html = '<form method="post" action="/?module=boss&methods=boss_add_submit">
		<p style="text-align:center;">修改运营商数据</p>
		<table style="background-color:#CCC;width:48%">';
		//style="border:1px solid #666666;text-align:center" readOnly
		foreach ((array)$game1 as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	      foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#8FBC8F;width:150px;">'.$v2['COLUMN_COMMENT'].'</td>';						
						$html.= '<td style="background-color:#FFF;width:240px;">';
                      
						if($k=="id")
						{
							$html.= '<input type ="text" name="u|'.$k.'" value="'.$v.'"  style="border:1px solid #666666;width:240px;text-align:center" readOnly ';
						}
						elseif( $k=="username"  or $k=="name" or $k=="company")
						{
							$html.= '<span>'.$v.'</span>';
						}else{
							$html.= '<input type ="text" name="u|'.$k.'" value="'.$v.'"  style="width:240px;"/>';
						}
						$html.= '</td><td style="background-color:#8FBC8F;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
	   <input type="submit" name="add_submit" value="确定更新"/>
	   <a href="/?module=boss&methods=boss_list_views">【返回】</a>
	   </td>
	   </tr>';
	   
	   $html.="</table></form>";
	   require_once parse_tmp("/boss/gy.html");//引入模版
	}
    
	//删除
	function del_boss_list_views($obj)
	{
		$obj->_bug=0;
		$get=$obj->get;
		$cont=new CONT("boss");
		$cont->obj->del_boss_list($get["id"]);
		$this->boss_list_views($obj);
	}
	function foo(&$ary)
	{
		if (is_array($ary)) {
			foreach ($ary as $key => &$value) {
				if (is_array($value)) {
					foreach ($value as $k => $v) {
						if (is_int($k)) {
							unset($value[$k]);
						}
					}
				}
			}
			return $ary;
		}else {
			return $ary;
		}
	}
//**************************************************游戏服****************************************************//
	function game_fu($obj)
	{
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		$game=array();
		/*$game=$cont->obj->gamefu($post['z_name'],$post['$z_id']);    
		$view_id =$get['view_id'];
		$goods_ary6=dbarr("SELECT * FROM `server_list` WHERE `operators_id`=".$view_id." ORDER BY `server_list`.`id`  DESC",0,"operators");
		$f_name= dbfield_note('server_list','operators');
		$this->foo ($f_name);
		$this->foo ($goods_ary6);
		if($goods_ary6)
		{ 
			$currentPage= $get['page'];
			if(!$currentPage or isset($post['z_name']) or isset($post['z_id']))
			{
			  $currentPage=1;
			}

			$this->foo($goods_ary6);
			$goods_page =spage($goods_ary6,30,$currentPage);
			$page_string=pageft2($currentPage,$goods_page['totalPage']);
     	}	     
		$server_id=dbarr("SELECT `server_id`  FROM `server_list` WHERE `operators_id` =$view_id ORDER BY `server_list`.`server_id`  DESC limit 1",0,"operators");
		if(isset($server_id[0]))
		{
			$view_id2=$server_id[0]["server_id"]+1;
		}else{
			$view_id2=1;
		}
		
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		//实例一个mongo
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],"all_user",$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"]);
		//$tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],"all_user");
		//$tmp_mongo->db->authenticate($operators[0]["mongo_username"],  $operators[0]["mongo_password"]);
		$tmp_server_list=$tmp_mongo->find("server_list",array("operators_id"=>intval($view_id)));
		$server_list=array();
		foreach($tmp_server_list as $value)
		{
			$server_list[$value["server_id"]]=$value;
		}
		*/
		
		
		require_once parse_tmp("/boss/open_service_list.html");//引入模版
   }
   
   function upsestatus($obj)
   {
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		$operators_id =intval($get['view_id']);
		$server_id =intval($get['view_id2']);
		$status =intval($get['view_id3']);
		
		$operators=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".intval($_SESSION["userid"]),0,"operators");
		//实例一个mongo
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],"all_user",$operators[0]["mongo_username"],  $operators[0]["mongo_password"],  $operators[0]["mongo_line"],$operators[0]["mongo_auth"]);
		//$tmp_mongo->tmpmongoconn($operators[0]["server_mongo_ip"],$operators[0]["server_mongo_port"],"all_user");
		//$tmp_mongo->db->authenticate($operators[0]["mongo_username"],  $operators[0]["mongo_password"]);
		$tmp_server_list=$tmp_mongo->update("server_list",array("status"=>$status),array("operators_id"=>intval($operators_id),"server_id"=>intval($server_id)));
		
		$this->game_fu($obj);
	}
	
	//***************************添加游戏服*******************************//			
	function game_addshow($obj)
	{
			$cont=new CONT("boss");
			$post=$obj->post;
			$get=$obj->get;
			//添加
			$view_id=$get['view_id'];
			$view_id2=$get['view_id2'];
			$f_name= dbfield_note('server_list','operators');
			$this->foo ($f_name);
			$html = '<form method="post" action="/?module=boss&methods=game_submit">
			<p style="text-align:center;">游戏服添加
			</p>
			<table align="center" style="background-color:#CCC;width:35%">';
			
			if (is_array($f_name))
			{
				foreach ($f_name as $key => $value)
				{
					if($value['COLUMN_NAME'] !='id' and $value['COLUMN_NAME'] !='pay' and $value['COLUMN_NAME'] !='month_pay' )
				  	{
						$html .= '<tr>';
						$html .= '<td style="background-color:#8FBC8F;width:150px;">'.$value['COLUMN_COMMENT'].'</td>';
						$html .= '<td style="background-color:#FFF;width:150px">';
						if($value['COLUMN_NAME'] =='operators_id' )
						{
							$html .= '<input type ="text" name="'.$value['COLUMN_NAME'].'" value="'.$view_id.'" style="width:150px;border:1px solid #666666;text-align:center" readOnly/>';
						}elseif($value['COLUMN_NAME'] =='server_id'){
							$html .= '<input type ="text" name="'.$value['COLUMN_NAME'].'" onkeyup="servers(this);"  value="'.$view_id2.'" style="width:150px;border:1px solid #666666;text-align:center"/>';
						}elseif($value['COLUMN_NAME'] =='mongo_db' ){
							$html .= '<input id="mongodbs" type ="text" name="'.$value['COLUMN_NAME'].'" value="s_'.$view_id.'_'.$view_id2.'" style="width:150px;border:1px solid #666666;text-align:center" readOnly/>';
						}else{
							$html .= '<input type ="text" name="'.$value['COLUMN_NAME'].'" value="" style="width:150px;"/>';
						}
						$html .= '</td><td style="background-color:#8FBC8F;">'.$value['COLUMN_NAME'].'</td>';
						$html .= '</tr>';
				  }
				}  
			}
			
			if($_SESSION["operators_id"]==1){
				$html.='<td colspan=3 style="text-align:center;background-color:#FFF;"><input type="submit" name="game_submit" value="确定添加"/><a href="/?module=boss&methods=boss_list_views">【返回运营商列表】</a>	</td>';
			}
			
			$html .= "</table></form>";
			require_once parse_tmp("/boss/gy.html");//引入模版
		}
//********************************添加游戏服2*******************************//			
	function game_submit($obj)
	{
		set_time_limit(0);
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		//提交
   		
		//添加sql的服
		$dbname=trim($post["mongo_db"]);
		$a_fields=array("operators_id","server_id","server_name","mongo_db");
		$a_values=array(trim($post["operators_id"]),trim($post["server_id"]),trim($post["server_name"]),$dbname);
		
		if(isset($post["id"]))
        {           	        
           dbupdate('server_list',$u_fields,$u_values,'id='.$post['id'],1,'operators');
           
           echo "更新成功！";  
		   return false;
         }
		 
		$tmp=dbarr("SELECT `server_mongo_ip`,`server_mongo_port`,`mongo_username`,`mongo_password`,`mongo_line`,`mongo_auth` FROM `operators` WHERE `id`=".trim($post["operators_id"]),0,"operators");
		

		//实例一个mongo
		$tmp_mongo=new TMP_MONGO;
		$tmp_mongo->tmpmongoconn($tmp[0]["server_mongo_ip"],$tmp[0]["server_mongo_port"],$dbname,"","",$tmp[0]["mongo_line"],"",$tmp[0]["mongo_auth"]);
		 
	   //添加一个用户名
	   $tmp_mongo->adduser($tmp[0]["mongo_username"],$tmp[0]["mongo_password"]);
	   create_index($tmp_mongo);

		dbinsert('server_list', $a_fields, $a_values,1,'operators');
		
		//添加all_user库中的开服列表
		$tmp_mongo->$this->selectdb("all_user");
		$tmp_mongo->db->authenticate($operators[0]["mongo_username"],$operators[0]["mongo_password"]);
		
		$aliases_server_id=intval(str_replace(array("封测","服","电信","联通"),"",$a_values[2]));
		
		$server_data=array(
			"aliases_server_id"=>$aliases_server_id,
			"combined_service"=>0,
			"combined_service_list"=> array(),
			"mongoname"=> $a_values[5],
			"operators_id"=>intval($a_values[0]),
			"server_id"=>intval($a_values[1]),
			"server_name"=>$a_values[2],
			"status"=>0,
			"today"=>date("Y-m-d",time())

		);
		$tmp_mongo->insert("server_list",$server_data);
		echo "添加成功！";
	}
	//***********************************修改游戏服***********************************************************//			
	function game_show($obj)
	{
		$cont=new CONT("boss");
		$post=$obj->post;
		$get=$obj->get;
		$view_id=$get['view_id'];
		$view_id2=$get['view_id2'];
		$game2=dbarr("SELECT * FROM `server_list` WHERE `operators_id` =$view_id and `server_id`=$view_id2",0,"operators");		
		$f_name= dbfield_note('server_list','operators');
		$this->foo ($f_name);
		$this->foo ($game2);
		$html = '<form method="post" action="/?module=boss&methods=game_submit">
		<td style="text-align:center;">修改游戏服数据</td>
		<table align="center" style="background-color:#CCC;width:35%">';		
		foreach ((array)$game2 as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	      		foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '</td><td style="background-color:#8FBC8F;">'.$v2['COLUMN_COMMENT'].'</td>';
						
						$html.= '<td style="background-color:#FFF;width:150px;">';
						if($k=="id"){
							$html.= '<input type ="hidden" name="'.$k.'" value="'.$v.'" />';
							continue;
						}elseif($k=="server_ip"  or $k=="operators_id"  or $k=="server_id" or $k=="month_pay" or $k=="pay" or $k=="mongo_db")
						{
							$html.= '<span>'.$v.'</span>';
						}else{
							$html.= '<input type ="text" name="'.$k.'" value="'.$v.'" style="width:200px;"/>';
						}
						$html.= '<td style="background-color:#8FBC8F;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
	   <input type="submit" name="add_submit" value="确定更新"/>
	   <a href="/?module=boss&methods=game_fu&view_id='.$value['operators_id'].'">【返回】</a>
	   </td>
	   </tr>';
	   
	   $html.="</table></form>";
	   require_once parse_tmp("/boss/gy.html");//引入模版
	}


}
?>