<head>
<base target="_self">

<style type="text/css">
<!--
td {
	font-size: 12px;
	color: #666666;
	text-decoration: none;
}
-->
</style>
<script>
function f(obj){
	if(/jpg|jpeg|gif|bmp|swf/i.test(obj.value.match(/\.(\w+)$/)[0])){
		var im = new Image();
		im.src = obj.value;
		function chksize(){
			if (im.fileSize>1024*100){
				alert("文件超过100K，请重新选择");
				obj.form.reset();
			}else{
				if (im.fileSize==-1){
					window.setTimeout(chksize,20);
				}
			}
		}
		chksize();
	}else{
		alert("格式错误");
		obj.form.reset();
	}
}

function aa(ac){
      if(ac=='a1'){
          document.getElementById('a2').style.display='none';
		  document.getElementById('a1').style.display='';
	   }
	   
	   if(ac=='a2'){
          document.getElementById('a1').style.display='none';
		  document.getElementById('a2').style.display='';
	   }
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="aa('a1');">
<form action="up_imgc.php" method="post" enctype="multipart/form-data" name="form1" style="margin:0">

<table width="320" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="21" colspan="2" align="center">
	<div><input name="radiobutton" type="radio" onClick="aa('a1')" value="0" checked="checked" />
	上传 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input onClick="aa('a2')" type="radio" name="radiobutton" value="1" />路径</div></td>
  </tr>
  <tr>
    <td height="21" colspan="2">
      <div id=a1>&nbsp; 文件：
        <input name="upfile" type="file" id="upfile">
      </div>
      <div id=a2>
        &nbsp; 路径：
          <input type="text" name="textfield3" />
      </div></td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="100%" border="0" cellpadding="0" cellspacing="3">
        <tr>
          <td width="100%"><div align="center">
            <label>
            <input type="submit" name="Submit" value="  上传  ">
            </label>
            &nbsp;&nbsp; 
            <input name="重置" type=reset onClick="window.close();" value='  取消  '></div></td>
          </tr>
      </table>      </td>
  </tr>
</table>
</form> 
</body>