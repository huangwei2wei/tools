// JavaScript Document
function code_mode(){  //代码模式
	 document.getElementById('view').value= editor.document.body.innerHTML;
	
     document.getElementById('edit').style.display='none';
	 document.getElementById('view').style.display='';
	 
	 
	 document.getElementById('code_a').src='con_img/code1.gif';
	 document.getElementById('design_a').src='con_img/design.gif';
	 document.getElementById('texts_a').src='con_img/text.gif';
	 
	 document.getElementById('tools').style.visibility='hidden';
	 document.getElementById('tools1').style.visibility='hidden';
}

function design_mode(){  //设计模式
	 editor.document.body.innerHTML=document.getElementById('view').value;
	 
     document.getElementById('edit').style.display='';
	 document.getElementById('view').style.display='none';
	 
	 document.getElementById('code_a').src='con_img/code.gif';
	 document.getElementById('design_a').src='con_img/design1.gif';
	 document.getElementById('texts_a').src='con_img/text.gif';
	
	 document.getElementById('tools').style.visibility='visible';
	 document.getElementById('tools1').style.visibility='visible';
}

function texts(){  //文本模式

	 var datess=editor.document.body.innerHTML;
	 
	 dates=datess.replace (/<a.*?>/g,"");  //去除所有连接
	 dates=dates.replace (/<A.*?>/g,"");  //去除所有连接
	 dates=dates.replace (/<IMG.*?>/g,"");  //去除所有图片
	 dates=dates.replace (/<img.*?>/g,"");  //去除所有图片
	 
	 //dates=dates.replace (/<TABLE.*?>/g,"");  //去除所有表格
	 //dates=dates.replace (/<table.*?>/g,"");  //去除所有表格
     dates=dates.replace (/<SCRIPT.*?>/g,"");  //去除所有JS
	 dates=dates.replace (/<script.*?>/g,"");  //去除所有JS
	 
	 document.getElementById('view').value=dates;
	 
     document.getElementById('edit').style.display='';
	 document.getElementById('view').style.display='none';
	 
	 
	 document.getElementById('code_a').src='con_img/code.gif';
	 document.getElementById('design_a').src='con_img/design.gif';
	 document.getElementById('texts_a').src='con_img/text1.gif';
	 
	 document.getElementById('tools').style.visibility='hidden';
	 document.getElementById('tools1').style.visibility='hidden';
}

function SEditor_B(editor_a,format){  //字体（粗体 下线 斜体）
	//document.getElementById('edit').focus();
	editor.document.execCommand(format,'',null);
} 

function SEditor_FColor(editor_a){   //颜色
	var colorPage = "./edit_color.php";
	var color = showModalDialog(colorPage,window,'dialogWidth:210px;dialogHeight:170px;status:0;scroll:0;help:0;'); 
	editor.document.execCommand('forecolor','',color);
 } 
 
function SEditor_InsertPic(editor_a){  //图片
	var facePage = "./up_img.php";
	var url = showModalDialog(facePage,window,'dialogWidth:300px;dialogHeight:190px;status:0;scroll:0;help:0;'); 
	if (url == "http://" || !url)
	return;
	
	var face = "<img src='" + url + "' />";
	
	editor.document.body.focus();
	var range = editor.document.body.document.selection.createRange();
	range.pasteHTML(face); 
	editor.document.body.focus();
}

function SEditor_InsertLink(editor_a){  //超连接
 //document.getElementById('edit').focus();
 editor.document.execCommand('createlink','false','true');
} 

function delete_InsertLink(editor_a){  //删除超连接
 //document.getElementById('edit').focus();
 editor.document.execCommand('Unlink','false','true');
}
function all_delete_InsertLink(editor_a){  //全部删除超连接
    var datess=editor.document.innerHTML;
    editor.document.innerHTML=datess.replace (/<a.*?>/g,"");
	editor.document.innerHTML=datess.replace (/<A.*?>/g,"");
}

function left_a(editor_a){  //左
   editor.document.execCommand('JustifyLeft','','');
} 

function center_a(editor_a){  //中
    editor.document.execCommand('JustifyCenter','','');
} 

function right_a(editor_a){  //右
    editor.document.execCommand('JustifyRight','','');
} 

function SEditor_InsertFlash(editor_a){
	var url = prompt("请输入Flash的地址.", "http://");
	if (url == "http://" || url == null || url == "" || url == "undefined")
	return;
	var flash = "<OBJECT codeBase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,2,0 classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 width=200 height=200><PARAM NAME=movie VALUE="+url+"><PARAM NAME=quality VALUE=high><PARAM NAME=menu VALUE=false><embed src="+url+" quality=high pluginspage="+"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" +" width=200 height=200>"+url+"</embed></OBJECT>";;
	 document.getElementById('edit').focus(); 
	 var range = document.getElementById('edit').document.selection.createRange();
	range.pasteHTML(flash);
	document.getElementById('edit').focus(); 
}

function font (bj,attribute1,attribute2){
  //document.execCommand('FontSize',false,7);  //true或false都可以
  // document.execCommand('FontName',false,'标楷体'); //true或false都可以 //字体必须是系统支持的字体
	  if(bj==1){
		  editor.document.execCommand('FontSize',false,attribute2.value);
	  }
	  if(bj==2){
		  editor.document.execCommand('FontName',false,attribute2.value); 
	  }
 }

function init(switchs)
{
   if(switchs=='edit')
   {
   		if(!formid)
		{
			alert('请传入调用参数ID，即隐藏的内容表单项ID');
		}else{
			var obj=parent.document.getElementById(formid);
			editor.document.body.innerHTML=obj.value;
		}
    }
	
	if(switchs=='add')
	{
		if(!formid)
		{
			alert('请传入调用参数ID，即隐藏的内容表单项ID');
		}else{
			var obj=parent.document.getElementById(formid);
			obj.value=editor.document.body.innerHTML;
		}
	}
}