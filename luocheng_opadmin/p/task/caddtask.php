<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_qmdj.php");
require(D_R."res/data/base_ninjutsu.php");
require(D_R."res/data/base_npc.php");
require(D_R."res/data/base_monster_list.php");
require(D_R."res/data/base_goods.php");
require(D_R."res/data/base_technology.php");
require(D_R."res/data/base_bres.php");

//原始，2封建，3君主，4启蒙，5工业，6信息，7未来
$base_era=array(1=>"原始",2=>"封建",3=>"君主",4=>"启蒙",5=>"工业",6=>"信息",7=>"未来");
//1防甲,2坐骑,3披风,4秘籍,5兵符,6武器
$equipment=array(1=>"防甲",2=>"坐骑",3=>"披风",4=>"秘籍",5=>"兵符",6=>"武器");

$is_ok=0;

if($_POST["yz"]=="ok" or is_numeric($_POST["yz"]))
{
	$field=array("task_id","order","condition_name","condition_type","condition","guide_xy","condition_chat","noinfo","condition_options","open");
	$value=array($_POST["task_id"],$_POST["order"],$_POST["condition_name"],$_POST["condition_type"],$_POST["condition"],$_POST["guide_xy"],$_POST["condition_chat"],$_POST["noinfo"],$_POST["condition_options"],$_POST["open"]);
	if($_POST["yz"]=="ok")
	{
		dbinsert("base_task_condition", $field, $value);
	}elseif(is_numeric($_POST["yz"])){
		$where="id=".$_POST["yz"];
		dbupdate("base_task_condition", $field, $value,$where);
	}
	$_GET["task_id"]=$_POST["task_id"];
	$is_ok=1;
}

$tasklist=dbarr("SELECT id,name FROM `base_task` ORDER BY `id`  DESC");

