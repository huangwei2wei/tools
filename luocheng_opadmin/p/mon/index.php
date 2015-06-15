<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_map_nexus.php");
require(D_R."res/data/base_monster_list.php");

if($_GET["del_id"])
{
	sql("delete from base_regional_map where id=".$_GET["del_id"]);
	$_GET["list"]=1;
}

if($_GET['sc'])
{
	sc($_GET['sc']);	
}
$is_ok=0;
if($_POST["yz"]=="ok" or is_numeric($_POST["yz"]))
{
	$field=array("map_id","name","regional","monster","probability");
	$value=array($_POST["map_id"],$_POST["name"],$_POST["regional"],$_POST["monster"],$_POST["probability"]);
	if($_POST["yz"]=="ok")
	{
		dbinsert("base_regional_map", $field, $value);
	}elseif(is_numeric($_POST["yz"])){
		$where="id=".$_POST["yz"];
		dbupdate("base_regional_map", $field, $value,$where);
	}
	$_GET["add"]=0;
	$_GET["list"]=1;
	$is_ok=1;
}

if($_GET["map_id"])
{
	$list=dbarr("SELECT * FROM `base_regional_map` where map_id=".$_GET["map_id"]." ORDER BY `id`  DESC");
}else{
	$list=dbarr("SELECT * FROM `base_regional_map` ORDER BY `id`  DESC");
}

