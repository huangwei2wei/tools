/*******************************************
  一款垃圾中的极品FreeEditor(2006-08-22)
  This JavaScript was writen by CXP.
*******************************************/
var colorType;
var currentMode="Design";	//当前模式
var ie=document.all?true:false;
document.onclick=function(){	
}
function format(str,arg){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var obj=window.frames["design"];
	if(!ie){
		var sAlert="";
		switch(str){
			case "Cut":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行剪切操作,请使用键盘快捷键(Ctrl+X)来完成";
				break;
			case "Copy":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行拷贝操作,请使用键盘快捷键(Ctrl+C)来完成";
				break;
			case "Paste":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行粘贴操作,请使用键盘快捷键(Ctrl+V)来完成";
				break;
		}
		if(sAlert!=""){
			alert(sAlert);
			return;
		}
	}
	obj.focus();
	if(arg){
		return obj.document.execCommand(str,false,arg);
	}
	else{
		if(ie){
			obj.document.execCommand(str);
		}
		else{
			obj.document.execCommand(str,false,false);
		}
	}
	obj.focus();
}

//显示颜色菜单
function showColorMenu(obj,e){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var target=document.all?e.srcElement:e.target;
	var colorMode=target.getAttribute("title");
	switch(colorMode){
		case "背景颜色":
			colorType="BodyBackColor";
			break;
		case "字体背景颜色":
			colorType="backColor";
			break;
		case "字体颜色":
			colorType="foreColor";
			break;		
		default:
			return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		var db=window.frames["design"].document;
		var height=125;
		if(colorType=="foreColor"){
			height=145;
		}
		var color=showModalDialog("dialog/color.html",window,"dialogWidth:263px;dialogHeight:"+height+"px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";px");
		if(color!=null){			
			if(colorType=="BodyBackColor"){
				db.body.style.backgroundColor=color;
				return;
			}
			else if(colorType=="foreColor"){
					var arr=color.split("|");
					if(arr.length==2){
						format(colorType,arr[0]);
						//设置发光滤镜,IEOnly
						if(db.selection.type.toLowerCase()!="none"){
							var selectText=db.selection.createRange().htmlText;
							selectText="<span style=\"height:18px;filter:Glow(color="+arr[0]+",Strength=2);\">"+selectText+"</span>";
							insertHTML(selectText);
							return;
						}
					}
				}
			format(colorType,color);			
		}
	}
	else{
		if(colorType=="backColor"){
			alert('待完善!');
			return;
		}	
		FFOpenWidnow("dialog/color.html",263,112,top+25,left);
	}
}

//显示插入图片窗口
function showImageMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		var path=showModalDialog("dialog/image.html","","dialogWidth:390px;dialogHeight:250px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";px");
		if(path==null){
			return;
		}
		format("insertImage",path);
	}
	else{
		FFOpenWidnow("dialog/image.html",390,250,top+25,left);
	}
}

//显示插入FLASH窗口
function showFlashMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj);
	if(document.all){
		var path=showModalDialog("dialog/FLASH.html","","dialogWidth:402px;dialogHeight:139px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+(top+138)+";px");
		if(path==null){
			return;
		}
		var arr=path.split("|");
		var str="<OBJECT codeBase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='"+arr[1]+"' height='"+arr[2]+"'><PARAM NAME=movie VALUE='"+arr[0]+"'><PARAM NAME=quality VALUE=high><embed src='"+arr[0]+"' quality=high pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='"+arr[1]+"' height='"+arr[2]+"'></embed></OBJECT>";
		insertHTML(str);
	}
	else{
		alert('待完善');
		return;
	}
}

//显示插入Face窗口
function showFaceMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/face.html",window,"dialogWidth:280px;dialogHeight:215px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";px");
	}
	else{
		FFOpenWidnow("dialog/face.html",280,180,top+25,left);
	}
}

//显示fieldSet窗口
function showFieldSetMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/fieldSet.html",window,"dialogWidth:330px;dialogHeight:120px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";px");
	}
	else{
		FFOpenWidnow("dialog/fieldSet.html",330,100,top+25,left);
	}
}

function getWidth(obj){
	var ParentObj=obj;
	var left=obj.offsetLeft;
	while(ParentObj=ParentObj.offsetParent){
		left+=ParentObj.offsetLeft;
	}
	return left;
}

function getHeight(obj){
	var ParentObj=obj;
	var top=obj.offsetTop;
	while(ParentObj=ParentObj.offsetParent){
		top+=ParentObj.offsetTop;
	}
	return top;
}

function showMediaMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj);
	if(document.all){
		showModalDialog("dialog/media.html",window,"dialogWidth:330px;dialogHeight:119px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+(top+138)+";");
	}
	else{
		alert('待完善');
	}
}

//显示插入文件窗口
function showFileMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/file.html",window,"dialogWidth:390px;dialogHeight:115px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/file.html",380,100,top+25,left);
	}
}

function setColor(color){	
	if(color==""){
		return;
	}
	if(colorType=="BodyBackColor"){
		window.frames["design"].document.body.style.backgroundColor=color;
		return;
	}
	format(colorType,color);
}

//插入字幕
function showMarqueeMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/marquee.html",window,"dialogWidth:439px;dialogHeight:260px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/marquee.html",435,220,top+25,left);
	}
}

//插入特殊字符
function showSymbolMenu(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/symbol.html",window,"dialogWidth:385px;dialogHeight:235px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/symbol.html",370,190,top+25,left);
	}
}

//插入文件
function insertFile(txt){
	var arr=txt.split(".");
	var fileName=getFileName(arr[arr.length-1]);
	var str="<img src=\"img\/file\/"+fileName+"\" border=0><a href=\""+txt+"\" target=\"_blank\">";
	arr=txt.split("/");
	fileName=arr[arr.length-1];
	str+=fileName+"</a>";
	var db=window.frames["design"].document;
	insertHTML(str);
}

//插入当前时间
function insertTime(b){
	var d=new Date();
	var year,month,day,hour,minute,second;
	year=d.getFullYear();
	month=d.getMonth();
	day=d.getDate();
	hour=d.getHours();
	minute=d.getMinutes();
	second=d.getSeconds();
	var str;
	if(b){
		str=year+"-"+month+"-"+day;
	}
	else{
		str=hour+":"+minute+":"+second;
	}
	insertHTML(str);
}
//插入分页符
function pagination()
{
	var str="<DIV style=\"BORDER-TOP: red 1px dotted; FONT-SIZE: 1px; BACKGROUND: url(img/fck_pagebreak.gif) no-repeat center 50%; WIDTH: 100%; LINE-HEIGHT: 5px; BORDER-BOTTOM: red 1px dotted; HEIGHT: 5px;\"></DIV>";
	insertHTML(str);
}
//插入引用
function insertQuote(){
	var str="<TABLE style=\"BORDER-RIGHT: #cccccc 1px dotted; TABLE-LAYOUT: fixed; BORDER-TOP: #cccccc 1px dotted; BORDER-LEFT: #cccccc 1px dotted; BORDER-BOTTOM: #cccccc 1px dotted\" cellSpacing=0 cellPadding=6 width=\"95%\" align=center border=0><TBODY><TR><TD style=\"WORD-WRAP: break-word\" bgColor=\"#f3f3f3\" valign=\"top\"><FONT style=\"FONT-WEIGHT: bold; COLOR: green;font-size:13px;\">引用：</FONT><BR></TD></TR></TBODY></TABLE>";
	insertHTML(str);
}

//插入单选框
function insertRadio(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/radio.html",window,"dialogWidth:275px;dialogHeight:156px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/radio.html",270,125,top+25,left);
	}
}

//插入复选框
function insertCheckBox(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/checkbox.html",window,"dialogWidth:275px;dialogHeight:156px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/checkbox.html",270,125,top+25,left);
	}
}

//插入复选框
function insertInputText(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/text.html",window,"dialogWidth:275px;dialogHeight:156px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/text.html",270,125,top+25,left);
	}
}

//插入多行文本框
function insertTextArea(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/textarea.html",window,"dialogWidth:305px;dialogHeight:250px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/textarea.html",300,250,top+25,left,true);
	}
}

//插入表格
function insertTable(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/table.html",window,"dialogWidth:265px;dialogHeight:239px;help:no;status:no;scroll:no;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/table.html",256,210,top+25,left,true);
	}
}

//插入下拉列表
function insertSelect(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/select.html",window,"dialogWidth:380px;dialogHeight:250px;help:no;status:no;scroll:auto;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/select.html",380,250,top+25,left);
	}
}

//插入按钮
function insertButton(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/button.html",window,"dialogWidth:239px;dialogHeight:170px;help:no;status:no;scroll:auto;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/button.html",236,139,top+25,left);
	}
}

//插入表单
function insertForm(obj){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	var left,top;
	left=getWidth(obj);
	top=getHeight(obj)+138;
	if(document.all){
		showModalDialog("dialog/form.html",window,"dialogWidth:239px;dialogHeight:170px;help:no;status:no;scroll:auto;dialogLeft:"+left+";dialogTop:"+top+";");
	}
	else{
		FFOpenWidnow("dialog/form.html",236,139,top+25,left);
	}
}

