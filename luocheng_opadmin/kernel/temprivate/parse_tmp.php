<?php
require(parse_tmp::getdirname(__FILE__)."temprivate/funct.php");
class parse_tmp{
	var $temp='template';//模版目录
	var $enforce='enforce';//执行目录
	var $time=0;	//过期时间单位秒
	
	/*新模版解析*/
	public function new_temp_cms($url,$newarr=false)
	{		
		if($this->time>0)
		{
			$p=$this->get_enforce_php($url);
			
			if(file_exists($p)==true && $this->time>0)
			{
				if(@filemtime($p)+$this->time>time())	
				{
					return $p;
				}
			}
		}
		
		$url=$this->temp.'/'.$url;
		$value = @file_get_contents($this-> getdirname(__FILE__).$url);
		$this->analysis_GETTEMPLET($value);
		$this->deltestCODE($value);
		if($newarr)
		{
			$this->analysis_NEWS($value,$newarr);
		}
		
		$this->analysis_NAMESPACE($value);
		$this->analysis_VARIABLE($value);
		$this->analysis_CLASS($value);
		$this->analysis_SQL($value);
		$this->analysis_ECHO($value);
		$this->preg($value);
		return $this->temp_php($value,$url);
	}
	
	/*取出测试部分代码*/
	private function deltestCODE(&$value)
	{
		$newstr_h=explode("<!--end head-->",$value);
		if(count($newstr_h)>1)
		{
			$value=$newstr_h[1];
		}
		
		$newstr_f=explode("<!--start foot-->",$value);
		if(count($newstr_f)>1)
		{
			$value=$newstr_f[0];
		}
		
		$arr=array();
		@preg_match_all('/<!--start-->(.+?)<!--end-->/is',$value,$arr);
		if(isset($arr[0][0]))
		{
			$value=str_replace($arr[0][0],"",$value);
		}
	}
	
	/*引入模板的解析*/
	private function analysis_GETTEMPLET(&$value)
	{
		$file=$arr=array();
		@preg_match_all('/<gettemplet(.+?)\/>/is', $value, $arr);
		if(isset($arr[1]))
		{
			foreach($arr[1] as $key=> $value1)
			{
				@preg_match_all( "/file=\"([^\"]+)\"/is",$value1,$file);
				$filestr = @file_get_contents($this-> getdirname(__FILE__).$this->temp.'/'.$file[1][0]);
				$this->deltestCODE($filestr);
				$this->analysis_GETTEMPLET($filestr);
				$value=str_replace($arr[0][$key],$filestr,$value);
			}
		}
	}
	
	/*引入动态模板的解析*/
	private function analysis_NEWS(&$value,$newarr_)
	{
		if(!is_array($newarr_))
		{
			$newarr[0]=$newarr_;
		}else{
			$newarr=$newarr_;
		}
		
		$file=$arr=array();
		@preg_match_all('/<news(.+?)\/>/is', $value, $arr);
		
		foreach($arr[1] as $key=> $value1)
		{
			@preg_match_all( "/fileindex=\"([^\"]+)\"/is",$value1,$file);
			$file__=intval(trim($file[1][0]));
			$filestr = @file_get_contents($this-> getdirname(__FILE__).$this->temp.'/'.$newarr[$file__]);
			$this->deltestCODE($filestr);
			$this->analysis_GETTEMPLET($filestr);
			$value=str_replace($arr[0][$key],$filestr,$value);
		}
	}
	
	/*命名空间的解析*/
	private function analysis_NAMESPACE(&$value)
	{
		$arr=array();
		@preg_match_all('/<namespace(.+?)\/>/is', $value, $arr);
		
		foreach($arr[1] as $key=> $value1)
		{
			@preg_match_all( "/use=\"([^\"]+)\"/is",$value1,$file);
			@preg_match_all( "/as=\"([^\"]+)\"/is",$value1,$as);
			$use="<?php use ".$file[1][0]." AS ".$as[1][0].";?>";
			$value=str_replace($arr[0][$key],$use,$value);
		}
	}
	

	/*数组变量解析的解析*/	
	private function analysis_VARIABLE(&$value)
	{
		$arr=array();
		@preg_match_all('/<variable(.+?)<\/variable>/is', $value, $arr);
		foreach($arr[0] as $key=> $value1)
		{
			@preg_match_all( "/data=\"([^\"]+)\"/is",$value1,$data);
			if(!$data[1][0]){return;}
			$value1=$this->analysis_LOOP($value1,$data[1][0]);
			
			$value1=str_replace("<echo>","<?php echo ",$value1);
			$value1=str_replace("</echo>",";?>",$value1);
			
			@preg_match_all('/<variable(.+?)>/is', $value1, $newarr);
			$value1=str_replace(array($newarr[0][0],"</variable>"),"",$value1);
			
			$value=str_replace($arr[0][$key],$value1,$value);
		}
	}
	