if($_GET["edit"])
{
	$list_=dbarr("SELECT * FROM `base_regional_map` where id=".$_GET["edit"]." ORDER BY `id`  DESC");
	$info=$list_[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript" src="/p/js/public.js"></script>
<title>无标题文档</title>
</head>

<body>
<table width="699" border="1">
  <tr>
    <td width="329" align="center"><a href="?add=1">添加</a></td>
    <td width="354" align="center"><a href="?list=1">列表</a></td>
  </tr>
  <tr>
    <td colspan="2">
    <?
    if(!$_GET["add"])
	{
	?>
    <table width="965" border="1">
        <tr>
          <td colspan="5">
            <select name="select4" id="select4" onchange="mapinfo(this,'list')">
             <option value="0">全部</option>
              <?
      foreach((array)$base_map_nexus as $key=>$value)
      {
      ?>
              <option <? if($value["id"]==$_GET["map_id"]){echo "selected";}?>  value="<?=$value["id"]?>">
                <?=$value["map_dependency"]?>--<?=$value["map_name"]?>
                </option>
              <?
       }
       ?>
            </select>
            <?
            if($_GET["map_id"])
			{
             ?><a href="?map_id=<?=$_GET["map_id"]?>&sc=<?=$_GET["map_id"]?>">生成</a> <a href="mc.php?map_id=<?=$_GET["map_id"]?>" target="_blank">查看怪物分布图</a>            <?
			}?>
          </td>
        </tr>
        <tr>
          <td>区域名</td>
          <td>区域范围</td>
          <td>怪物</td>
          <td>概率</td>
          <td>操作</td>
        </tr>
        <? 
		foreach((array)$list as $key=>$value)
		{
			if($value)
			{
		?>
        <tr>
          <td width="191" height="27"><?=$value["name"]?></td>
          <td width="220"><?=$value["regional"]?></td>
          <td width="208"><?=$value["monster"]?></td>
          <td width="171"><?=$value["probability"]?></td>
          <td width="141"><a href="?add=1&edit=<?=$value["id"]?>">编辑</a>  <a href="#" onclick="deltask('<?=$value["id"]?>')">删除</a></td>
        </tr>
        <? }}?>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
    </table>
    <? }else{?>
      <form id="form1" name="form1" method="post" action="">
      <table width="668" border="1">
        <tr>
          <td width="60">地图</td>
          <td width="472">
          <select name="map_id" id="map_id">
          <?
          foreach((array)$base_map_nexus as $key=>$value)
		  {
		  ?>
            <option <? if($value["id"]==$info["map_id"]){echo "selected";}?>  value="<?=$value["id"]?>"><?=$value["map_dependency"]?>--<?=$value["map_name"]?></option>
           <?
		   }
		   ?>
          </select>
          <?
          if($_GET["edit"])
		  {
			  echo ' <input name="yz" type="hidden" id="yz" value="'.$_GET["edit"].'" />';
		 }else{
				 echo ' <input name="yz" type="hidden" id="yz" value="ok" />'; 
		}
		  ?>
         </td>
          <td width="114">&nbsp;</td>
        </tr>
        <tr>
          <td>名称</td>
          <td><input name="name" type="text" id="name" value="<?=$info["name"]?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>区域</td>
          <td><input name="regional" type="text" id="regional" value="<?=$info["map_id"]?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>怪物</td>
          <td><input name="monster" type="text" id="monster" value="<?=$info["monster"]?>" />
            <select name="monstersel" id="monstersel">
            <?
          foreach((array)$base_monster_list as $key=>$value)
		  {
		  ?>
            <option value="<?=$value["id"]?>"><?=$value["monster_name"]?></option>
           <?
		   }
		   ?>
            </select>
            <input type="button" name="addbutton" id="addbutton" value="加怪物" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>概率</td>
          <td><input name="probability" type="text" id="probability" value="<?=$info["probability"]?>" size="5" />
          *</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="center"><input type="submit" name="button" id="button" value="提交" /></td>
        </tr>
      </table>
    </form>
    <? }?>
    </td>
  </tr>
</table>
<script type='text/javascript'>
var objTest = document.getElementById("addbutton");

function addgoods_js()
{
	var sel=document.getElementById("monstersel");
	var addgoods_id= sel.options[sel.options.selectedIndex].value//你要的值

	if(addgoods_id!=0)
	{
		var goods_awardinfo=document.getElementById("monster").value;
		if(goods_awardinfo)
		{
			var garr=split_my(goods_awardinfo);
			if(garr[addgoods_id])
			{
				alert("此怪物已经存在");	
				return true;
			}
			
			document.getElementById("monster").value+=","+addgoods_id+":0:1";
		}else{
			document.getElementById("monster").value=addgoods_id+":0:1";
		}
	}else{
		alert("请选择怪物");
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

function mapinfo(obj,e)
{
	 window.location.href="?map_id="+obj.value+"&"+e+"=1";
	//ajax_ultimate("monster_c.php?switch=map&map="+obj.value,"mapinfo_");
}

function deltask(id)
{
	if (confirm ("请注意您的操作，删除这条规则后果极其恐怖，如果仍要删除，请点击确定！"))
	{
		 window.location.href="?del_id="+id;
	}
}


objTest.onclick = addgoods_js;
</script>

<?
function sc($map_id)
{
	$marr=array();
	$str="";
	$i=0;
	$list=dbarr("SELECT * FROM `base_regional_map` where map_id=".$map_id." ORDER BY `id`  DESC");
	foreach((array)$list as $key=>$value)
	{
			$regional=$value["regional"];
			$monster=$value["monster"];
			$probability=$value["probability"];
			$regional_arr=explode("@",$regional);
			$regional_1=explode("@",$regional_arr[0]);
			$regional_2=explode("@",$regional_arr[1]);
			
			$monster_arr=arrayed($monster);
			foreach((array)$monster_arr as $k=>$v)
			{
				$i++;
				$x=rand($regional_1[0],$regional_2[0]);
				$y=rand($regional_1[1],$regional_2[1]);
				if($v[1]>1)
				{
					$v[1]=round($v[1]*$probability);
				}
				$xy1=($x-150)."_".$y;
				$xy2=($x+150)."_".$y;
				
				$marr[$i]=array(
							  "xy1"=>$xy1,
							  "xy2"=>$xy2,
							  "m_l_id"=>$k,
						);
				//1:1:1040_865@1157_874
				$str.=$k.":".$i.":".$xy1."@".$xy2.",";
			}
	}
	$str=substr($str,0,-1);
	//sql("UPDATE  `base_map_nexus` SET  `monster` =  '".$str."' WHERE  `id` =".$map_id);
}
?>
</body>
</html>