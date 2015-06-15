<?php
namespace kernel\base\base_class;

/** /////////////////////////////////////////////////////////////////////////
 *
 * 分页类
 * 函数说明
 * sql_p($sql,$pagesize='20') 带分页功能的SQL执行语句
 * 结合p($return=false) 使用 如需要返回值只指定 $return 为true
 * p($return=false) 函数是显示分页

 * 参数说明 
 * $sql 完整的SQL语句
 * $pagesize 每页的行数，默认为20
 * $return 是否返回值 默认为 false 若为 true 则返回变量
 * $allurl 是否从外部传入分页路径前面那部分 默认为空

 * 举例
 * $sql="SELECT * FROM qd_supplyincome order by id desc";
 * $p=new page;
 * $p->sql_p($sql);
 * $p->p();

//////////////////////////////////////////////////////////////////////////// */
class admin_page{

	var $total_row;
	var $pagesize;
	var $flagsize;
	
	function sql_p($sql,$page,$pagesize='20')
	{	
		if($page<1){$page=1;}
		$this->flagsize=intval($page/10)+1;
		
		$max0=intval($page/10)*($pagesize*10);
		$max1=$pagesize*10;
		
		$total_row = dbsum($sql." limit $max0,$max1");
		
		$this->total_row=$total_row;
		
		$this->pagesize=$pagesize;
		
		$pages=intval($total_row/$pagesize); //算出多少页

		if ($total_row%$pagesize)
		{
		  $pages++;
		}

		$offset=$pagesize*($page-1);
		return dbarr($sql." limit $offset,$pagesize");
	}
	
	function p($return=false,$allurl='',$page)
	{
			if(!$allurl)
			{
				$allurl="http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}"; //全地址
			}
		
			$allurl=str_replace(array('&page='.$page,'page='.$page),'',$allurl);
			
			$total_row=$this->total_row;
			
			$pagesize=$this->pagesize;
			
	        $totalPage=($total_row%$pagesize==0)?intval($total_row/$pagesize): intval($total_row/$pagesize)+1;
			
            $currentPage    = intval($page);
			
			$allurl_=explode('?',$allurl);
			
			if(count($allurl_)==2)
			{
				$url                 = $allurl."&page";
			}else{
				$url                 = $allurl."?page";
			}
			
            $halfPer           = 5;
			
            $imagePath      ="images";
			
            $pageHtml       = $this->page ( $totalPage , $currentPage,$url ,$halfPer,$imagePath);
			
            $currentPage=($currentPage<1)?1:$currentPage;
			
            $currentPage=($currentPage>$totalPage)?$totalPage:$currentPage;
			
			if($return)
			{
				return $pageHtml;
			}else{
				print $pageHtml;
			}
	        
	 }
	 
	private function page ($totalPage , $currentPage,$url ,$halfPer,$imagePath)
	{
            $total=$totalPage;
			   
            $currentPage=($currentPage<1)?1:$currentPage;
			   
            $currentPage=($currentPage>$totalPage)?$totalPage:$currentPage;
            $currentPage*=$this->flagsize;
			   
            # 更改分页样式 By Xinge
            $re="<div id=\"pagelist\"><ul>";
			   
            $re .= ( $currentPage > 1 )?"<li><a href=\"$url=1\">首页</a>
                                         <li><a href=\"$url=".($currentPage-1)."\">上一页</a>":"
                                         <li>首页";
			   
            for ( $i = $currentPage - $halfPer,$i >= 1 || $i = 1 ,$j = $currentPage + $halfPer, $j < $totalPage || $j = $totalPage;$i <= $j ;$i++ )
			{//核心算法！
			   
                $re .= ($i == $currentPage)? "<li class=\"current\"><b> $i </b>": "<li><a href=\"$url=$i\"> [$i] </a>";
            }
			   
            $re .= ( $currentPage < $total )? "<li><a href=\"$url=" . ( $currentPage + 1 ) . "\">下一页</a>
                                               <li><a href=\"$url=" . ( $total )."\">尾页</a>": "
                                               <li>尾页";
               
			$re .= "<li class=\"pageinfo\">第{$currentPage}页</li>\n";
            $re .= "</ul></div>";
        
            return $re;
     }
}

?>
