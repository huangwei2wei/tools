<?php
		namespace plugins\activation;
		use controllers\controllers as CONT;
		class activation
		{
//*****************************************************添加激活码*******************************************************

	function add_activation_views($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
		unset($get['view_id']);
		$f_name = dbfield_note('base4_code');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=activation&methods=activation_add_submit">
				<table style="background-color:#CCC;width:300px">';
			
		$get_goods_list=$cont->obj->get_goods();
		
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] !='id')
				{
					$html .= '<tr>';
					
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'';
					if($value['COLUMN_NAME'] =='cd_id' )
					{
						$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="'.$view_id.'" style="width:150px;border:1px solid #666666;text-align:center" readOnly/>';
					}else{
					$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:150px;"/></td>';
					}
					$html .= '</td><td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=3 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/activation_code/add_jihuoma.html");//引入模版
	}
//添加修改//
	
	function activation_add_submit($obj)
	{
		$cont=new CONT("activation");
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

           dbinsert('base4_code', $a_fields, $a_values,1);
           $get['view_id'] =lastlyid('base4_code',"id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base4_code',$u_fields,$u_values,'id='.$post['u|id']);                 
           echo "更新成功！";                    
           $get['view_id'] =$post['u|id'];
        }
	}
	
	
   //修改*//
	function activation_show($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$game1=dbarr("select a.spree_id,a.cd_id,a.code,a.spree_id,a.status,b.id,b.goods_name from  base_goods  as b
	inner join base4_code as a  on b.id=a.spree_id WHERE cd_id= ".$view_id);
		$f_name= dbfield_note('base4_code');
		$this->foo ($f_name);
		$this->foo ($game1);
		$html = '<form method="post" action="/?module=activation&methods=activation_add_submit">
		<p style="text-align:center;">修改激活码数据</p>
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
	   <a href="/?module=activation&methods=activation_list_views">【返回】</a>
	   </td>
	   </tr>';
	   
	   $html.="</table></form>";
	   require_once parse_tmp("/activation_code/add_jihuoma.html");//引入模版
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
	
	function building_code($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
		
		if(intval($post["mymax"])<=0){
			echo "<script language=javascript>alert('礼包数量必须大于0');history.back();</script>";
			return false;	
		}
		
		if(strlen($post["spree_name"])<3){
			echo "<script language=javascript>alert('礼包内容不对！');history.back();</script>";
			return false;
		}
		
		if(strlen($post["spree_name"])<3){
			echo "<script language=javascript>alert('礼包名称不对！');history.back();</script>";
			return false;
		}
		
		$ret = $cont->obj->building_code($post);
		if (ret)
			echo "<script language=javascript>alert('添加成功！');history.back();</script>";
		else 
			echo "<script language=javascript>alert('添加失败，礼包名称已存在！');history.back();</script>";
	}
	
	

	function user_code($obj)
	{
		$obj->_bug=0;
		$cont=new CONT("activation");
		$get=$obj->get;
		$get["page"]=isset($get["page"])?intval($get["page"]):1;
		$get["page"]=$get["page"]<=0?1:$get["page"];
// 		$get_code=$cont->obj->get_code($get["page"]);
// 		$data=$get_code["content"];
// 		$pa=$get_code["page"];
// 		$hero=$get_code["hero"];
		$code_list = $cont->obj->user_code_count();
		require_once parse_tmp("/activation_code/jihuoma.html");//引入模版
	}
	
	function down_excel($obj)
	{
		$cont=new CONT("activation");
		$get_code=$cont->obj->down_excel();
		$excellist=array();
		foreach($get_code as $key=>$value)
		{
			$excellist[]=array($value["spree_name"],$value["code"],$value["status"]);	
		}
		$this->outputXlsHeader($excellist,"礼包码".date("Y-m-d",time()));
	}
	
	function outputXlsHeader($data,$file_name = 'export')
	{
		header('Content-Type: text/xls'); 
		header ( "Content-type:application/vnd.ms-excel;charset=utf-8" );
		$str = mb_convert_encoding($file_name, 'gbk', 'utf-8'); 
		header('Content-Disposition:attachment;filename="'.$str .'.xls"');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		$table_data = '<table border="1">';
		
		foreach($data as $line)
		{
			$table_data .= '<tr>';
			foreach ($line as $key => &$item)
			{
				//$item = mb_convert_encoding($item, 'gbk', 'utf-8'); 
				$table_data .= '<td>' . $item . '</td>';
			}
			$table_data .= '</tr>';
		}
		$table_data .='</table>';
		echo $table_data;
		die();
	}
	
	function add_activation_code($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
			
		$get_goods_list=$cont->obj->get_goods();
	
		
		require_once parse_tmp("/activation_code/add_jihuoma.html");//引入模版
	}
	
	function code_list($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
			
		$get_goods_list=$cont->obj->get_goods();
		$goods_list = array();
		foreach ($get_goods_list as $key=>$value) {
			$goods_list[$value["goods_ext_id"]] = $value["name"];
		}
		
		
		$spree_name = $get["spree_name"];
		$code_list = $cont->obj->code_list($spree_name);
		
		require_once parse_tmp("/activation_code/activation_code_list.html");//引入模版
	}
	
	function code_delete($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
			
		$spree_name = $get["spree_name"];
		//print($spree_name);
		$ret = $cont->obj->code_delete($spree_name);
		if (ret) {
			echo "<script language=javascript>alert('删除成功！');history.back();</script>";
		}else {
			echo "<script language=javascript>alert('删除失败！');history.back();</script>";
		}
		
		//require_once parse_tmp("/activation_code/activation_code_list.html");//引入模版
	}
	
	function append_code($obj)
	{
		$cont=new CONT("activation");
		$post=$obj->post;
		$get=$obj->get;
			
		$spree_name = $get["spree_name"];
		$append_num = $post["append_num"]?intval($post["append_num"]):0;
	
		if ($append_num){
			$cont->obj->code_append($spree_name, $append_num);
			echo "<script language=javascript>alert('追加成功！');history.back();</script>";
		}
			
		require_once parse_tmp("/activation_code/append_activation_code.html");//引入模版
	}
}
?>