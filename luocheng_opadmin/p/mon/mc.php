<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_map_nexus.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/p/js/css.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/p/js/public.js"></script>
<title>无标题文档</title>
</head>

<body>
<?
if($_GET["map_id"]==1 or $_GET["map_id"]==7 or $_GET["map_id"]==13 or $_GET["map_id"]==19)
{
	$w=720;
	$h=480;
}else{
	$w=1400;
	$h=933;
}
?>
<div style="background-color:#000"><a onclick="op()" href="#">添加</a> &nbsp;&nbsp;&nbsp;<a  onclick="vehicle()" href="#">保存</a> &nbsp;&nbsp;&nbsp; <a  onclick="zairu(<?=$_GET["map_id"]?>)" href="#">载入游戏中</a></div>
<div id="rongqi" style="width:<?=$w?>px; height:<?=$h?>px; background-color:#99F;  background-image: url(map/id<?=$_GET["map_id"]?>.jpg); layer-background-image: url(map/id<?=$_GET["map_id"]?>.jpg); border: 1px none #000000;">
<?
$map_id=$_GET["map_id"];
sc($map_id);
?>
</div>

<?
function sc($map_id)
{
	require(D_R."res/data/base_monster_list.php");
	$b=5;
	
	$list=dbarr("SELECT * FROM `base_map_monster` where map_id=".$map_id." ORDER BY `id`  DESC");
	foreach((array)$list as $key=>$value)
	{
			$mxy_arr1=explode(":",$value["xy1"]);
			$mxy_arr2=explode(":",$value["xy2"]);
			
			$xd=intval(abs($mxy_arr1[0]-$mxy_arr2[0])/5);
			$yd=intval(abs($mxy_arr1[1]-$mxy_arr2[1])/5);
			
			$dd=distance(intval($mxy_arr1[0]/$b).":".intval($mxy_arr1[1]/$b),intval($mxy_arr2[0]/$b).":".intval($mxy_arr2[1]/$b));
			$info="原始:".$value["xy1"]."_".$value["xy2"]."<br>序列:".$value["id"]."<br>list名:".$base_monster_list[$value["monster_id"]]["monster_name"]."<br>直线距离:".$dd;
			if($value["xy1"] and $value["xy2"])
			{
				echo '<div mid='.$value["monster_id"].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr1[0]/$b).'px;top:'.intval($mxy_arr1[1]/$b).'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'.$value["id"].'"  onmousedown="canmoveto2(this,document.getElementById(\'id_'.$value["id"].'_\'),'.$xd.','.$yd.',0, event)">'.$value["monster_id"].'</div>'."\n";
			
				echo '<div mid='.$value["monster_id"].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr2[0]/$b).'px;top:'.intval($mxy_arr2[1]/$b).'px;background-color:#90F; width:15px; height:15px; position:absolute;" id="id_'.$value["id"].'_"  onmousedown="canmoveto2(this,document.getElementById(\'id_'.$value["id"].'\'),'.$xd.','.$yd.',1, event)">'.$value["monster_id"].'</div>'."\n";
			}else{
				echo '<div mid='.$value["monster_id"].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr1[0]/$b).'px;top:'.intval($mxy_arr1[1]/$b).'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'.$value["id"].'"  onmousedown="canmove(this,event)">'.$value["monster_id"].'</div>'."\n";
			}
	}
	
	/*
	$list=dbarr("SELECT * FROM `base_map_nexus` where id=".$map_id." ORDER BY `id`  DESC");
	foreach((array)$list as $key=>$value)
	{
			$monster=$value["monster"];
			//$monster_arr=arrayed($monster);
			$mo=explode(",",$monster);
			foreach((array)$mo as $kk=>$vv)
			{
				$monster_arr=explode(":",$vv);
				$aaa=explode("\r\n",$monster_arr[2]);
				if(count($aaa)>1)
				{
					$monster_arr[2]=$aaa[0];
				}
				unset($aaa);
				$mxy_arr=explode("@",str_replace(array("\n",'\n',"\r\n",'\r\n'),"",$monster_arr[2]));
				$mxy_arr1=explode("_",$mxy_arr[0]);
				$mxy_arr2=explode("_",$mxy_arr[1]);
				$xd=intval(abs($mxy_arr1[0]-$mxy_arr2[0])/5);
				$yd=intval(abs($mxy_arr1[1]-$mxy_arr2[1])/5);
				
				$dd=distance(intval($mxy_arr1[0]/$b).":".intval($mxy_arr1[1]/$b),intval($mxy_arr2[0]/$b).":".intval($mxy_arr2[1]/$b));
				$info="原始:".$monster_arr[2]."<br>序列:".$monster_arr[1]."<br>list名:".$base_monster_list[$monster_arr[0]]["monster_name"]."<br>直线距离:".$dd;
				if(count($mxy_arr)>=2)
				{
					echo '<div mid='.$monster_arr[0].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr1[0]/$b).'px;top:'.intval($mxy_arr1[1]/$b).'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'.$monster_arr[1].'"  onmousedown="canmoveto2(this,document.getElementById(\'id_'.$monster_arr[1].'_\'),'.$xd.','.$yd.',0, event)">'.$monster_arr[0].'</div>'."\n";
				
					echo '<div mid='.$monster_arr[0].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr2[0]/$b).'px;top:'.intval($mxy_arr2[1]/$b).'px;background-color:#90F; width:15px; height:15px; position:absolute;" id="id_'.$monster_arr[1].'_"  onmousedown="canmoveto2(this,document.getElementById(\'id_'.$monster_arr[1].'\'),'.$xd.','.$yd.',1, event)">'.$monster_arr[0].'</div>'."\n";
				}else{
					echo '<div mid='.$monster_arr[0].' onmouseover="tooltip.show(\''.$info.'\');" onmouseout="tooltip.hide();"  style="left:'.intval($mxy_arr1[0]/$b).'px;top:'.intval($mxy_arr1[1]/$b).'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'.$monster_arr[1].'"  onmousedown="canmove(this,event)">'.$monster_arr[0].'</div>'."\n";
				}
				
			}
	}
	*/
	
}
?>
<div id='movediv' style="display:none;"></div>
<script>
function op()
{
	small_movediv("mcc.php","mini_move_div",50,50,"movediv");
}


