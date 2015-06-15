<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script>
var formid='<?php echo $_GET['id'];?>';
</script>
<script src="edit.js"></script>
<title>简单的编辑器 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body{font-size:12px;}
#edit,#view{
	width:100%;
	overflow-y:auto;
}

#view{
	width:99%;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #FFFFFF;
	border-right-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-left-style: solid;
	border-right-color: #FFFFFF;
	border-left-color: #FFFFFF;
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #FFFFFF;
	margin:0px;
	padding:0px;
}

.kb_kc{
	border-right-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-left-style: solid;
	border-right-color: #a4c3f1;
	border-left-color: #a4c3f1;
}

.dhlx{
	height:24; padding-top:8px; padding-left:8px;
}

.dhlx_{
	height:24; padding-top:8px; padding-left:8px; float:left;
}
</style>
</head>

<body onLoad="design_mode();init('edit')" style="margin:0px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11" height="34" background="con_img/left.jpg">&nbsp;</td>
    <td background="con_img/bg.jpg">
    <?php
    if($_GET['width']>=550)
	{
		$cs="dhlx_";
	}else{
		$cs="dhlx";
	}
	?>
<div class="<?php echo $cs?>" id=tools1>
<input id=f2 title="左对齐"  onClick="left_a('edit')"  type="image" name="imageField" src="con_img/left.gif">
<input id=f3 title="居中"  onClick="center_a('edit')" type="image" name="imageField" src="con_img/center.gif">
<input id=f4 title="右对齐"  onClick="right_a('edit')" type="image" name="imageField" src="con_img/right.gif">
<input id=f5 title="粗体" onClick="SEditor_B('edit','bold')" type="image" name="imageField" src="con_img/B.gif"> 
	<input id=f6 title="下划线" onClick="SEditor_B('edit','underline')" type="image" name="imageField4" src="con_img/U.gif">
	<input id=f7 title="斜体" onClick="SEditor_B('edit','italic')" type="image" name="imageField3" src="con_img/I.gif">
    <input id=f8 title="字体颜色" onClick="SEditor_FColor('edit')" type="image" name="imageField2" src="con_img/Color.gif">
    <input id=f9 title="插入Flash（动画）" onClick="SEditor_InsertFlash('edit')"  type="image" name="imageField5" src="con_img/flash.gif">
	<input id=f10 title="插入图片" onClick="SEditor_InsertPic('edit')" type="image" name="imageField6" src="con_img/img.gif">
	<input id=f11 title="插入超连接" onClick="SEditor_InsertLink('edit')" width="22" height="20" type="image" name="imageField7" src="con_img/Link.gif">
	<input id=f12 title="删除被选中的超连接" onClick="delete_InsertLink('edit')" width="22" height="20" type="image" name="imageField7" src="con_img/Link2.gif">
	<input id=f13 title="全部删除超连接（无须选中）" onClick="all_delete_InsertLink('edit')" width="22" height="20" type="image" name="imageField7" src="con_img/Link3.gif">
</div>

<div class="<?php echo $cs?>" id=tools>
  <select id=f0 name="select" onChange="font ('2','edit',this)">
  <option value="宋体">默认</option>
  <option value="宋体">宋体</option>
  <option value="新宋体">新宋体</option>
  <option value="仿宋_GB2312">仿宋_GB2312</option>
  <option value="宋体-方正超大字符集">宋体-方正超大</option>
  <option value="黑体">黑体</option>
  <option value="幼圆">幼圆</option>
  <option value="楷体_GB2312">楷体_GB2312</option>
  <option value="隶书">隶书</option>
  <option value="华文中宋">华文中宋</option>
  <option value="华文行楷">华文行楷</option>
  <option value="华文新魏">华文新魏</option>
  <option value="华文细黑">华文细黑</option>
  <option value="华文仿宋">华文仿宋</option>
  <option value="华文彩云">华文彩云</option>
  <option value="方正姚体">方正姚体</option>
  <option value="方正舒体">方正舒体</option>
  </select>
<select id=f1 name="select2" onChange="font ('1','edit',this)">
  <option value="2">默认</option>
  <option value="1">一号</option>
  <option value="2">二号</option>
  <option value="3">三号</option>
  <option value="4">四号</option>
  <option value="5">五号</option>
  <option value="6">六号</option>
  <option value="7号">七号</option>
  <option value="8">八号</option>
  <option value="9">九号</option>
  <option value="10">十号</option>
</select>
</div>
	</td>
    <td width="11" height="34" background="con_img/right.jpg">&nbsp;</td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kb_kc">
  <tr>
    <td>
	<div onmouseenter="init('add')" onMouseOut="init('add')">
        <IFRAME id="edit" marginwidth="0" framespacing="0" marginheight="0" frameborder="0"  style="HEIGHT: <?php echo $_GET['height']?>px;" ></IFRAME>
        <textarea style="height:<?php echo ($_GET['height']-2)?>px;display:none"  id='view'></textarea>
    </div>
  </td>
  </tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11" height="11" background="con_img/left-d.jpg"></td>
    <td width="*" background="con_img/d.jpg">&nbsp;</td>
    <td width="11" height="11" background="con_img/right-d.jpg"></td>
  </tr>
</table>
<div style="padding-left:25px;">
<img id="code_a" src="con_img/code.gif"  style="cursor:pointer;" onClick="code_mode()" alt="代码模式">

<img src="con_img/design1.gif" alt="设计模式" name="design_a" width="90" height="27" id="design_a"  style="cursor:pointer;" onClick="design_mode()">

<img src="con_img/text.gif" alt="文本模式" width="90" height="27" id="texts_a"  style="cursor:pointer;" onClick="texts()">
&nbsp; </div>
<script>
	var editor = document.getElementById("edit").contentWindow;
	
	//只需键入以下设定，iframe立刻变成编辑器。
	editor.document.designMode = 'on';
	editor.document.contentEditable = true;
	
	//但是IE与FireFox有点不同，为了兼容FireFox，所以必须创建一个新的document。
	editor.document.open();
	editor.document.writeln('<html><body></body></html>');
	editor.document.close();
</script>
</body>
</html>