//设置模式
function setMode(modeType,btnObj){
	if(currentMode==modeType){
		return;
	}
	btnObj.style.border="1px solid #000000";
	var cObj;
	if(currentMode=="Preview"){
		cObj=document.getElementById("preview");
	}
	else if(currentMode=="Design"){
		cObj=document.getElementById("design");
	}
	else{
		cObj=document.getElementById("textCode");
	}
	document.getElementById("bnt"+currentMode).style.border="1px solid #999999";
	switch(modeType){
		case "Code":	//代码	
			var obj=document.getElementById("textCode");
			var db=window.frames["design"].document.body;
			var HTMLCode=db.innerHTML;
			//防止FF在没有任何代码前产生的换行
			if(HTMLCode=="<br>\n" || HTMLCode=="<br>"){
				HTMLCode="";
			}
			obj.value=HTMLCode;
			cObj.style.display="none";
			obj.style.display="block";
			break;
		case "Design":	//设计	
			cObj.style.display="none";
			document.getElementById("design").style.display="block";
			break;
		case "Preview":
			var obj=window.frames["preview"].document.body;
			var db=window.frames["design"].document.body;
			obj.style.backgroundColor=db.style.backgroundColor;
			if(currentMode=="Code"){
				obj.innerHTML=cObj.value;
			}
			else{
				var str=db.innerHTML;
				obj.innerHTML=str;
			}
			cObj.style.display="none";
			document.getElementById("preview").style.display="block";
			break;
	}
	currentMode=modeType;	//设置当前模式
}

//创建链接
function createLink(){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	if(document.all){
		format("CreateLink");
	}
	else{
		var str=window.prompt("Enter Link Location (Example:http://www.baidu.com/ or ftp://127.0.0.1/)","http://");
		if(str==null || str=="http://" || str=="")	return;
		format("CreateLink", str);
	}
}

//新建
function newPage(){
	if(currentMode!="Design"){	//非设计模式
		return;
	}
	window.frames["design"].document.body.innerHTML="";
}

//设置字体
function setFaceFamily(obj){
	var val=obj.options[obj.selectedIndex].value;
	if(val=="")	return;
	format("fontname",val);
}

//设置字号
function setFaceSize(obj){
	var val=obj.options[obj.selectedIndex].text;
	if(val=="字号")	return;
	format("fontsize",val);
}
function getFileName(str){
	str=str.toLowerCase();
	var name;
	switch(str){
		case "avi":
			name="avi.gif";
			break;
		case "bmp":
			name="bmp.gif";
			break;
		case "chm":
			name="chm.gif";
			break;
		case "doc":
			name="doc.gif";
			break;
		case "exe":
			name="exe.gif";
			break;
		case "gif":
			name="gif.gif";
			break;
		case "html":
			name="html.gif";
			break;
		case "htm":
			name="html.gif";
			break;
		case "jpg":
			name="jpg.gif";
			break;
		case "mdb":
			name="mdb.gif";
			break;
		case "mid":
			name="mid.gif";
			break;
		case "mp3":
			name="mp3.gif";
			break;
		case "pdf":
			name="pdf.gif";
			break;
		case "ppt":
			name="ppt.gif";
			break;
		case "rar":
			name="rar.gif";
			break;
		case "rm":
			name="rm.gif";
			break;
		case "rmvb":
			name="rm.gif";
			break;
		case "swf":
			name="swf.gif";
			break;
		case "txt":
			name="txt.gif";
			break;
		case "xls":
			name="xls.gif";
			break;
		case "zip":
			name="zip.gif";
			break;
		default:
			name="unknow.gif";
			break;
	}
	return name;
}

function setFocus(){
	window.frames["design"].focus();
}

//插入HTML代码
function insertHTML(str){
	if(ie){
		setFocus();
		window.frames["design"].document.selection.createRange().pasteHTML(str);		
	}
	else{
		var newStr=window.frames["design"].document.body.innerHTML;
		if(newStr.toLowerCase()=="<br>" || newStr.toLowerCase()=="<br>\n"){
			window.frames["design"].document.body.innerHTML=str;
		}
		else{
			window.frames["design"].document.body.innerHTML+=str;
		}
	}
	setFocus();
}

//FF打开窗口
function FFOpenWidnow(url,w,h,top,left){	
	window.open(url,"","location=no,menubar=no,toolbar=no,dependent=yes,dialog=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=no,width="+w+",height="+h+",screenX="+left+",screenY="+top);
		
}