	/*字符串变量解析的解析*/
	private function analysis_ECHO(&$value)
	{
		$value=str_replace("<echo>","<?php echo ",$value);
		$value=str_replace("</echo>",";?>",$value);
	}
	
	/*类的解析*/
	private function analysis_CLASS(&$value)
	{
		$arr=array();
		@preg_match_all('/<class(.+?)<\/class>/is', $value, $arr);

		foreach($arr[0] as $key=> $value1)
		{
			@preg_match_all('/<class(.+?)>/is',$value1, $class_tmp);
			
			@preg_match_all( "/new=\"([^\"]+)\"/is",$value1,$new);
			@preg_match_all( "/function=\"([^\"]+)\"/is",$value1,$function);
			@preg_match_all( "/parameter=\"([^\"]+)\"/is",$value1,$parameter);
			@preg_match_all( "/data=\"([^\"]+)\"/is",$value1,$data);
			if($new[1][0])
			{
				$class='<?php $'.$new[1][0].'=new '.$new[1][0].'();'."\n";
				$class.=$data[1][0].'=$'.$new[1][0].'->'.$function[1][0].'('.$parameter[1][0].');?>';
				
			}else{
				$class='<?php '.$data[1][0].'='.$function[1][0].'('.$parameter[1][0].');?>';
			}
			$value1=str_replace($class_tmp[0][0],$class,$value1);
			$value1=str_replace("</class>","",$value1);
			
			$value1=$this->analysis_LOOP($value1,$data[1][0]);
			$value1=str_replace("<echo>","<?php echo ",$value1);
			$value1=str_replace("</echo>",";?>",$value1);
			$value=str_replace($arr[0][$key],$value1,$value);
		}
	}
	
	/*loop解析*/
	private function analysis_LOOP(&$value,$count="1")
	{
		@preg_match_all('/<loop(.+?)>/is', $value, $loop);
		@preg_match_all( "/list=\"([^\"]+)\"/is",$loop[1][0],$list);
		
		@preg_match_all( "/index=\"([^\"]+)\"/is",$loop[1][0],$initialization);
		@preg_match_all( "/max=\"([^\"]+)\"/is",$loop[1][0],$max);
		
		if(!$list[1][0]){$list[1][0]='$i';}
		
		if(!isset($initialization[1][0])){$initialization[1][0]=0;}
		$yes=0;
		if(!isset($max[1][0])){
			@preg_match_all('/<echo>(.+?)<\/echo>/is', $value, $echo);
			$max[1][0]='count('.$count.')';
			$yes='(isset('.$count.')&& '.$count.' && is_array('.$count.'))';
		}else{
			$yes=1;
		}
		
		$for1='<?php 
		if('.$yes.'>0)
		{
			for ('.$list[1][0].'='.$initialization[1][0].';'.$list[1][0].'<'.$max[1][0].';'.$list[1][0].'++)
			{ 
			?>';
			$for2='<?php 
			}
		} 
		?>';
		
		$value=str_replace($loop[0][0],$for1,$value);
		$value=str_replace("</loop>",$for2,$value);
		return $value;
	}
	
	
	/*构造sql语句*/
	private function analysis_SQL_($value)
	{
		global $tp;
		$sql=array();
		@preg_match_all('/<sql(.+?)\>/is',$value,$sql);
		$cms=explode(';',$sql[1][0]);
		
		for($k=0;$k<count($cms);$k++) //一个标签
		{
			$a0=explode('="',$cms[$k]);
			$a0[1]=str_replace('"','',$a0[1]);
			$cms[$k]=str_replace('"','',$cms[$k]);
			if(trim($a0[0])=='table') //处理出查询表
			{
				$table=$a0[1];
			}
			
			if(trim($a0[0])=='field') //处理出查询字段
			{
				$field=$a0[1];
			}
			
			if(trim($a0[0])=='num')  //读取记录数
			{
				$num=' LIMIT '.$a0[1];
				$pagesize=$a0[1];
			}
			
			if(trim($a0[0])=='order')  //排序
			{
				$order='order by '.$a0[1];
			}
			
			if(trim($a0[0])=='where')  //处理出查询条件
			{
				$where=$cms[$k];
				$where=str_replace('where=','WHERE ',$cms[$k]);
			}
			
			if(trim($a0[0])=='sql')  //处理出查询条件
			{
				$sqlsentence=$a0[1];
				$sqlsentence=str_replace('||','=',$sqlsentence);
			}
			
			if(trim($a0[0])=='page' and trim($a0[1])=='yes')  //分页
			{
				$pagination='yes';
			}
			
			if(trim($a0[0])=='pagesname')   //分页地方
			{
				$pagesname=$a0[1];
			}
			
			if(trim($a0[0])=='halfPer')  //分页步长
			{
				$halfPer=$a0[1];
			}
			
		}
		
		if($field=='')
		{  //无要求则插入通配符
			$field=' * ';
		}
		
		$sql="SELECT $field FROM `".$table.'` '.$where.' '.$order;		///构造SQL语句
		
		if($sqlsentence!='')
		{
			$sql=$sqlsentence;
		}
			
		if($pagination=='yes')
		{
			$phpsql.='$row=$tp->sql_p("'.$sql.'",'.$pagesize.');';
			$pagest='<?php $tp->p('.$halfPer.');?>';
			$pgenm="<".$pagesname."/>";
			
		}else{
			$phpsql.='$row=mysql_query("'.$sql.$num.'");';
		}
		
		return array($phpsql,$pagest,$pgenm);
	}
	
