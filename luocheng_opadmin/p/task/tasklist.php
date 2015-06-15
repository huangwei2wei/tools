<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_qmdj.php");
require(D_R."res/data/base_ninjutsu.php");
require(D_R."res/data/base_npc.php");
require(D_R."res/data/base_bourn_lv.php");
if($_GET["del_id"])
{
	deltask($_GET["del_id"]);
}

if($_GET["c_del_id"])
{
	del_c_task($_GET["c_del_id"]);
}

$pg = $_GET["page"];
$pagesize = 20; //每页显示数
$limitFrom = 0;//开始limit的数
if (!isset($pg))
{
   $pg=1;
}

if ($pg>1)
{
	$limitFrom = $pagesize*($pg-1);
}else{
     $limitFrom = 0;
}

if($_GET["sel"]==1)
{
	if($_GET["tty"] or $_GET["tti"])
	{
		if($_GET["tty"] and !$_GET["tti"])
		{
			$tasklist=dbarr("SELECT * FROM `base_task` where type=".$_GET["tty"]." ORDER BY `id`  ASC,`front_lv` asc,`type` asc LIMIT ".$limitFrom.",".$pagesize);
			$total_row=dbsum_("SELECT * FROM `base_task` where type=".$_GET["tty"]);
		}elseif(!$_GET["tty"] and $_GET["tti"]){
			$tasklist=dbarr("SELECT * FROM `base_task` where name like '".$_GET["tti"]."' ORDER BY `id`  ASC,`front_lv` asc,`type` asc LIMIT ".$limitFrom.",".$pagesize);
			$total_row=dbsum_("SELECT * FROM `base_task` where  name like '".$_GET["tti"]."'");
		}elseif($_GET["tty"] and $_GET["tti"]){
			$tasklist=dbarr("SELECT * FROM `base_task` where name like '".$_GET["tti"]."' and type=".$_GET["tty"]." ORDER BY `id`  ASC,`front_lv` asc,`type` asc LIMIT ".$limitFrom.",".$pagesize);
			$total_row=dbsum_("SELECT * FROM `base_task` where  name like '".$_GET["tti"]."' and type=".$_GET["tty"]);
		}else{
			$tasklist=dbarr("SELECT * FROM `base_task` ORDER BY `id`  ASC,`front_lv` asc,`type` asc LIMIT ".$limitFrom.",".$pagesize);
			$total_row=dbsum_("SELECT * FROM `base_task` where 1");
		}
	}
	
}else{
	$tasklist=dbarr("SELECT * FROM `base_task` ORDER BY `id`  ASC,`front_lv` asc,`type` asc LIMIT ".$limitFrom.",".$pagesize);
	$total_row=dbsum_("SELECT * FROM `base_task` where 1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>

<table width="100%" border="1">
  <tr>
    <td align="center"><a href="addtask.php">添加任务</a></td>
    <td align="center"><strong>任务列表</strong></td>
  </tr>
  <tr>
    <td colspan="2">
    <form action="" method="post" enctype="application/x-www-form-urlencoded" name="form1" target="_self">
      <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="25" bgcolor="#CCCCCC">任务名</td>
          <td height="25" bgcolor="#CCCCCC">任务类型</td>
          <td height="25" bgcolor="#CCCCCC">任务属国</td>
          <td height="25" bgcolor="#CCCCCC">任务顺序</td>
          <td height="25" bgcolor="#CCCCCC">经验奖励</td>
          <td height="25" bgcolor="#CCCCCC">物品奖励</td>
          <td height="25" bgcolor="#CCCCCC">铜钱奖励</td>
          <td height="25" bgcolor="#CCCCCC">忍阶奖励</td>
          <td height="25" bgcolor="#CCCCCC"><p>操作</p></td>
          </tr>
          <? for($i=0;$i<count($tasklist);$i++)
		  {
			  switch($tasklist[$i]["type"])
			  {
					case 1:
						$type="剧情";
					break;
					case 2:
						$type="循环";
					break;
					case 3:
						$type="日常";
					break;
					case 4:
						$type="节气";
					break;
					case 5:
						$type="随机";
					break;
				}
				
				switch($tasklist[$i]["ingroup"])
				{
					case 1:
						$ingroup="火之国";
					break;
					case 2:
						$ingroup="水之国";
					break;
					case 3:
						$ingroup="土之国";
					break;
				}
				
				$l++;
				if($l%2==0)
				{
					$c='#dff5ff';
				}else{
					$c='#ffffff';
				}
			  
		  ?>
        <tr bgcolor="<?=$c?>" onmouseout=this.bgColor='<?=$c?>' onmouseover=this.bgColor='#BBE9FF' onclick="opc('y_<?=$i?>')">
          <td><?=$tasklist[$i]["name"]?></td>
          <td><?=$type?></td>
          <td><?=$ingroup?></td>
          <td><?=$tasklist[$i]["order"]?></td>
          <td><?=$tasklist[$i]["experience_award"]?></td>
          <td><?=$tasklist[$i]["goods_award"]?></td>
          <td><?=$tasklist[$i]["copper_award"]?></td>
          <td><?=$base_bourn_lv[$tasklist[$i]["bourn_award"]]["name"]?></td>
          <td><a href="./addtask.php?id=<?=$tasklist[$i]["id"]?>">编辑</a> <a href="#" onclick="deltask('<?=$tasklist[$i]["id"]?>')">删除</a>   <a href="#" onclick="copy_c_task('<?=$tasklist[$i]["id"]?>')">复制</a> </td>
          </tr>
          
          <tr>
 		 <td  colspan="9" id="y_<?=$i?>" style="display:none;">
             <table width="95%" border="1" align="right" cellpadding="0" cellspacing="0">
             <tr>
              <td width="5%" bgcolor="#9999FF">排序</td>
              <td width="8%" bgcolor="#9999FF">条件名</td>
              <td width="9%" bgcolor="#9999FF">类型</td>
              <td width="14%" bgcolor="#9999FF">条件</td>
              <td width="15%" bgcolor="#9999FF">引导坐标</td>
              <td width="15%" bgcolor="#9999FF">对话</td>
              <td width="17%" bgcolor="#9999FF">未完成对话</td>
              <td width="10%" bgcolor="#9999FF">选项</td>
              <td width="7%" bgcolor="#9999FF">操作</td>
              </tr>
              <?
              $taskcon=dbarr("SELECT * FROM `base_task_condition` WHERE `task_id`=".$tasklist[$i]["id"]." ORDER BY `order`  ASC");
			  if($taskcon)
			  {
			   for($j=0;$j<count($taskcon);$j++)
		 		{
					
			  ?>
            <tr>
              <td><?=$taskcon[$j]["order"]?></td>
              <td><?=$taskcon[$j]["condition_name"]?></td>
              <td><?=$taskcon[$j]["condition_type"]?></td>
              <td><?=$taskcon[$j]["condition"]?></td>
              <td><?=$taskcon[$j]["guide_xy"]?></td>
              <td><?=$taskcon[$j]["condition_chat"]?></td>
              <td><?=$taskcon[$j]["noinfo"]?></td>
              <td><?=$taskcon[$j]["condition_options"]?></td><!-- c_del_id-->
              <td><a href="caddtask.php?c_edit_id=<?=$taskcon[$j]["id"]?>&task_id=<?=$tasklist[$i]["id"]?>">编辑</a> <a href="#" onclick="del_c_task('<?=$taskcon[$j]["id"]?>')">删除</a></td>
              </tr>
              <?
				}
			  }?>
              <tr>
              <td colspan="9" align="center"><strong><a href="caddtask.php?task_id=<?=$tasklist[$i]["id"]?>">添加条件</a></strong></td>
              </tr>
          </table>
          </td>
         </tr>
          <? }?>
          <tr><td colspan="9" align="center"><?=page($total_row,20)?></td></tr>
        </table>
    </form></td>
  </tr>
</table>
<p>
<?
function deltask($task_id)
{
	sql("delete from base_task where id=".$task_id);
	sql("delete from base_task_condition where task_id=".$task_id);
}

function del_c_task($task_c_id)
{
	sql("delete from base_task_condition where id=".$task_c_id);
}

/**
 * +--------------------------------------------------
 * |   函数名： page($total_row,$pagesize)
 * |   作用：   分页
 * |   参数：　
 * $total_row 共多少条
 * $pagesize 每页多少条
 *
 * |   返回值： $pageHtml
 * +--------------------------------------------------
 */
function page($total_row,$pagesize)
{
	$totalPage=($total_row%$pagesize==0)?intval($total_row/$pagesize): intval($total_row/$pagesize)+1;
	//print '<br>'.$total_row;
	$currentPage = intval($_GET['page']);
	$url         = "?".$_SERVER["QUERY_STRING"]."&page";
	$halfPer     = 5;
	$imagePath   ="images";
	$pageHtml    = page1($totalPage,$currentPage,$url,$halfPer,$imagePath,$keywords,$more);
	$currentPage=($currentPage<1)?1:$currentPage;
	$currentPage=($currentPage>$totalPage)?$totalPage:$currentPage;
	print $pageHtml;
}

function page1($totalPage,$currentPage,$url,$halfPer,$imagePath)
{
		$total=$totalPage;
		$currentPage=($currentPage<1)?1:$currentPage;
		$currentPage=($currentPage>$totalPage)?$totalPage:$currentPage;
		$re="";
		$re .= ( $currentPage > 1 )?"<td><a href=\"$url=1\">首页</a></td>\n<td><a href=\"$url=".($currentPage-1)."\">上一页</a></td>\n":"<td>首页</td>\n";
		for ( $i = $currentPage - $halfPer,$i >= 1 || $i = 1 ,$j = $currentPage + $halfPer, $j < $totalPage || $j = $totalPage;$i <= $j ;$i++ ){//核心算法！
			$re .= ($i == $currentPage)? "<td><b class=currentPage> [$i] </b></td>\n": "<td><a href=\"$url=$i\"> [$i] </a></td>\n";
		}
		$re .= ( $currentPage < $total )? "<td><a href=\"$url=" . ( $currentPage + 1 ) . "\">下一页</a></td>\n<td><a href=\"$url=" . ( $total )."\">尾页</a>\n</td>": "<td>尾页</td>\n";
		$re="<table style=text-align:center class='siyto_b_b_c'><tr>$re</tr></table>";
		return $re;
}
?>
</p>
<form id="form2" name="form2" method="get" action="">
  <input type="text" name="tti" id="tti" />
  <select name="tty" id="tty">
 	<option value="0">全部</option>
    <option value="1">剧情</option>
    <option value="2">循环</option>
    <option value="3">日常</option>
    <option value="4">节日</option>
    <option value="5">随机</option>
  </select>
  <input type="submit" name="button" id="button" value="查询" />
  1剧情,2循环,3每日，4节日，5随机任务  
  <input name="sel" type="hidden" id="sel" value="1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
function opc(id)
{
	var opc_open=document.getElementById(id);
	if(opc_open.style.display=="none")
	{
		opc_open.style.display="block";
	}else{
		opc_open.style.display="none";
	}
}

function deltask(id)
{
	if (confirm ("您确定删除！"))
	{
		 window.location.href="?del_id="+id;
	}
}

function del_c_task(id)
{
	if (confirm ("您确要删除任务子条件！"))
	{
		 window.location.href="?c_del_id="+id;
	}
}

function copy_c_task(id)
{
	if (confirm ("您确要复制这一条任务！"))
	{
		 window.location.href="addtask.php?id="+id+"&copy_id="+id;
	}
}
</script>
</body>
</html>