if($_GET["c_edit_id"])
{
	$task_info_=dbarr("SELECT * FROM `base_task_condition` where id=".$_GET["c_edit_id"]);
	$task_info=$task_info_[0];
}
$ord=dbarr("SELECT max(`order`) as `order` FROM `base_task_condition` WHERE task_id=".$_GET["task_id"]);
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
    <td align="center"><a href="tasklist.php">任务列表</a></td>
  </tr>
  <tr>
    <td>
    <form action="" method="post" enctype="application/x-www-form-urlencoded" name="form1" target="_self" onSubmit="return check_form()">
      <table width="100%" border="1">
      <tr>
       <? if($is_ok==1){?>
          <td colspan="3" align="center"><b><font color="#FF0000"><? if(is_numeric($_POST["yz"])){echo "【".$_POST["condition_name"]."】编辑成功";}else{echo "【".$_POST["condition_name"]."】添加成功";}?></font><b></td>
           <? }?>
      </tr>
       
        <tr>
          <td align="right">任务ID</td>
          <td><?=$_GET["task_id"]?>
            <input name="task_id" type="hidden" id="task_id" value="<?=$_GET["task_id"]?>" /></td>
          <td width="33%" rowspan="6">条件基础属性（红色必填）</td>
        </tr>
        <tr>
          <td width="15%" align="right"> 条件名 </td>
          <td width="52%"><input name="condition_name" type="text" id="condition_name" value="<?=$task_info["condition_name"]?>" />
            <font color="#FF0000">*
              <input name="yz" type="hidden" id="yz" value="<? if($_GET["c_edit_id"]){echo $_GET["c_edit_id"];}else{echo "ok";}?>" />
            </font></td>
          </tr>
        <tr>
          <td align="right"> 条件类型 </td>
          <!-- 条件类型(1完成杀怪 2完成收集物品（背包可见 goods_ID）3完成收集物品（背包不可见 goods_ID）4与npc交谈 5完成忍阶 6完成奇门遁甲 7完成忍术学习 8装备穿戴( goods_ID) 9忍术修炼) -->
          <td><select name="condition_type" id="condition_type"  onchange="xzs(document.getElementById('condition_type').options[document.getElementById('condition_type').options.selectedIndex].value)">
            <option <? if($task_info["condition_type"]==1){echo "selected";}?> value="1">完成杀怪</option>
            <option <? if($task_info["condition_type"]==2){echo "selected";}?> value="2">完成收集物品（背包可见 goods_ID）</option>
            <option <? if($task_info["condition_type"]==3){echo "selected";}?> value="3">完成收集物品（背包不可见 goods_ID）</option>
            <option <? if($task_info["condition_type"]==4){echo "selected";}?> value="4">与npc交谈 </option>
            <option  <? if($task_info["condition_type"]==6){echo "selected";}?> value="6">完成奇门遁甲</option>
            <option  <? if($task_info["condition_type"]==7){echo "selected";}?> value="7">完成忍术学习</option>
            <option  <? if($task_info["condition_type"]==8){echo "selected";}?> value="8">装备穿戴( goods_ID)</option>
        	<option  <? if($task_info["condition_type"]==9){echo "selected";}?> value="9">忍术修炼</option>
            
            
            <option  <? if($task_info["condition_type"]==10){echo "selected";}?> value="10">招募酒馆英雄（蓝色以上）</option>
            <option  <? if($task_info["condition_type"]==11){echo "selected";}?> value="11">招募世界英雄</option>
            <option  <? if($task_info["condition_type"]==12){echo "selected";}?> value="12">进化到科技时代</option>
            <option  <? if($task_info["condition_type"]==13){echo "selected";}?> value="13">研发科技</option>
            <option  <? if($task_info["condition_type"]==14){echo "selected";}?> value="14">完成征收</option>
            <option  <? if($task_info["condition_type"]==15){echo "selected";}?> value="15">占领城池</option>
            <option  <? if($task_info["condition_type"]==16){echo "selected";}?> value="16">完成锤炼</option>
            <option  <? if($task_info["condition_type"]==17){echo "selected";}?> value="17">攻击玩家</option>
            <option  <? if($task_info["condition_type"]==18){echo "selected";}?> value="18">完成英雄培养</option>
            <option  <? if($task_info["condition_type"]==19){echo "selected";}?> value="19">强化装备</option>
            <option  <? if($task_info["condition_type"]==20){echo "selected";}?> value="20">竞技场挑战</option>
            <option  <? if($task_info["condition_type"]==21){echo "selected";}?> value="21">占领资源</option>
            
            
            <option  <? if($task_info["condition_type"]==22){echo "selected";}?> value="22">元宝消耗</option>
            <option  <? if($task_info["condition_type"]==23){echo "selected";}?> value="23">元宝兑换</option>
            <option  <? if($task_info["condition_type"]==24){echo "selected";}?> value="24">排行榜排名</option>
            <option  <? if($task_info["condition_type"]==25){echo "selected";}?> value="25">提升公会等级</option>
            <option  <? if($task_info["condition_type"]==26){echo "selected";}?> value="26">加入公会</option>
            <option  <? if($task_info["condition_type"]==27){echo "selected";}?> value="27">100分战役（次数）</option>
            <option  <? if($task_info["condition_type"]==28){echo "selected";}?> value="28">挑战战役（次数）</option>
            
            <option  <? if($task_info["condition_type"]==29){echo "selected";}?> value="29">挑战世界BOSS</option>
          </select>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td align="right"> 条件 </td>
          <td><input name="condition" type="text" id="condition" value="<?=$task_info["condition"]?>" size="30" />            <font color="#FF0000">*
            <select name="goods" id="goods" style="display:none">
             <option value="0">请选择</option>
		  <?
				foreach((array)$base_goods as $key=>$value)
				{
					echo "<option  value='".$key."'>".$value["goods_name"]."</option>";
				}
		  ?>
            </select>
            <select name="monster_list" id="monster_list" style="display:none">
            <option value="0">请选择</option>
		  <?
				foreach((array)$base_monster_list as $key=>$value)
				{
					echo "<option  value='".$value["id"]."'>".$value["monster_name"]."</option>";
				}
		  ?>
            </select>
            <select name="npc_list" id="npc_list" style="display:none">
              <option value="0">请选择</option>
		  <?
				foreach((array)$base_npc as $key=>$value)
				{
					echo "<option  value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            <select name="qmdj" id="qmdj" style="display:none">
             <option value="0">请选择</option>
            <?
				foreach((array)$base_qmdj as $key=>$value)
				{
					echo "<option  value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            <select name="ninjutsu" id="ninjutsu" style="display:none">
             <option value="0">请选择</option>
             <?
				foreach((array)$base_ninjutsu as $key=>$value)
				{
					echo "<option  value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            
            
            
            
            
            
            
            
             <select name="era" id="era" style="display:none">
             <option value="0">请选择</option>
             <?
				foreach((array)$base_era as $key=>$value)
				{
					echo "<option  value='".$key."'>".$value."</option>";
				}
		  ?>
            </select>
            
             <select name="technology" id="technology" style="display:none">
             <option value="0">请选择</option>
             <?
				foreach((array)$base_technology as $key=>$value)
				{
					echo "<option  value='".$value["id"]."'>".$value["technology_name"]."</option>";
				}
		  ?>
            </select>
            
             <select name="equipment" id="equipment" style="display:none">
             <option value="0">请选择</option>
             <?
				foreach((array)$equipment as $key=>$value)
				{
					echo "<option  value='".$key."'>".$value."</option>";
				}
		  ?>
            </select>
            
             <select name="resoccupy" id="resoccupy" style="display:none">
             <option value="0">请选择</option>
             <?
				foreach((array)$base_bres as $key=>$value)
				{
					if($value["type"]==1)
					{
						echo "<option  value='".$value["id"]."'>".$value["name"]."</option>";
					}
				}
		  ?>
            </select>
            <input type="button" name="addbutton" id="addbutton" value="添加条件" />
          </font></td>
          </tr>
        <tr>
          <td align="right">顺序</td>
          <td><input name="order" type="text" id="order" value="<? 
          if(is_numeric($task_info["order"]))
          {
          	echo $task_info["order"];
          }else{
          	if(is_numeric($ord[0]["order"]))
          	{
          		echo $ord[0]["order"]+1;
          	}else{
          		echo 0;
          	}
          }
          ?>" size="5" /></td>
          </tr>
        <tr>
          <td align="right">引导坐标</td>
          <td><input name="guide_xy" type="text" id="guide_xy" value="<?=$task_info["guide_xy"]?>" size="30" /> 
            格式：地图ID_x1:y1_x2:y2</td>
          </tr>
           <tr>
          <td colspan="3" align="right" height="2" bordercolor="#999999"></td>
          </tr>
        <tr>
          <td align="right"> NPC对话</td>
          <td><textarea name="condition_chat" cols="50" rows="5" id="condition_chat"><?=$task_info["condition_chat"]?></textarea>
            （与npc交谈生效）</td>
            <td rowspan="4">条件聊天（红色必填）</td>
        </tr>
       
        <tr>
          <td align="right"> 未完成条件对话 </td>
          <td><textarea name="noinfo" cols="50" rows="5" id="noinfo"><?=$task_info["noinfo"]?></textarea>
            <font color="#FF0000">*</font></td>
          
        </tr>
        <tr>
          <td align="right">开窗</td>
          <td><select name="open" id="open">
            <option <? if($task_info["open"]==1){echo "selected";}?>  value="1">开启</option>
            <option <? if($task_info and $task_info["open"]!=1){echo "selected";}?>  value="0">关闭</option>
          </select></td>
        </tr>
        <tr>
          <td align="right"> 玩家回答 </td>
          <td><input name="condition_options" type="text" id="condition_options" value="<?=$task_info["condition_options"]?>" size="30" />
            <font color="#FF0000">*</font></td>
        </tr>
        <tr>
          <td colspan="3" align="right" height="2" bordercolor="#999999"></td>
        </tr>
 
        <tr>
          <td colspan="3" align="center"><? if($_GET["c_edit_id"]){?><input type="submit" name="button" id="button" value="编辑条件" /><? }else{?><input type="submit" name="button" id="button" value="添加条件" /><? }?></td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
<script type='text/javascript'>
function addgoods_js()
{
	var sel=false;
	var cty=document.getElementById("condition_type");
	var condition_type=cty.options[cty.options.selectedIndex].value;
	
	if(condition_type==1)
	{//打开怪物
		sel=document.getElementById("monster_list");
	}
	
	if(condition_type==2 || condition_type==3  || condition_type==8)
	{	//打开物品
		sel=document.getElementById("goods");
	}
	
	if(condition_type==4)
	{//打开NPC列表
		sel=document.getElementById("npc_list");
	}
	
	if(condition_type==6)
	{//打开奇门遁甲
		sel=document.getElementById("qmdj");
	}
	
	if(condition_type==7 || condition_type==9)
	{//打开忍术
		sel=document.getElementById("ninjutsu");
	}
	
	if(condition_type==12)
	{//进化科技时代
		sel=document.getElementById("era");
	}
	if(condition_type==13)
	{//研发科技
		sel=document.getElementById("technology");
	}
	if(condition_type==19)
	{//强化装备
		sel=document.getElementById("equipment");
	}
	if(condition_type==21)
	{//占领资源
		sel=document.getElementById("resoccupy");
	}
	
	if(!sel)
	{
		document.getElementById("condition").value="x:0:1";
		return false	
	}
	
	var addgoods_id= sel.options[sel.options.selectedIndex].value//你要的值
	
	if(addgoods_id!=0)
	{
		var goods_awardinfo=document.getElementById("condition").value;
		if(goods_awardinfo)
		{
			var garr=split_my(goods_awardinfo);
			if(garr[addgoods_id])
			{
				alert("此条件经存在");	
				return true;
			}
			
			document.getElementById("condition").value+=","+addgoods_id+":0:1";
		}else{
			document.getElementById("condition").value=addgoods_id+":0:1";
		}
	}else{
		alert("请选择条件");
	}
}

function split_my(str)
{
	var runarr=new Array();   
	var garr=str.split(",");
	for(i=0;i<garr.length;i++)
	{
		var garr_arr=garr[i].split(":");
		runarr[garr_arr[0]]=new Array(garr_arr[1],garr_arr[2]);
	}
	return runarr;
}

function check_form()
{
	if(!document.getElementById("condition_name").value)
	{
		alert("请填写【条件名称】");
		document.getElementById("condition_name").focus();
		document.getElementById("condition_name").select();
		return false;
	}
	
	var condty=document.getElementById("condition_type");
	var condition_type= condty.options[condty.options.selectedIndex].value;

	if(!condition_type || condition_type==0)
	{
		alert("请选择条件类型");
		renpcid.focus();
		return false;
	}
	
	
	
	if(!document.getElementById("condition").value)
	{
		alert("请填写条件");
		document.getElementById("condition").focus();
		document.getElementById("condition").select();
		return false;
	}
	
	
	if(!document.getElementById("noinfo").value)
	{
		alert("请填写未完成条件对话");
		document.getElementById("noinfo").focus();
		document.getElementById("noinfo").select();
		return false;
	}

	if(!document.getElementById("condition_options").value)
	{
		alert("请填写玩家说话");
		document.getElementById("condition_options").focus();
		document.getElementById("condition_options").select();
		return false;
	}
	
	if (confirm ("您确定要提交吗？"))
	{
		return true;
	}else{
		return false;
	}
}


function xzs(type)
{
	//初始化全部关闭 goods monster_list npc_list qmdj  ninjutsu
	document.getElementById("goods").style.display="none";
	document.getElementById("monster_list").style.display="none";
	document.getElementById("npc_list").style.display="none";
	document.getElementById("qmdj").style.display="none";
	document.getElementById("ninjutsu").style.display="none";
	
	document.getElementById("era").style.display="none";
	document.getElementById("technology").style.display="none";
	document.getElementById("equipment").style.display="none";
	document.getElementById("resoccupy").style.display="none";
	
	document.getElementById("condition").value="";

	if(type==1)
	{//打开怪物
		document.getElementById("monster_list").style.display="";
	}
	
	if(type==2 || type==3  || type==8)
	{	//打开物品
		document.getElementById("goods").style.display="";
	}
	
	if(type==4)
	{//打开NPC列表
		document.getElementById("npc_list").style.display="";
	}
	
	if(type==6)
	{//打开奇门遁甲
		document.getElementById("qmdj").style.display="";
	}
	
	if(type==7||type==9)
	{//打开忍术
		document.getElementById("ninjutsu").style.display="";
	}
	
	
	
	
	if(type==12)
	{//进化科技时代
		document.getElementById("era").style.display="";
	}
	if(type==13)
	{//研发科技
		document.getElementById("technology").style.display="";
	}
	if(type==19)
	{//强化装备
		document.getElementById("equipment").style.display="";
	}
	if(type==21)
	{//占领资源
		document.getElementById("resoccupy").style.display="";
	}
	
}

var objTest = document.getElementById("addbutton");
objTest.onclick = addgoods_js;
</script>
<?
if($task_info["condition_type"])
{
	echo "<script type='text/javascript'>
	var condition__=document.getElementById('condition').value;
	xzs(".$task_info['condition_type'].");
	document.getElementById('condition').value=condition__;
	</script>";
}else{
	echo "<script type='text/javascript'>
	var condition__=document.getElementById('condition').value;
	xzs(1);
	document.getElementById('condition').value=condition__;
	</script>";
}
?>
</body>
</html>