	/*sql的解析*/
	private function analysis_SQL(&$value)
	{
		@preg_match_all('/<sql(.+?)<\/sql>/is', $value, $arr);
		for($j=0;$j<count($arr[0]);$j++)    //将每个标签分拆成有用的字符串
		{
			$tmp=$arr[0][$j];
			$tmpsql=$this->analysis_SQL_($arr[0][$j]);
			@preg_match_all('/<sql(.+?)>/is', $arr[0][$j], $newarr);
			$arr[0][$j]=str_replace($newarr[0][0],"<?php ".$tmpsql[0]."?>",$arr[0][$j]);
			$arr[0][$j]=str_replace("</sql>","",$arr[0][$j]);
			$arr[0][$j]=str_replace("<loop>",'<? while($rs=mysql_fetch_array($row)){?>',$arr[0][$j]);
			$arr[0][$j]=str_replace("</loop>",'<? }?>',$arr[0][$j]);
			$arr[0][$j]=str_replace("<echo>","<?php echo ",$arr[0][$j]);
			$arr[0][$j]=str_replace("</echo>",";?>",$arr[0][$j]);
			$value=str_replace($tmp,$arr[0][$j],$value);
			if($tmpsql[2] && $tmpsql[1])
			{
				$value=str_replace($tmpsql[2],$tmpsql[1],$value);
			}
		}
	}

	/*运行模板*/
	private function temp_php(&$value,$url)
	{
		$url=str_replace($this->temp,$this->enforce,$url);
		
		$url1=strrchr($url,'/');//去掉文件保留目录
		
		$ur2=str_replace($url1,'',$url);
		
		$this->createDir($this-> getdirname(__FILE__).$ur2);
		
		$url3=strrchr($url,'.');//取得文件扩展名
		
		$ur4=str_replace($url3,'.php',$url);//将扩展名换成PHP
		/* 写入文件*/
		@file_put_contents($this-> getdirname(__FILE__).$ur4,$value);
		return $this-> getdirname(__FILE__).$ur4;
	}
	
	/*取得执行模版路径*/
	private function get_enforce_php($url)
	{
		$url=str_replace($this->temp,$this->enforce,$url);
		
		$url3=strrchr($url,'.');//取得文件扩展名
		
		$ur4=str_replace($url3,'.php',$url);//将扩展名换成PHP
		
		return $this-> getdirname(__FILE__).$ur4;
	}

	/*标签解析*/
	private function preg(&$value)
	{
		$value=str_replace('<!--?','<?php ',$value);//PHP标签解析
		$value=str_replace('?-->',' ?>',$value);//PHP标签解析
		return $value;
	}
	
	
	
	/*建立无极目录*/
	private function createDir($dir)
	{
		$falg = true;
		
		$dir = str_replace(array('//','\\','\\\\'),'/',$dir);
		
		if (!@is_dir($dir))
		{
			$temp = explode('/',$dir);
			$cur_dir = '';
			for($i=0;$i<count($temp);$i++) 
			{
				$cur_dir .= $temp[$i].'/';
				if (!@is_dir($cur_dir)) 
				{
					if(!@mkdir($cur_dir,0777)) $falg = false;
				} 
			}
		}
		return $falg;
	}
	
	/*取得路径*/
	static function getdirname($path)
	{
		if(strpos($path,'\\')!==false)
		{
			$dir=substr($path,0,strrpos($path,'\\'));
			
			$dir=str_replace('temprivate','',$dir);
			return $dir;
		}elseif(strpos($path,'/')!==false)
		{
			$dir=substr($path,0,strrpos($path,'/'));
			$dir=str_replace('temprivate','',$dir);
			return $dir;
		}else{
			return '/';
		}
	}
	
	/*开始静态化*/
	public function html_start()
	{
		ob_start();
	}
	
	/*处理静态化 $dir 如果是目录，则生成文件名，如果是最终的路径则直接替换*/
	public function html_end($dir)
	{
		$html = ob_get_contents();
		ob_end_clean();
		if(is_dir($dir))
		{
			$timest=microtime();
			$timest=explode(' ',$timest);
			$tst=$timest[1].str_replace('0.','',$timest[0]);
			$timedir=date("Y",time()).'/'.date("m",time()).'/';
			$temurl=$dir.'/'.$timedir.$tst.'.html';
			$this->createDir($temurl);
			@file_put_contents($temurl,$html);
			return $temurl;
		}else{
			@file_put_contents($dir,$html);
			return $dir;
		}
	}
}
?>