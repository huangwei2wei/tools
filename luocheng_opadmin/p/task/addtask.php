<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_qmdj.php");
require(D_R."res/data/base_ninjutsu.php");
require(D_R."res/data/base_npc.php");
require(D_R."res/data/base_bourn_lv.php");

if($_GET["copy_id"] and $_GET["id"] and !$_POST["yz"])
{
	$task_info_=dbarr("SELECT * FROM `base_task` where id=".$_GET["copy_id"]);
	$task_info=$task_info_[0];
	unset($task_info["id"]);
	foreach((array)$task_info as $k=>$v)
	{
		if(!is_numeric($k))
		{
			$field[]=$k;
			$value[]=$v;
		}
	}
	
	dbinsert("base_task", $field, $value);
	
	$id=lastlyid("base_task","id");
	$btc_arr=dbarr("SELECT * FROM `base_task_condition` WHERE `task_id`=".$_GET["copy_id"]);
	foreach((array)$btc_arr as $kk=>$vv)
	{
		$vv["task_id"]=$id;
		unset($vv["id"],$field,$value);
		foreach((array)$vv as $k=>$v)
		{
			if(!is_numeric($k))
			{
				$field[]=$k;
				$value[]=$v;
			}
		}
		dbinsert("base_task_condition", $field, $value);
	}
	$_GET["id"]=$id;
}

$is_ok=0;
if($_POST["yz"]=="ok" or is_numeric($_POST["yz"]))
{
	$field=array("name","type","ingroup","order","receive_npc_id","complete_npc_id","front_lv","front_bourn_grade","front_qmdj","front_task_id","front_ninjutsu","experience_award","goods_award","copper_award","bourn_award","info","complete_info","players_answer","contribution","front_team");
	$value=array($_POST["name"],$_POST["type"],$_POST["ingroup"],$_POST["order"],$_POST["receive_npc_id"],$_POST["complete_npc_id"],$_POST["front_lv"],$_POST["front_bourn_grade"],$_POST["front_qmdj"],$_POST["front_task_id"],$_POST["front_ninjutsu"],$_POST["experience_award"],$_POST["goods_award"],$_POST["copper_award"],$_POST["bourn_award"],$_POST["info"],$_POST["complete_info"],$_POST["players_answer"],$_POST["contribution"],$_POST["front_team"]);
	if($_POST["yz"]=="ok")
	{
		dbinsert("base_task", $field, $value);
	}elseif(is_numeric($_POST["yz"])){
		$where="id=".$_POST["yz"];
		dbupdate("base_task", $field, $value,$where);
	}
	$is_ok=1;
}

$tasklist=dbarr("SELECT id,name,ingroup FROM `base_task` ORDER BY `id`  DESC");
$goods_list=dbarr("SELECT a.goods_name,b.goods_ext_id FROM `base_goods` a inner join base_goods_ext b on a.id=b.goods_id WHERE 1");

