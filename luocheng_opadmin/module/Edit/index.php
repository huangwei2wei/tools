<script>
function detection_thing(vName)
{
	
	try
		{
			var oForm=document.getElementById(vName);
			
			if(!oForm)
			{
				alert('名为 '+vName+' 的表单或控件不存在!'); 
				return false; 
			}
			
		}catch(err){}
}
</script>
<?php
/* /////////////////////////
 /    程序编写人员：肖立东,李奕
 /    时间：2012/8/24
 /        调用方法
 / 
 /   $height='500';   编辑器高度
 /   $width='600';    编辑器宽度
 /   $edit_name='article_body'; 文本隐藏表单 name名称
 /   $switch='briefness';      编辑器类型 briefness为简单型 空为全功能型
 /   edit($height,$width,$edit_name,$switch);
 /
 //////////////////////////*/
 
function edit($height,$width,$edit_name,$switch,$path="")
{
     print "<script>
				detection_thing(\"".$edit_name."\");
			</script>";
	if($path=="")$path=".";
	$ght=$height-115;
	if($switch=='briefness'){
		print "<iframe name='editt' id='editt' scrolling='no' hspace=0 vspace=0 src='".$path."/edit_br/index.php?id=$edit_name&height=$ght&width=$width' frameborder='0' height=$height width=$width></iframe>";
	}else{
		print "<iframe name='editt' id='editt' scrolling='no' hspace=0 vspace=0 src='".$path."/Edit/editor.html?id=$edit_name&height=$ght&width=$width' frameborder='0' height=$height width=$width></iframe>";
	}
}
?>