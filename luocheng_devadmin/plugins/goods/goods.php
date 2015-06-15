<?php
namespace plugins\goods;
use controllers\controllers as CONT;
use kernel\base\sql\mongo as TMP_MONGO;

class goods{
	function base($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$goods_ary=array();
		$goods_ary=$cont->obj->getdata($post['g_id'],$post['g_name']);

		if($goods_ary)
		{ 
		  $currentPage= $get['page'];
		  if(!$currentPage or isset($post['g_id']) or isset($post['g_name']))
		  {
		      $currentPage=1;
		  }
		   $this->foo($goods_ary);
		   $goods_page =spage($goods_ary,15,$currentPage);
		   $page_string=pageft2($currentPage,$goods_page['totalPage']);
		   $html = '<p></p><table style="background-color:#CCC;width:100%">';
		   $html.= '<tr>
		   <td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">物品ID</td>
		   <td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">物品名称</td>


		   </tr>';
			if (is_array($goods_page))
			{
				foreach ($goods_page['content'] as $key => $value)
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#BE77FF;text-align:center;">'.$value['id'].'</td>';
					$html .= '<td style="background-color:#40c0ff;text-align:center;"><a href = "/?module=goods&methods=view_show&view_id='.$value['id'].'">'.$value['goods_name'].'</></td>';
					$html .= '</tr>';
				}
			}
		     $html .='<tr><td colspan=2 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
		     $html .= "</table>";
		}
		require_once parse_tmp("/goods/goods.html");//引入模版
	}
	
	function addshow($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//添加
		unset($get['view_id']);
		$f_name = dbfield_note('base_goods');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=goods&methods=add_submit">
		<p style="text-align:center;">物品添加</p>
		<table style="background-color:#CCC;width:100%">';
			
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] !='id')
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
					$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/goods/goods.html");//引入模版
	}
	
	function add_submit($obj)
	{
		$cont=new CONT("goods");
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

           dbinsert('base_goods', $a_fields, $a_values,1);
           $get['view_id'] =lastlyid('base_goods',"id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base_goods',$u_fields,$u_values,'id='.$post['u|id']);                 
           echo "更新成功！";                    
           $get['view_id'] =$post['u|id'];
        }
	}
	
	function view_show($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$goods_arr =dbarr("SELECT * FROM base_goods WHERE id= ".$view_id);
		$f_name = dbfield_note('base_goods');
		$this->foo($f_name);
		$this->foo($goods_arr);
		$html = '<form method="post" action="/?module=goods&methods=add_submit">
		<p style="text-align:center;">物品查看</p>
		<table style="background-color:#CCC;width:100%">';
		foreach ((array)$goods_arr as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	            foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '<td style="background-color:#FFF;"><input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定更新"/></td></tr>';
	   $html.="</table></form>";
	   require_once parse_tmp("/goods/goods.html");//引入模版
	}
 //****************************************************英雄***********************************************//
	function hero($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$k_name = dbfield_note('base1_hero');
		$this->foo($k_name);
		$this->foo($goods_arr);
		$goods_arr =dbarr("SELECT id,name,base_property FROM base1_hero WHERE id= ".$view_id);
		$view_id =$get['view_id'];

		$goods_ary2=array();
		
		$goods_ary2=$cont->obj->getdata2($post['w_id'],$post['w_name'],$post['w_pr']);

		if($goods_ary2)
		{ 
		  $currentPage= $get['page'];
		  if(!$currentPage or isset($post['w_id']) or isset($post['w_name']) or isset($post['w_pr']))
		  {
		      $currentPage=1;
		  }
		   $this->foo($goods_ary2);
		   $goods_page =spage($goods_ary2,2,$currentPage);
		   $page_string=pageft2($currentPage,$goods_page['totalPage']);
		   $html = '<p></p><table style="background-color:#CCC;width:100%">';
		   $html.= '<tr>
		   <td width="20%" style="bordercolor:#000000;background-color:#02DF82;text-align:center;">武将ID</td>
		   <td width="3%" style="bordercolor:#000000;background-color:#40c0ff;text-align:center;">武将属性</td>
		   <td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">武将名称</td>
		
		  
		   </tr>';
			if (is_array($goods_page))
			{
				foreach ($goods_page['content'] as $key => $value)
				{
					$value['base2_property1']=$value['base_property'];
					$tproperty=explode(",",$value['base_property']);
					
					foreach ($tproperty as $key1=>$value1)
					{
						$tv1=explode(":",$value1);
						if(count($tv1)!=3)
						{
							$value['base2_property1']=str_replace($value1,"<font color='#FF0000'>".$value1."</font>",$value['base2_property1']);
						}
					}
					$html .= '<tr>';
					$html .= '<td style="background-color:#02DF82;text-align:center;">'.$value['id'].'</td>';
					$html .= '<td   style="background-color:#FFCCFF;text-align:left;height:20;width:10px;" >                    
					<div style="width:900px;height:60px;word-wrap:break-word;" id="s_'.$value['id'].'" data="'.$value['base_property'].'" 
					ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=base1_hero\',\'base_property\',\'id\','.$value["id"].','.strlen($value['base_property']).')">'.$value['base_property'].'
					</div>
					
					 
					<a  href="/?module=goods&methods=hero" > ->刷新验证<-</a>
					
					<div style="background-color:#FFF;width:900px;text-align:left;"> 
                     <table align="left" width="800"  border="1">
					<tbody>
					<tr>
					<td  bgcolor="#009393 " style="FONT-SIZE:15pt; word-break:break-all;width="300px>'.$value['base2_property1'].'</td>
					</tr> 
					</tbody>
					  </table>
					</div>
					</td>';

					$html .= '<td style="background-color:#977C00;text-align:center;">
					<a href = "/?module=goods&methods=hero_view_show&view_id='.$value['id'].'">'.$value['name'].'</></td>';
				     
					

					
					$html .= '</tr>';

				}
			}
		     $html .='<tr><td colspan=3 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
		     $html .= "</table>";
		}
		require_once parse_tmp("/goods/base1_hero.html");//引入模版
	}
	
	function hero_addshow($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//添加
		unset($get['view_id']);
		$f_name = dbfield_note('base1_hero');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=goods&methods=hero_add_submit">
		<p style="text-align:center;">英雄添加</p>
		<table style="background-color:#CCC;width:100%">';
			
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] !='id')
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
					$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/goods/base1_hero.html");//引入模版
	}
	
	function hero_add_submit($obj)
	{
	  
		$cont=new CONT("goods");
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

           dbinsert('base1_hero', $a_fields, $a_values,1);
           $get['view_id'] =lastlyid('base1_hero',"id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base1_hero',$u_fields,$u_values,'id='.$post['u|id']);                 
           echo "更新成功！";
                         
           $get['view_id'] =$post['u|id'];
        }
	}
	
	function hero_view_show($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$goods_arr =dbarr("SELECT * FROM base1_hero WHERE id= ".$view_id);
		$f_name = dbfield_note('base1_hero');
		$this->foo($f_name);
		$this->foo($goods_arr);
		$html = '<form method="post" action="/?module=goods&methods=hero_add_submit">
		<p style="text-align:center;">武将查看</p>
		<table style="background-color:#CCC;width:100%">';
		foreach ((array)$goods_arr as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	            foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '<td style="background-color:#FFF;"><input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
	   <input type="submit" name="add_submit" value="确定更新"/></td></tr>';
	   $html.="</table></form>";
	   require_once parse_tmp("/goods/base1_hero.html");//引入模版
	}
    //*********************************************主动技能*****************************************************
		function skill($obj)
		{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$goods_ary4=array();
		$goods_ary4=$cont->obj->getdata4($post['c_id'],$post['c_name'],$post['skill_parameter']);

		if($goods_ary4)
		{ 
		  $currentPage= $get['page'];
		  if(!$currentPage or isset($post['c_id']) or isset($post['c_name'])or isset($post['skill_parameter']))
		  {
		      $currentPage=1;
		  }
		   $this->foo($goods_ary4);
		   $goods_page =spage($goods_ary4,5,$currentPage);
		   $page_string=pageft2($currentPage,$goods_page['totalPage']);
		   $html = '<p></p><table style="background-color:#CCC;width:100%">';
		   $html.= '<tr>
		   <td width="20%" style="bordercolor:#000000;background-color:#02DF82;text-align:center;">技能ID</td>
		   <td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">技能名称</td>
		   
		   
		   
		   <td width="10%" style="bordercolor:#000000;background-color:#CC6699;text-align:center;">技能效果（cl-减益位置（1-前排，2-中排，3后排，4-自身对应的横排，5-单体，6-全体） cm-增益位置（1-自己，2-全部） cn-敌方减益 co-敌方冻结 cp-我方增益 cq-我方加血 cr-持续回合 ct-状态触发几率 增益/减益效果格式： cn/cp:属性id：X@数值，其中X=0表示整形相加，1表示小数型相乘，2表示小数型相加）
		   -双击修改</td>
		   <td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">技能描述</td>
		   
		   </tr>';
			if (is_array($goods_page))
			{
				foreach ($goods_page['content'] as $key => $value)
				{					
					$value['result1']=$value['result'];
					$tproperty=explode(",",$value['result']);
					
					foreach ($tproperty as $key1=>$value1)
					{
						$tv1=explode(":",$value1);
						if(count($tv1)!=3)
						{
							$value['result1']=str_replace($value1,"<font color='#FF0000'>".$value1."</font>",$value['result1']);
						}
					}
					$html .= '<tr>';
					$html .= '<td style="background-color:#02DF82;text-align:center;">'.$value['id'].'</td>';
					$html .= '<td style="background-color:#977C00;text-align:center;">
					<a href = "/?module=goods&methods=skill_view_show&view_id='.$value['id'].'">'.$value['name'].'</></td>';
						
					$html .= '<td   style="background-color:#FFCCFF;text-align:left;height:20;width:900px;" >                    
					<div style="width:690px;height:15px;word-wrap:break-word;" id="s_'.$value['id'].'" data="'.$value['result'].'" 
					ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=base3_skill_effect\',\'result\',\'id\','.$value["id"].','.strlen($value['result']).')">'.$value['result'].'
					</div>
										<a  href="/?module=goods&methods=skill" > ->刷新验证<- </a>
					
					<div style="background-color:#FFF;width:800px;text-align:left;"> 
                     <table align="left" width="800"  border="1">
					<tbody>
					<tr>
					<td  bgcolor="#009393 " style="FONT-SIZE:15pt; word-break:break-all;width="300px>'.$value['result1'].'</td>
					</tr> 
					</tbody>
					  </table>
					</div>
					</td>';
					$html .= '<td style="background-color:#977C00;text-align:center;">'.$value['info'].'</td>';
				
					$html .= '</tr>';
				}
			}
		     $html .='<tr><td colspan=7 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
		     $html .= "</table>";
		}
		require_once parse_tmp("/goods/skill.html");//引入模版
	}
	
	function skill_addshow($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//添加
		unset($get['view_id']);
		$f_name = dbfield_note('base3_skill_effect');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=goods&methods=skill_add_submit">
		<p style="text-align:center;">技能添加</p>
		<table style="background-color:#CCC;width:100%">';
			
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] )
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
					$html .= '<td style="background-color:#FFF;">
					<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=3 style="text-align:center;background-color:#FFF;">
		<input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/goods/skill.html");//引入模版
	}
	
	function skill_add_submit($obj)
	{
		$cont=new CONT("goods");
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

           dbinsert('base3_skill_effect', $a_fields, $a_values,1);
           $get['view_id'] =lastlyid('base3_skill_effect',"id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base3_skill_effect',$u_fields,$u_values,'id='.$post['u|id']);                 
           echo "更新成功！";                    
           $get['view_id'] =$post['u|id'];
        }
	}
	
	function skill_view_show($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$goods_arr =dbarr("SELECT * FROM base3_skill_effect WHERE id= ".$view_id);
		$f_name = dbfield_note('base3_skill_effect');
		$this->foo($f_name);
		$this->foo($goods_arr);
		$html = '<form method="post" action="/?module=goods&methods=skill_add_submit">
		<p style="text-align:center;">技能查看</p>
		<table style="background-color:#CCC;width:100%">';
		foreach ((array)$goods_arr as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	            foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '<td style="background-color:#FFF;"><input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定更新"/></td></tr>';
	   $html.="</table></form>";
	   require_once parse_tmp("/goods/skill.html");//引入模版
	}
	//*********************************************怪物等级*****************************************************

	function monster($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$goods_ary=array();
		
		$goods_ary=$cont->obj->getdata3($post['m_id'],$post['m_lv'],$post['m_name']);

		if($goods_ary)
		{ 
		  $currentPage= $get['page'];
		  if(!$currentPage or isset($post['m_id']) or isset($post['m_lv']) or isset($post['m_name']))
		  {
		      $currentPage=1;
		  }
		   $this->foo($goods_ary);
		   $goods_page =spage($goods_ary,4,$currentPage);
		   $page_string=pageft2($currentPage,$goods_page['totalPage']);
		   $html = '<p></p><table style="background-color:#CCC;width:100%">';
		   $html.= '<tr>
		   <td width="10%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">等级</td>	
		   <td width="10%" style="bordercolor:#000000;background-color:#02DF82;text-align:center;">ID</td>
   
		   <td width="10%" style="bordercolor:#000000;background-color:#40c0ff;text-align:center;">怪物等级属性（双击修改）</td>
		   <td width="20%" style="bordercolor:#000000;background-color:#FFCC00;text-align:center;">怪物名称</td>
		   </tr>';
			if (is_array($goods_page))
			{
				foreach ($goods_page['content'] as $key => $value)
				{
					$value['property1']=$value['property'];
					$tproperty=explode(",",$value['property']);
					
					foreach ($tproperty as $key1=>$value1)
					{
						$tv1=explode(":",$value1);
						if(count($tv1)!=3)
						{
							$value['property1']=str_replace($value1,"<font color='#FF0000'>".$value1."</font>",$value['property1']);
						}
					}
					$html .= '<tr>';
					$html .= '<td style="background-color:#977C00;text-align:center;">
					<a href = "/?module=goods&methods=monster_view_show&view_id='.$value['id'].'">'.$value['lv'].'</></td>';										
					$html .= '<td style="background-color:#02DF82;text-align:center;">'.$value['id'].'</td>';
					$html .= '<td   style="background-color:#FFCCFF;text-align:left;height:20;width:900px;" >                    
					<div style="width:690px;height:30px;word-wrap:break-word;" id="s_'.$value['id'].'" data="'.$value['property'].'" 
					ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=base2_monster\',\'property\',\'id\','.$value["id"].','.strlen($value['property']).')">'.$value['property'].'
					</div>
										<a  href="/?module=goods&methods=monster" > ->刷新验证<- </a>
					
					<div style="background-color:#FFF;width:900px;text-align:left;"> 
                     <table align="left" width="800"  border="1">
					<tbody>
					<tr>
					<td  bgcolor="#009393 " style="FONT-SIZE:15pt; word-break:break-all;width="300px>'.$value['property1'].'</td>
					</tr> 
					</tbody>
					  </table>
					</div>
					</td>';
					$html .= '<td style="background-color:#FFCC00;text-align:center;">
				    <a href = "/?module=goods&methods=monster_view_show&view_id='.$value['id'].'">
					'.$value['name'].'</></td>';

					$html .= '</tr>';
				}
			}
		     $html .='<tr><td colspan=4 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
		     $html .= "</table>";
		}
		require_once parse_tmp("/monster/monster_lv.html");//引入模版
	}
	
	function monster_addshow($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//添加
		unset($get['view_id']);
		$f_name = dbfield_note('base2_monster_lv');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=goods&methods=monster_add_submit">
		<p style="text-align:center;">怪物添加</p>
		<table style="background-color:#CCC;width:100%">';
			
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] !='id')
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
					$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;">
		<input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/monster/monster_lv.html");//引入模版
	}
	
	function monster_add_submit($obj)
	{
		$cont=new CONT("goods");
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

           dbinsert('base2_monster_lv', $a_fields, $a_values,1);
           $get['view_id'] =lastlyid('base2_monster_lv',"id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base2_monster_lv',$u_fields,$u_values,'id='.$post['u|id']);                 
           echo "更新成功！";                    
           $get['view_id'] =$post['u|id'];
        }
	}
	
	function monster_view_show($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$goods_arr =dbarr("SELECT * FROM base2_monster_lv WHERE id= ".$view_id);
		$f_name = dbfield_note('base2_monster_lv');
		$this->foo($f_name);
		$this->foo($goods_arr);

		$html = '<form method="post" action="/?module=goods&methods=monster_add_submit">
		<p style="text-align:center;">怪物查看</p>
		<table style="background-color:#CCC;width:100%">';
		foreach ((array)$goods_arr as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	            foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '<td style="background-color:#FFF;">
						<input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:800px;"/></td>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
	   <input type="submit" name="add_submit" value="确定更新"/></td></tr>';
	   $html.="</table></form>";
	   require_once parse_tmp("/monster/monster_lv.html");//引入模版
	}
	
	
	
	
	
	//********************************************************************************************************************//
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
	
	//*********************************************************物品扩展表***********************************************************//
	function ext($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$f_name = dbfield_note('base_goods_ext');
		$this->foo($f_name);
		$this->foo($goods_arr);
		$goods_arr =dbarr("SELECT property FROM base_goods_ext WHERE goods_ext_id= ".$view_id);
		$view_id =$get['view_id'];
		$goods_ary=$cont->obj->getextdata($get['type']);
		if($goods_ary)
		{ 
		  $currentPage= $get['page'];
		  if(!$currentPage or isset($post['g_id']) or isset($post['g_name']))
		  {
		      $currentPage=1;
		  }
		   $this->foo($goods_ary);
		   $goods_page =spage($goods_ary,5,$currentPage);
		   $page_string=pageft2($currentPage,$goods_page['totalPage']);
		   
		   $html = '<table style="background-color:#CCC;width:100%">';
		   $html.= '<tr>
		   
		   <td  width="20%"style="background-color:#977C00;text-align:center;">物品ID</td>
		   <td  width="20%"style="background-color:#977C00;text-align:center;">物品名称</td>
		   <td  width="30%"style="background-color:#977C00;text-align:left;">
		   物品属性: (英雄等级cp,初始强化系数de,普通攻击值df,普通防御值cw,带兵数cv,技能攻击值cx,技能防御值dm,飞行攻击值dn,强化属性do)-双击修改</td>
         
		   </tr>';
			if (is_array($goods_page))
			{
				foreach ($goods_page['content'] as $key => $value)
				{
					$value['property1']=$value['property'];
					$tproperty=explode(",",$value['property']);
					
					foreach ($tproperty as $key1=>$value1)
					{
						$tv1=explode(":",$value1);
						if(count($tv1)!=3 or $tv1[2]=="")
						{
							$value['property1']=str_replace($value1,"<font color='#FF0000'>".$value1."</font>",$value['property1']);
						}
					}
					
					$html .= '<tr>';
					$html .= '<td style="background-color:#BE77FF;text-align:center;">'.$value['goods_ext_id'].'</td>';
					$html .= '<td style="background-color:#019858;text-align:center;">
					<a href = "/?module=goods&methods=ext_view_show&view_id='.$value['goods_ext_id'].'">'.$value['goods_name'].'</></td>';       
					//双击修改SQL字段.
					//editdata+URL+要查的字段+字段ID+$value字段ID+strlen($value['要查的字段'])>$value['要查的字段']
					$html .= '<td  style="background-color:#FFCCFF;text-align:left;width=114; " >       
					<div id="s_'.$value['goods_ext_id'].'" data="'.$value['property'].'" ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=base_goods_ext\',\'property\',\'goods_ext_id\','.$value["goods_ext_id"].','.strlen($value['property']).')">'.$value['property'].'
					</div>
					 <a  href="/?module=goods&methods=ext&type=1" >->刷新验证<-</a>
					<div style="background-color:#FFF;text-align:left;"> 
                    <table align="left" width="950" border="1">
					<tbody>
					<tr>
					<td style="FONT-SIZE:15pt; width="200px" height="20" bgcolor="#009393 ">'.$value['property1'].'</td>
					</tr> 
					</tbody>
					  </table>
					</div>
					</td>';
					$html .= '</tr>';
				}
			}
		     $html .='<tr><td colspan=3 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
		     
		     $html .= "</table>";
		}
		require_once parse_tmp("/goods/ext.html");//引入模版
	}
	
	function updata($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		$get['query']=array($get['query_filed']=>$get['query_value']);
		$goods_tmp=$cont->obj->updata($get['dbname'],$get['filed'],$get['data'],$get['query']);
	}
	
	//*******************************************************更新扩展*****************************************************************//
	function ext_view_show($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//查看
		$view_id =$get['view_id'];
		$goods_arr =dbarr("SELECT * FROM base_goods_ext WHERE goods_ext_id= ".$view_id);
		$f_name = dbfield_note('base_goods_ext');
		$this->foo($f_name);
		$this->foo($goods_arr);
		$html = '<form method="post" action="/?module=goods&methods=ext_add_submit">
		<p style="text-align:center;">物品查看</p>
		<table style="background-color:#CCC;width:100%">';
		foreach ((array)$goods_arr as $key => $value)
		{
			foreach($value as $k=>$v)
	         {
	            foreach($f_name as $k2=>$v2)
	            {
					if($v2['COLUMN_NAME'] ==$k)
	                {
						$html.= '<tr>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
						$html.= '<td style="background-color:#FFF;"><input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
						$html.= '</tr>';
	                }
	            }
	        }
	    }
	
	   $html.='<tr><td colspan=2 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定更新"/></td></tr>';
	   $html.="</table></form>";
	   require_once parse_tmp("/goods/ext.html");//引入模版
	}
	
	function ext_add_submit($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//提交
       foreach ($post as $key => $value)
       {
			$pre = substr($key,0,2);
			if ($pre == 'a|') {
				if(substr($key,2)!="goods_ext_id")
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

           dbinsert('base_goods_ext', $a_fields, $a_values,1);
          
           $get['view_id'] =lastlyid('base_goods_ext',"goods_ext_id");
           echo "添加成功！";
        }
        
        if($u_fields && $u_values)
        {
           dbupdate('base_goods_ext',$u_fields,$u_values,'goods_ext_id='.$post['u|goods_ext_id']);                 
           echo "更新成功！";
           $get['view_id'] =$post['u|goods_ext_id'];
        }
	}
	
	function ext_addshow($obj)
	{
		$cont=new CONT("goods");
		$post=$obj->post;
		$get=$obj->get;
		//添加
		$goods_tmp=$cont->obj->base_goods_id($post['goods_tmp']);
		$goods_tmp["goods_id"]=$goods_tmp["id"];
		unset($get['view_id']);
		$f_name = dbfield_note('base_goods_ext');
		$this->foo($f_name);
		$html = '<form method="post" action="/?module=goods&methods=ext_add_submit">
		<p style="text-align:center;">物品添加</p>
		<table style="background-color:#CCC;width:100%">';
			
		if (is_array($f_name))
		{
			foreach ($f_name as $key => $value)
			{
				if($value['COLUMN_NAME'] !='id')
				{
					$html .= '<tr>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
					$html .= '<td style="background-color:#FFF;"><input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="'.$goods_tmp[$value['COLUMN_NAME']].'" style="width:400px;"/></td>';
					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
					$html .= '</tr>';
				}
			}
		}
		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定添加"/></td>';
		$html .= "</table></form>";
		require_once parse_tmp("/goods/ext.html");//引入模版
	}
	
	function base_goods_list($obj)
	{
		$this->bug_=0;
		$cont=new CONT("goods");
		$base_goods_list=$cont->obj->base_goods_list($obj->get);
		echo json_encode($base_goods_list);
	}
	
	function base_hero_list($obj)
	{
		$this->bug_=0;
		$cont=new CONT("goods");
		$base_hero_list=$cont->obj->base_hero_list($obj->get);
		echo json_encode($base_hero_list);
	}
	
	
////***************************************运营商表*********************************************************//
//	
//		function boss($obj)
//		{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		$goods_ary5=array();
//		$goods_ary5=$cont->obj->getdata5($post['d_id'],$post['d_name']);
//
//		if($goods_ary5)
//		{ 
//		  $currentPage= $get['page'];
//		  if(!$currentPage or isset($post['d_id']) or isset($post['d_name']))
//		  {
//		      $currentPage=1;
//		  }
//		$this->foo($goods_ary5);
//		$goods_page =spage($goods_ary5,6,$currentPage);
//		$page_string=pageft($currentPage,$goods_page['totalPage']);
//		   $html = '<p></p><table style="width:100%">';
//		   $html.= '
//		   <tr>
//		   <td width="20%"; style="bordercolor:#000000;background-color:#977C00;text-align:center;">运营商ID</td>
//		   <td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">用户名</td>
//		   <td width="20%"; style="bordercolor:#000000;background-color:#977C00;text-align:center;">运营商名称</td>
//		   <td width="20%"; style="bordercolor:#000000;background-color:#977C00;text-align:center;">公司名称</td>	
//		   <td style="bordercolor:#000000;background-color:#977C00;text-align:center;">运营商识别码</td>	
//		   <td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">运营商电话</td>		
//		   <td width="20%";  style="bordercolor:#000000;background-color:#977C00;text-align:center;">操作</td>	
//		   </tr>';
//			if (is_array($goods_page))
//			{
//				foreach ($goods_page['content'] as $key => $value)
//				{
//					$html .= '<tr>';
//					$html .= '<td style="background-color:	#79FF79;text-align:center;">'.$value['id'].'</td>';
//					$html .= '<td style="background-color:#79FF79;text-align:center;">'.$value['username'].'</td>';
//					$html .= '<td style="background-color:#79FF79;text-align:center;">'.$value['name'].'</td>';
//					$html .= '<td style="background-color:#79FF79;text-align:center;">'.$value['company'].'</td>';						
//					$html .= '<td style="background-color:#79FF79;text-align:center;">'.$value['identifier'].'</td>';
//					$html .= '<td   style="background-color:#79FF79;text-align:center;height:20;width:900px;" >                    
//					<div style="width:100px;height:100%;word-wrap:break-word;" name="s_'.$value['name'].'" data="'.$value['boss'].'" 
//					ondblclick="editdata(this,\'/?module=goods&methods=updata&dbname=operators\',\'boss\',\'name\','.$value["name"].','.strlen($value['boss']).')">'.$value['boss'].'
//					</div>
//					</td>';
//					$html .= '<td style="background-color:#79FF79;text-align:center;">
//					<a href="/?module=goods&methods=game_fu&view_id='.$value['id'].'">【开服列表】</a>&nbsp; 
//					<a href="/?module=goods&methods=boss_show&view_id='.$value['id'].'">【编辑】</a>
//					</td>';
//					$html .= '</tr>';
//				}
//			}
//		     $html .='<tr><td colspan=9 style="background-color:#FFF;text-align:center;">'.$page_string.'</td></tr>';
//		     $html .= "</table>";
//				}
//			require_once parse_tmp("/goods/gamefu.html");//引入模版
//			}
//	//*****************************运营商添加***************************************************//	
//		function boss_addshow($obj)
//		{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//添加
//		unset($get['view_id']);
//		$f_name = dbfield_note('operators','operators');
//		$game2=dbarr("SELECT id,username,name,company,identifier,boss FROM `operators` ",0,"operators",0,"operators");
//		$this->foo ($game2);
//		$this->foo($f_name);
//		$html = '<form method="post" action="/?module=goods&methods=boss_add_submit">
//		<p style="text-align:center;">运营商添加
//		<a href="/?module=goods&methods=boss">【返回】</a>
//		</p>
//		<table style="background-color:#CCC;width:100%">';
//				if (is_array($f_name))
//		{
//			foreach ($f_name as $key => $value)
//			{
//				if($value['COLUMN_NAME'] )
//				{
//					$html .= '<tr>';
//					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_NAME'].'</td>';
//					$html .= '<td style="background-color:#FFF;">
//					<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
//					$html .= '<td style="background-color:#FFF;">'.$value['COLUMN_COMMENT'].'</td>';
//					$html .= '</tr>';
//				}
//			}
//		}
//		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;">
//		<input type="submit" name="add_submit" value="确定添加"/></td>';
//		$html .= "</table></form>";
//				require_once parse_tmp("/goods/gy.html");//引入模版
//	}
//	//**************************************************************添加运营商***************************************************//
//	
//	function boss_add_submit($obj)
//	{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//提交
//       foreach ($post as $key => $value)
//       {
//			$pre = substr($key,0,2);
//			if ($pre == 'a|') {
//				if(substr($key,2)!="id")
//				{
//					$a_fields[] = substr($key,2);
//					$a_values[] = $value;
//				}
//			}elseif($pre == 'u|') {
//				$u_fields[] = substr($key,2);
//				$u_values[] = $value;
//			}
//		}
//
//        if($a_fields && $a_values)
//        {
//
//           dbinsert('operators', $a_fields, $a_values,1);
//           $get['view_id'] =lastlyid('operators',"id");
//           echo "添加成功！";
//        }
//        
//        if($u_fields && $u_values)
//        {
//           dbupdate('operators',$u_fields,$u_values,'id='.$post['u|id']);                 
//           echo "更新成功！";                    
//           $get['view_id'] =$post['u|id'];
//        }
//	}
////***************************修改运营商***************************************************//
//	function boss_show($obj)
//	{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//查看
//		$view_id =$get['view_id'];
//		$goods_ary5=dbarr("SELECT id,name,company,identifier,boss FROM `operators` WHERE id= ".$view_id,0,"operators");
//		$f_name= dbfield_note('operators','operators');
//		$this->foo ($f_name);
//		$this->foo ($goods_ary5);
//		$html = '<form method="post" action="/?module=goods&methods=boss_add_submit">
//		<p style="text-align:center;">修改运营商数据</p>
//		<table style="background-color:#CCC;width:100%">';
//		foreach ((array)$goods_ary5 as $key => $value)
//		{
//			foreach($value as $k=>$v)
//	         {
//	      foreach($f_name as $k2=>$v2)
//	            {
//					if($v2['COLUMN_NAME'] ==$k)
//	                {
//						$html.= '<tr>';
//						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
//						$html.= '<td style="background-color:#FFF;">
//						<input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
//						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
//						$html.= '</tr>';
//	                }
//	            }
//	        }
//	    }
//	
//	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
//	   <input type="submit" name="add_submit" value="确定更新"/>
//	   <a href="/?module=goods&methods=boss">【返回】</a>
//	   </td>
//	   </tr>';
//	   
//	   $html.="</table></form>";
//	   require_once parse_tmp("/goods/gy.html");//引入模版
//	}
//	//**************************************************游戏服****************************************************//
//	function game_fu($obj)
//		{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		$game=array();
//		$game=$cont->obj->gamefu($post['z_name'],$post['$z_id']);    
//		$view_id =$get['view_id'];
//		$goods_ary6=dbarr("SELECT a.operators_id,b.platform,  b.login,b.identifier,  a.server_id, a.server_name,a.game_web, a.month_pay, a.mongo_username, a.pay, a.mongo_db,a.server_mongo_ip FROM `server_list` a inner join operators b on a.operators_id=b.id   WHERE id=".$view_id,0,"operators");
//		$f_name= dbfield_note('server_list','operators');
//		$this->foo ($f_name);
//		$this->foo ($goods_ary6);
//		if($goods_ary6)
//		{ 
//		  $currentPage= $get['page'];
//		  if(!$currentPage or isset($post['z_name']) or isset($post['z_id']))
//		  {
//		      $currentPage=1;
//		  }
//
//		$this->foo($goods_ary6);
//		$goods_page =spage($goods_ary6,8,$currentPage);
//		$page_string=pageft($currentPage,$goods_page['totalPage']);
//		$html = '<table style="width:80%">';
//		$html.= '<tr>
//		<td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">开服ID</td>
//		<td width="20%" style="bordercolor:#000000;background-color:#977C00;text-align:center;">开服名称</td>
//		<td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">游戏地址</td>
//		<td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">当月充值总量</td>
//		<td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">充值总量</td>
//		<td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">芒果数据库</td>
//		<td  style="bordercolor:#000000;background-color:#977C00;text-align:center;">操作</td>	
//		</tr> ';
//		if (is_array($goods_page))
//	    {
//			foreach ($goods_page['content'] as $key => $value)
//			{
////			foreach ((array)$goods_ary6 as $key => $value)
////			    {
//
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//
//		'.$value['server_id'].'</td>';
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//
//		'.$value['server_name'].'</td>';
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//
//		'.$value['game_web'].'</td>';
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//		'.$value['month_pay'].'</td>';
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//
//		'.$value['pay'].'</td>';
//		$html .= '<td style="background-color:	#FFD39B;text-align:center;">
//
//		'.$value['mongo_db'].'</td>';
//	
//		$html .= '<td style="background-color:#FFD39B;text-align:center;">
//	     
//		<a href="/?module=goods&methods=game_show&view_id='.$value['operators_id'].'&view_id2='.$value['server_id'].'       ">【编辑】</a>
//  
//	     </td>';
//		$html.= '</tr>';
////			      }
//			}
//	   }
//	      	$html .='<tr><td colspan=6 style="background-color:#FFF;text-align:center;">'.$page_string.' &nbsp;8服/页 </td></tr>';
//	      	$html.="</table>";	
//     }	     
//			require_once parse_tmp("/goods/boss.html");//引入模版
//   }
////***************************添加游戏服*******************************//			
//	function game_addshow($obj)
//	{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//添加
//		unset($get['view_id']);
//		$f_name = dbfield_note('server_list','operators');
//		$this->foo($f_name);
//		$html = '<form method="post" action="/?module=goods&methods=game_submit">
//		<p style="text-align:center;">游戏服添加
//		</p>
//		
//		<table style="background-color:#CCC;width:100%">';
//			
//		if (is_array($f_name))
//		{
//			foreach ($f_name as $key => $value)
//			{
//				if($value['COLUMN_NAME'] !='id')
//				{
//					$html .= '<tr>';
//					$html .= '<td style="background-color:#8FBC8F;">'.$value['COLUMN_NAME'].'</td>';
//					$html .= '<td style="background-color:#FFF;">
//					<input type ="text" name="a|'.$value['COLUMN_NAME'].'" value="" style="width:400px;"/></td>';
//					$html .= '<td style="background-color:#8FBC8F;">'.$value['COLUMN_COMMENT'].'</td>';
//					$html .= '</tr>';
//				}
//			}
//		}
//		$html.='<td colspan=2 style="text-align:center;background-color:#FFF;"><input type="submit" name="add_submit" value="确定添加"/></td>';
//		$html .= "</table></form>";
//		require_once parse_tmp("/goods/gy.html");//引入模版
//	}
////***************************************************************//			
//	function game_submit($obj)
//	{
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//提交
//       foreach ($post as $key => $value)
//       {
//			$pre = substr($key,0,2);
//			if ($pre == 'a|') {
//				if(substr($key,2)!="id")
//				{
//					$a_fields[] = substr($key,2);
//					$a_values[] = $value;
//				}
//			}elseif($pre == 'u|') {
//				$u_fields[] = substr($key,2);
//				$u_values[] = $value;
//			}
//		}
//
//        if($a_fields && $a_values)
//        {
//
//           dbinsert('server_list', $a_fields, $a_values,1);
//           $get['view_id'] =lastlyid('server_list',"id");
//           //实例一个mongo
//           $tmp_mongo=new TMP_MONGO();
//           //链接远程的服务器
//           $tmp_mongo->phpmongo=new \Mongo($post["a|server_mongo_ip"].":".$post["a|server_mongo_port"]);
//           //选择一个数据库
//           $tmp_mongo->selectdb($post["a|mongo_db"]);
//           //添加一个用户名
//           $tmp_mongo->adduser($post["a|mongo_username"],$post["a|mongo_password"]);
//           echo "添加成功！";
//        }
//        
//        if($u_fields && $u_values)
//        {
//           dbupdate('server_list',$u_fields,$u_values,'id='.$post['u|id']);                 
//           echo "更新成功！";                    
//           $get['view_id'] =$post['u|id'];
//        }
//	}
//	//***********************************修改游戏服***********************************************************//			
//		function game_show($obj)
//	  {
//		$cont=new CONT("goods");
//		$post=$obj->post;
//		$get=$obj->get;
//		//查看
//		$view_id=$get['view_id'];
//		$view_id2=$get['view_id2'];
//		$game2=dbarr("SELECT b.platform, b.login,b.identifier, a.operators_id, a.server_id, a.server_name,a.game_web, a.server_ip, a.server_mongo_port, a.mongo_username, a.mongo_password, a.mongo_db,a.server_mongo_ip FROM `server_list` a inner join operators b on a.operators_id=b.id  WHERE `operators_id`=$view_id and `server_id`=$view_id2",0,"operators");
//		$f_name= dbfield_note('server_list','operators');
//		$this->foo ($f_name);
//		$this->foo ($game2);
//		$html = '<form method="post" action="/?module=goods&methods=game_submit">
//		<td style="text-align:center;">修改游戏服数据</td>
//		<table style="background-color:#CCC;width:100%">';
//		
//		foreach ((array)$game2 as $key => $value)
//		{
//			foreach($value as $k=>$v)
//	         {
//	      foreach($f_name as $k2=>$v2)
//	            {
//					if($v2['COLUMN_NAME'] ==$k)
//	                {
//						$html.= '<tr>';
//						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_NAME'].'</td>';
//						$html.= '<td style="background-color:#FFF;">
//						<input type ="text" name="u|'.$k.'" value="'.$v.'" style="width:400px;"/></td>';
//						$html.= '<td style="background-color:#FFF;">'.$v2['COLUMN_COMMENT'].'</td>';
//						$html.= '</tr>';
//	                }
//	            }
//	        }
//	    }
//	
//	   $html.='<tr><td colspan=3 style="text-align:center;background-color:#FFF;">
//	   <input type="submit" name="add_submit" value="确定更新"/>
//	   <a href="/?module=goods&methods=game_fu&view_id='.$value['operators_id'].'">【返回】</a>
//	   </td>
//	   </tr>';
//	   
//	   $html.="</table></form>";
//	   require_once parse_tmp("/goods/gy.html");//引入模版
//	}
}
?>