function vehicle() 
{ 
	var objt=document.getElementById("rongqi");

	var objdiv=objt.getElementsByTagName("div");
	var str="";
	var str_="";
	var htmls='';
	var divid=Array();
	var id=0;
	var arr=Array();
	var arr_=Array();
	
	for(i=0;i<objdiv.length;i++)
	{
		if(objdiv[i])
		{
			id="";
			divid=objdiv[i].id.split("_");
			id=divid[1];
			if(!id)
			{
				continue;
			}
			if(arr[id])
			{
				arr[id]+="@"+parseInt(objdiv[i].style.left)+"_"+parseInt(objdiv[i].style.top);
			}else{
				arr[id]=parseInt(objdiv[i].style.left)+"_"+parseInt(objdiv[i].style.top);
			}
			arr_[id]=objdiv[i].getAttribute("mid");
		}
	}
	
	for (str_ in arr)
	{
		str+=arr_[str_]+":"+str_+":"+arr[str_]+",";
	}
	
	ajax_ultimate("mccc.php?str="+str+"&map_id=<?php echo $_GET["map_id"];?>","st");
	
} 
function st(msg)
{
	alert(msg);
}


function getmaxid()
{
	var objt=document.getElementById("rongqi");

	var objdiv=objt.getElementsByTagName("div");
	var str="";
	var str_="";
	var htmls='';
	var divid=Array();
	var id=0;
	var arr=Array();
	var arr_=Array();
	var maxid=0;
	for(i=0;i<objdiv.length;i++)
	{
		if(objdiv[i])
		{
			id="";
			divid=objdiv[i].id.split("_");
			id=divid[1];
			if(!id)
			{
				continue;
			}
			arr[id]=id;
		}
	}
	
	for (str_ in arr)
	{
		if(parseInt(str_)>parseInt(maxid))
		{
			maxid=str_;
		}
	}
	
	return maxid;
}

function tijiao()
{
	var maxid=getmaxid()+1;
	var htmls1="";
	var htmls2="";
	var mlist=document.getElementById("mlist").value;
	var xytoxy=document.getElementById("xytoxy").value;
		
	var xytoto=xytoxy.split("@");
	var mlist_arr=mlist.split("@#");
	
	var info="原始:"+xytoxy+"<br>序列:"+maxid+"<br>list名:"+mlist_arr[1];
	if(mlist_arr[0]==5 && xytoto.length==2)
	{
		alert("食人花只需单坐标即可");
		return;
	}
	if(xytoto.length==2)
	{
		var xy1=xytoto[0].split(":");
		var xy2=xytoto[1].split(":");
			
		var x1=Math.abs(xy1[0]-xy2[0]);
		var y1=Math.abs(xy1[1]-xy2[1]);
		
		if(y1)
		{
			alert("Y轴不相等，目前不支持");
			return;
		}
		
		if(x1>300)
		{
			alert("x轴距离过远，怪物行走路段过长，请检查");
			return;
		}
		
		htmls1='<div mid='+mlist_arr[0]+' onmouseover="tooltip.show(\''+info+'\');" onmouseout="tooltip.hide();"  style="left:'+xy1[0]+'px;top:'+xy1[1]+'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'+maxid+'"  onmousedown="canmoveto2(this,document.getElementById(\'id_'+maxid+'_\'),'+x1+','+y1+',0, event)">'+mlist_arr[0]+'</div>'+"\n\n";
		
		htmls2+='<div mid='+mlist_arr[0]+' onmouseover="tooltip.show(\''+info+'\');" onmouseout="tooltip.hide();"  style="left:'+xy2[0]+'px;top:'+xy2[1]+'px;background-color:#90F; width:15px; height:15px; position:absolute;" id="id_'+maxid+'_"  onmousedown="canmoveto2(this,document.getElementById(\'id_'+maxid+'\'),'+x1+','+y1+',1, event)">'+mlist_arr[0]+'</div>';
		document.getElementById("rongqi").innerHTML+=htmls1;
		document.getElementById("rongqi").innerHTML+=htmls2;
	}else{
		var xy1=xytoto[0].split(":");
		htmls1='<div mid='+mlist_arr[0]+' onmouseover="tooltip.show(\''+info+'\');" onmouseout="tooltip.hide();"  style="left:'+xy1[0]+'px;top:'+xy1[1]+'px;background-color:#FFF; width:15px; height:15px; position:absolute;" id="id_'+maxid+'"  onmousedown="canmove(this, event)">'+mlist_arr[0]+'</div>'+"\n\n";
		document.getElementById("rongqi").innerHTML+=htmls1;
	}
//	alert(htmls);
	logoutdiv('movediv');
	
	
}

function zairu(map_id)
{
	if (confirm ("注意：如果您对怪物做了操作，请先保存，否则不生效！\n继续请点击确定！"))
	{
		ajax_ultimate("daorumem.php?map_id="+map_id,"zairu_");
	}
}

function zairu_(msg)
{
	alert(msg);
}
</script>
</body>
</html>