if($_GET["id"])
{
	$task_info_=dbarr("SELECT * FROM `base_task` where id=".$_GET["id"]);
	$task_info=$task_info_[0];
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
    <td align="center"><strong>添加任务</strong></td>
    <td align="center"><a href="tasklist.php">任务列表</a></td>
  </tr>
  <tr>
    <td colspan="2">
    <form action="" method="post" enctype="application/x-www-form-urlencoded" name="form1" target="_self" onSubmit="return check_form()">
      <table width="100%" border="1">
      <tr>
       <? if($is_ok==1){?>
          <td colspan="3" align="center"><b><font color="#FF0000"><? if(is_numeric($_POST["yz"])){echo "【".$_POST["name"]."】编辑成功";}else{echo "【".$_POST["name"]."】添加成功";}?></font><b></td>
           <? }?>
      </tr>
       
        <tr>
          <td width="15%" align="right"> 任务名 </td>
          <td width="52%"><input name="name" type="text" id="name" value="<?=$task_info["name"]?>" />
            <font color="#FF0000">*
              <input name="yz" type="hidden" id="yz" value="<? if($_GET["id"]){echo $_GET["id"];}else{echo "ok";}?>" />
            </font></td>
          <td width="33%" rowspan="6">任务基础属性（红色必填）</td>
          </tr>
        <tr>
          <td align="right"> 任务类型 </td>
          <td><select name="type" id="type">
            <!--<option <? if($task_info["type"]==1){echo "selected";}?> value="1">剧情</option>
            <option <? if($task_info["type"]==2){echo "selected";}?> value="2">循环</option>
            <option <? if($task_info["type"]==3){echo "selected";}?> value="3">日常</option>
            <option <? if($task_info["type"]==4){echo "selected";}?> value="4">节气</option>
            <option <? if($task_info["type"]==5){echo "selected";}?> value="5">随机</option>-->
            
            
            <option <? if($task_info["type"]==1){echo "selected";}?> value="1">主线</option>
            <option <? if($task_info["type"]==2){echo "selected";}?> value="2">支线</option>
            <option <? if($task_info["type"]==3){echo "selected";}?> value="3">每日</option>
            <option <? if($task_info["type"]==4){echo "selected";}?> value="4">活动</option>
            <option <? if($task_info["type"]==5){echo "selected";}?> value="5">新手</option>
            </select>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td align="right"> 任务属国 </td>
          <td><select disabled="disabled" name="ingroup" id="ingroup">
            <option <? if($task_info["ingroup"]==1){echo "selected";}?> value="1">火之国</option>
            <option <? if($task_info["ingroup"]==2){echo "selected";}?> value="2">水之国</option>
            <option <? if($task_info["ingroup"]==3){echo "selected";}?> value="3">土之国</option>
            </select>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td align="right"> 任务顺序 </td>
          <td><input name="order" type="text" id="order" value="<? if($task_info["id"]){echo $task_info["id"];}else{echo $tasklist[0]["id"]+1;}?>" size="5" /></td>
          </tr>
        <tr>
          <td align="right"> 领取任务NPC </td>
          <td><select name="receive_npc_id" id="receive_npc_id">
          <option value="0">请选择</option>
          <?
				foreach((array)$base_npc as $key=>$value)
				{
					if($value["slg_type"]==8)
					{
						$ing="";
						if($value["ingroup"]==1)
						{
							$ing="火";
						}elseif($value["ingroup"]==2){
							$ing="水";
						}elseif($value["ingroup"]==3){
							$ing="土";
						}elseif($value["ingroup"]==4){
							$ing="中";
						}
						 if($task_info["receive_npc_id"]==$value["id"]){$selec= "selected";}else{$selec= "";}
						echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."(".$ing.")</option>";
					}
				}
		  ?>
            </select>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td align="right"> 提交任务NPC </td>
          <td><select name="complete_npc_id" id="complete_npc_id">
          <option value="0">请选择</option>
           <?
				foreach((array)$base_npc as $key=>$value)
				{
					if($value["slg_type"]==9)
					{
						$ing="";
						if($value["ingroup"]==1)
						{
							$ing="火";
						}elseif($value["ingroup"]==2){
							$ing="水";
						}elseif($value["ingroup"]==3){
							$ing="土";
						}elseif($value["ingroup"]==4){
							$ing="中";
						}
						if($task_info["complete_npc_id"]==$value["id"]){$selec= "selected";}else{$selec= "";}
						echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."(".$ing.")</option>";
					}
				}
		  ?>
            </select>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td colspan="3" align="right" height="2" bordercolor="#999999"></td>
          </tr>
        <tr>
          <td align="right"> 领取等级 </td>
          <td><input name="front_lv" type="text" id="front_lv" value="<?=intval($task_info["front_lv"])?>" size="5" />
            <font color="#0000FF">*</font></td>
          <td rowspan="6">任务领取属性</td>
        </tr>
        <tr>
          <td align="right"> 领取忍阶(时代) </td>
          <td><select name="front_bourn_grade" id="front_bourn_grade">
		   <option value="0">请选择</option>
		  <?
		  $base_bourn_lv=array(
		  						array("id"=>1,"name"=>"原始"),
								array("id"=>2,"name"=>"封建"),
								array("id"=>3,"name"=>"君主"),
								array("id"=>4,"name"=>"启蒙"),
								array("id"=>5,"name"=>"工业"),
								array("id"=>6,"name"=>"信息"),
								array("id"=>7,"name"=>"未来"),
		  				);
				foreach((array)$base_bourn_lv as $key=>$value)
				{
					if($task_info["front_bourn_grade"]==$value["id"]){$selec= "selected";}else{$selec= "";}
					echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
          </select>
            <font color="#0000FF">*</font></td>
          </tr>
          <!--
        <tr>
          <td align="right"> 领取奇门遁甲 </td>
          <td><select name="front_qmdj" id="front_qmdj">
          <option value="0">请选择</option>
           <?
				foreach((array)$base_qmdj as $key=>$value)
				{
					if($task_info["front_qmdj"]==$value["id"]){$selec= "selected";}else{$selec= "";}
					echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            <font color="#0000FF">*</font></td>
          </tr>
          -->
        <tr>
          <td align="right"> 领取前置任务 </td>
          <td><select name="front_task_id" id="front_task_id">
           <option value="0">请选择</option>
          <?
			foreach((array)$tasklist as $key=>$value)
			{
				if($task_info["front_task_id"]==$value["id"]){$selec= "selected";}else{$selec= "";}
				$ing="";
				if($value["ingroup"]==1)
				{
					$ing="火";
				}elseif($value["ingroup"]==2){
					$ing="水";
				}elseif($value["ingroup"]==3){
					$ing="土";
				}
				
				if($task_info["ingroup"])
				{
					
					if($task_info["ingroup"]==$value["ingroup"])
					{
						echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."(".$ing.")</option>";
					}
				}else{
					echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."(".$ing.")</option>";
				}
			}
		  ?>
            </select>
            <font color="#0000FF">*</font></td>
          </tr>
        <tr>
          <td align="right">前置组队</td>
          <td><select name="front_team" id="front_team">
            <option <? if($task_info["front_team"]==2){echo "selected";}?>  value="2">请选择</option>
            <option <? if($task_info["front_team"]==1){echo "selected";}?>  value="1">组队</option>
            <option <? if($task_info["front_team"]===0){echo "selected";}?>  value="0">未组队</option>
            <option <? if($task_info["front_team"]==2){echo "selected";}?>  value="2">无状态</option>
          </select>
            <font color="#0000FF">*</font></td>
        </tr>
        <tr>
          <td align="right"> 前置忍术（技能） </td>
          <td><select name="front_ninjutsu" id="front_ninjutsu">
          <option value="0">请选择</option>
           <?
				foreach((array)$base_ninjutsu as $key=>$value)
				{
					if($task_info["front_ninjutsu"]==$value["id"]){$selec= "selected";}else{$selec= "";}
					echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            <font color="#0000FF">*</font></td>
          </tr>
        <tr>
          <td colspan="3" align="right" height="2" bordercolor="#999999"></td>
          </tr>
        <tr>
          <td align="right"> 完成任务经验奖励 </td>
          <td><input name="experience_award" type="text" id="experience_award" value="<?=intval($task_info["experience_award"])?>" />
            <font color="#0000FF">*</font></td>
          <td rowspan="5">任务奖励属性（蓝色选填，但必须有一项）</td>
        </tr>
        <tr>
          <td align="right"> 完成任务物品奖励 </td>
          <td><input name="goods_award" type="text" id="goods_award" value="<?=$task_info["goods_award"]?>" size="40" />
            <select name="addgoods" id="addgoods">
             <option value="0">请选择</option>
           <?
				foreach((array)$goods_list as $key=>$value)
				{
					echo "<option value='".$value["goods_ext_id"]."'>".$value["goods_name"]."</option>";
				}
		  ?>
              </select>
            <input type="button" name="addbutton" id="addbutton" value="添加物品" />
            <font color="#0000FF">*</font></td>
          </tr>
        <tr>
          <td align="right"> 铜钱奖励 </td>
          <td><input name="copper_award" type="text" id="copper_award" value="<?=intval($task_info["copper_award"])?>" />
            <font color="#0000FF">*</font></td>
          </tr>
        <tr>
          <td align="right">奖励贡献</td>
          <td><input name="contribution" type="text" id="contribution" value="<?=intval($task_info["contribution"])?>" /></td>
        </tr>
        <tr>
          <td align="right"> 忍阶奖励 </td>
          <td><select name="bourn_award" id="bourn_award">
          <option value="0">请选择</option>
		  <?
				foreach((array)$base_bourn_lv as $key=>$value)
				{
					if($task_info["bourn_award"]==$value["id"]){$selec= "selected";}else{$selec= "";}
					echo "<option ".$selec." value='".$value["id"]."'>".$value["name"]."</option>";
				}
		  ?>
            </select>
            <font color="#0000FF">*</font></td>
          </tr>
        <tr>
          <td colspan="3" align="right" height="2" bordercolor="#999999"></td>
          </tr>
        <tr>
          <td align="right"> 领取NPC说话</td>
          <td><textarea name="info" cols="50" rows="5" id="info"><?=$task_info["info"]?></textarea>
            <font color="#FF0000">*</font></td>
          <td rowspan="3">任务说话属性（红色必填）</td>
        </tr>
        <tr>
          <td align="right">提交NPC说话 </td>
          <td><textarea name="complete_info" cols="50" rows="5" id="complete_info"><?=$task_info["complete_info"]?></textarea>
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td align="right">玩家回答 </td>
          <td><input name="players_answer" type="text" id="players_answer" value="<?=$task_info["players_answer"]?>" />
            <font color="#FF0000">*</font></td>
          </tr>
        <tr>
          <td colspan="3" align="center"><? if($_GET["id"]){?><input type="submit" name="button" id="button" value="编辑任务" /><? }else{?><input type="submit" name="button" id="button" value="添加任务" /><? }?></td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
<script type='text/javascript'>
var objTest = document.getElementById("addbutton");
function addgoods_js()
{
	var sel=document.getElementById("addgoods");
	var addgoods_id= sel.options[sel.options.selectedIndex].value//你要的值

	if(addgoods_id!=0)
	{
		var goods_awardinfo=document.getElementById("goods_award").value;
		if(goods_awardinfo)
		{
			var garr=split_my(goods_awardinfo);
			if(garr[addgoods_id])
			{
				alert("此物品已经存在");	
				return true;
			}
			
			document.getElementById("goods_award").value+=","+addgoods_id+":0:1";
		}else{
			document.getElementById("goods_award").value=addgoods_id+":0:1";
		}
	}else{
		alert("请选择物品");
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
	if(!document.getElementById("name").value)
	{
		alert("请填写【任务名称】");
		document.getElementById("name").focus();
		document.getElementById("name").select();
		return false;
	}
	
	var renpcid=document.getElementById("receive_npc_id");
	var receive_npc_id= renpcid.options[renpcid.options.selectedIndex].value;

	if(!receive_npc_id || receive_npc_id==0)
	{
		alert("请选择领取NPC");
		renpcid.focus();
		return false;
	}
	
	
	var conpcid=document.getElementById("complete_npc_id");
	var complete_npc_id= conpcid.options[conpcid.options.selectedIndex].value;
	if(!complete_npc_id || complete_npc_id==0)
	{
		alert("请选择提交NPC");
		conpcid.focus();
		return false;
	}
	
	
	if((!document.getElementById("experience_award").value || document.getElementById("experience_award").value==0) && 
	!document.getElementById("goods_award").value && 
	(!document.getElementById("copper_award").value || document.getElementById("copper_award").value==0) && 
	(!document.getElementById("bourn_award").value || document.getElementById("bourn_award").value==0))
	{
		alert("任务必须有一个奖励");
		document.getElementById("experience_award").focus();
		document.getElementById("experience_award").select();
		return false;
	}
		
	if(!document.getElementById("info").value)
	{
		alert("请填写领取npc说话");
		document.getElementById("info").focus();
		document.getElementById("info").select();
		return false;
	}
	
	if(!document.getElementById("complete_info").value)
	{
		alert("请填写提交npc说话");
		document.getElementById("complete_info").focus();
		document.getElementById("complete_info").select();
		return false;
	}

	if(!document.getElementById("players_answer").value)
	{
		alert("请填写玩家说话");
		document.getElementById("players_answer").focus();
		document.getElementById("players_answer").select();
		return false;
	}
	
	if (confirm ("您确定要提交吗？"))
	{
		return true;
	}else{
		return false;
	}
}

objTest.onclick = addgoods_js;
</script>
</body>
</html>
