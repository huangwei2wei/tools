<!--
/**
 * +--------------------------------------------------
 * |   函数名：opens(theURL,winName,size)
 * |   作用：打开固定大小的窗口
 * |   参数：
 * |   theURL    要打开的地址   
 * |   winName   窗口名称
 * |   size      窗口尺寸 当为fullscreen 时为全屏
 * |   返回值：无
 * +--------------------------------------------------
 */

function opens(theURL,winName,size)
{
  window.open(theURL,winName,size);
}

/*过渡加载*/
function loading(id,bigs)
{
	if(bigs=='big')
	{
		var loa='<table class="loading" width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><img  style="top:45%" src="/admin/images/loading_b.gif" /></td></tr></table>';
	}else{
		var loa='<table class="loading" width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><img  style="top:45%" src="/admin/images/loading.gif" /></td></tr></table>';
	}

	if(typeof(id) == 'object')
	{
		id.innerHTML=loa;
	}else{
		document.getElementById(id).innerHTML=loa;
	}
}

/*浏览器判断*/
function getOs()
{
     var OsObject = "";
    if(navigator.userAgent.indexOf("MSIE")>0) {
         return "MSIE";
    }
    if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
         return "Firefox";
    }
    if(isSafari=navigator.userAgent.indexOf("Safari")>0) {
         return "Safari";
    } 
    if(isCamino=navigator.userAgent.indexOf("Camino")>0){
         return "Camino";
    }
    if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){
         return "Gecko";
    }
  
} 

function ajaxsends(url,fun,method,fromid,id,vars)
{
	var this_=this;
	this_.url=url;
	this_.fun=fun;
	this_.method=method;
	this_.fromid=fromid;
	this_.id=id;
	this_.vars=vars;
	
	/*AJAX执行状态*/
	ajaxsends.prototype.ajax_yun=function()
	{
		//获取执行状态
		this_.ajax.onreadystatechange = function() 
		{
			//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
			if (this_.ajax.readyState == 4 && this_.ajax.status == 200)
			{
				var	strdiv = this_.ajax.responseText;  //读取PHP页面打印出来的文字
				
				var jsstr=strdiv;
				
				strdiv=strdiv.replace(/<script[\s\S]+?<\/script>/igm,"");
				if(vars)
				{
					eval(this_.vars +"=strdiv");
				}else{
					if(this_.fun)
					{
						eval(this_.fun + "(strdiv,id)");
					}else if(this_.id){
						 document.getElementById(this_.id).innerHTML=strdiv;
					}
				}
				this_.ajaxjs(jsstr);
				
				/*释放变量及缓存*/
				this_.url=this_.fun=this_.method=this_.fromid=this_.id=strdiv=jsstr=this_.ajax=this_.vars=null;
				delete this_.ajax ; 
				this_=null;
				if(getOs()=='MSIE'){CollectGarbage;}
				
		//////////////////////////////////上面是处理//////////////////////////////////////////////////
			}
		}
	}
	
	
	/* 实例化AJAX*/
	ajaxsends.prototype.user_InitAjaxw=function()
	{
		 if (window.ActiveXObject)
		 {
			//IE
			try {
				//IE6.0以上
				return new ActiveXObject("Microsoft.XMLHTTP");
			}catch (e1) {
					//IE5.5以下
					return new ActiveXObject("Msxml2.XMLHTTP");
			}
		} else if (window.XMLHttpRequest) {
			//FireFox
			return new XMLHttpRequest();
		}

	}
	
	/*获取表单URL*/
	ajaxsends.prototype.geturl=function()
	{	
		var u=new Array();
		
		try{
			var url=document.getElementById(this_.fromid).action; 
		}catch(err){
			var  url=document.getElementsByName(this_.fromid)[0].action; 
		}
		
		if(!url)
		{
			alert('表单action属性为空（要提交的地址）！');
			return false;
		}
		
		u[0]=url;
		u[1]=fromid;
		
		return u;
	}
	
	/*执行JS */
	ajaxsends.prototype.ajaxjs=function(msg)
	{
		var str2=msg.split("\r\n").join('');
		str2=str2.split("\n").join('');
		str2=str2.split("\r").join('');
		
		var reg=/<script[^>]*?>(.*?)<\/script>/ig;
		var str=str2.match(reg);
		
		if(!str)
		{
			return false;
		}
		
		for(var i=0;i<str.length;i++)
		{
			str[i]=str[i].replace(reg,"$1");
			try {
				eval(str[i]);
			}catch(e){}

		}
	}
	
	
	/*POST提交*/
	ajaxsends.prototype.post=function()
    {
		if(!this_.url)
		{
			this_.url=this_.geturl();
		}else{
			if ( this_.url.constructor != window.Array)
			{
				var url_=this_.url;
				this_.url = new Array () ; 
				this_.url[0]=url_;
				this_.url[1]=this_.fromid;
			}
		}
		
		
		if (!typeof(this_.url[0])){return;}

		if(!this_.url[0]){return;}
       
		this_.ajax_yun();
		
        this_.ajax.open("POST", this_.url[0], true);
		
        this_.ajax.setRequestHeader("If-Modified-Since","0");
        this_.ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//发送空
		
		try
		{
			var oForm=document.getElementById(this_.url[1]);

		}catch(err){
		
		   var oForm=document.getElementsByName(this_.url[1])[0];
		}
		
		var sBody=getRequestBody(oForm);
		
		this_.ajax.send(sBody);
	}

	

	/*GET提交*/
	ajaxsends.prototype.get=function()
	{
		if (!typeof(this_.url)){return;}
		
		if(!this_.url){return;}
		
		this_.ajax.open("GET",this_.url,true);
		this_.ajax_yun();
		this_.ajax.setRequestHeader("Content-Type","text/html; charset=UTF-8");
		this_.ajax.setRequestHeader("If-Modified-Since","0");
		this_.ajax.send(null);
	}
	//////////////////////////////////////////
	this_.ajax = this_.user_InitAjaxw();
	
	if(!this_.method || this_.method=='get'  || this_.method=='GET')
	{
		
		this_.get();//GET方式
	}else{
		this_.post();//POST方式	
	}
	
	if(getOs()=='MSIE'){CollectGarbage();}//释放缓存
}

function getRequestBody(oForm)
{
	var aParams=new Array();
	for(var i=0;i<oForm.elements.length;i++)
	{
		if(oForm.elements[i].type=="radio" || oForm.elements[i].type=="checkbox")
		{
			if(oForm.elements[i].checked==true)
			{
				var sParam=encodeURIComponent(oForm.elements[i].name);
				sParam+="=";
				sParam+=encodeURIComponent(oForm.elements[i].value);
				aParams.push(sParam);
			}
		}else{
			var sParam=encodeURIComponent(oForm.elements[i].name);
			sParam+="=";
			sParam+=encodeURIComponent(oForm.elements[i].value);
			aParams.push(sParam);
		}
	}
	return aParams.join("&");
}

/* //////////////////////////////////////////////////////////////////////////////////////////////////
  *   AJAX主函数
  *   参数说明：
  *   url 提交页面  可选参数 geturl(id) 自定义 为空 ，3中状态 必选 其它不为必选
  *   fun 调用函数 默认调用client  可选参数 不调用 自定义
  *   method提交方式 get post 默认 GET 
  *   fromid 提交表单的ID或名称
  *   id     可以带一个返回参数
  *   vars 将数据返回给外部变量（注意：在使用它之间必须先定义外部变量比如：var gamehtml='';ajax_ultimate('index_.php','','','','','gamehtml');）这样才是正却的，否则报错 他的等级最高，其次外调函数，再次是ID返回值
  
  *   当method为GET时 只需调用 url 和fum两个即可
  *   当method为POST时 如果URL启用的是 geturl()函数时只需调用 url,fun,method即可 
  *   如果URL为用户定义路径时 需要把url,fun,method,id这4个参数掉齐全
  *   如果URL为空时则属要调用 url,fun,method,id 4个参数
  *   geturl(id)；AJAX附加调用函数
  *   作用：为AJAX取得FORM表单的路径 参数ID为 FORM表单ID或名称
  *   POST用法有3种 
  *   1、URL 用户自定义地址 Fun 可为空 ,method 为POST 输入FORM表单ID  ajax_ultimate(url,fun,method,id)
  *   2、URL 为空 Fun 可为空 ,method 为POST  ,id 提交表单的ID或名称 ajax_ultimate('',fun,method,id)
  *   3、URL 调入url() 函数  Fun 可为空 ,method 为POST ajax_ultimate(geturl(ID),fun,method,id)
*/////////////////////////////////////////////////////////////////////////////////////////////////

function ajax_ultimate(url,fun,method,fromid,id,vars)
{
	new ajaxsends(url,fun,method,fromid,id,vars);
	return ;
}


function setCookie(name,value,timet) //Cookie名  Cookie内容  Cookie存储时间
{ //设置Cookie
    	var exp  = new Date();    //new Date("December 31, 9998");
        exp.setTime(exp.getTime()+Number(timet));
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getCookie(name)
{//读取Cookie
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)) return unescape(arr[2]);
        else return null;
}
function delCookie(name)
{//删除Cookie
    var exp = new Date();
        exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
        if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


/*判断表单是否存在*/
function detection_thing(labelID)
{
	try
		{
			var oForm=document.getElementById(labelID);
			
			return true;	
			
		}catch(err){return false; }
}

/*随屏幕滚动层*/
function scrolldiv(id,h)
{
	var MyMar='';
	
	var obj=document.getElementById(id);
	
	if(h==null)
	{
		h='50px';
	}
	
	if(obj.style.display!='none')
	{
		MyMar=setInterval('scrolldiv_("'+id+'",'+h+')',100);   ///控制层的移动
	}else{
		clearInterval(MyMar);
	}
}

function  scrolldiv_(id,h)
{  //定位层
   obj=document.getElementById(id);

   obj.style.top =  ((document.documentElement) ? document.documentElement : document.body).scrollTop+h;
	//obj.style.top = document.body.scrollTop+h;
} 


function newgdcode(obj,url) 
{
	obj.src = url;
}

function on_off(value)
{
     var obj=document.getElementById(value);
	 
	 obj.style.display=(obj.style.display=='none')?'':'none';
}

/**************************BEGIN图层渐变显示*************************************/
/*
参数说明
div_id要控制渐变的ID
*/
function shade_show(div_id)
{
	var this_=this;
	this_.fade_opacity = 0;
	this_.ele_div = null;
	this_.fade_times= null;
	
	shade_show.prototype.change_show=function()
	{
	   var obj =this_.ele_div;
	   this_.fade_opacity = this_.fade_opacity + 5; //逐渐显示速度
	   obj.style.filter = "Alpha(Opacity=" + this_.fade_opacity + ")"; //透明度逐渐变小
	   obj.style.opacity = this_.fade_opacity/100; //兼容FireFox
	   if(this_.fade_opacity>=100){
		clearInterval(this_.fade_times);
		this_.fade_opacity=0;
	   }
	}
	
	
	shade_show.prototype.show_fade=function()
	{
	   var obj = document.getElementById(div_id);
	   if (!obj)
	   {
		   return ;
	   }
	   this_.ele_div = obj;
	   if(this_.fade_times){clearInterval(this_.fade_times);}
	   obj.style.filter = "Alpha(Opacity=0)"; //透明度逐渐变小
	   obj.style.opacity = this_.fade_opacity/100; //兼容FireFox
	   obj.style.display="block";
	   this_.fade_times = setInterval(this_.change_show,1);
	}
}
/**************************END图层渐变显示**************************************/	

/*
函数说明：
url   移动层内的内容地址
title  标题
css   移动层的样式（不用包含TOP和LEFT）
cajax  是采用AJAX还是iframe方式调用内容 1为不采用AJAX 0为采用AJAX
scrolling 如果采用iframe 是否启用滚动条，默认为自动 有三个值 no yes auto
top 顶坐标 默认居中
left 顶坐标 默认居中
注意 使用前应引入/imgedit/manipulate.js
请在页面中加入 <div id='movediv' style="display:none;"></div>这个 一般加在最底部
*/
function movediv(url,title,css,cajax,scrolling,top,left,cssid)
{
	if(!cssid)
	{
		cssid="movediv";
	}
	document.getElementById(cssid).style.display='';

	if(!scrolling){scrolling='auto';}
	
	var infos='<div id=move_div_info style="text-align:center;width:100%;height:100%;"><\/div>';
	
	if(!title)
	{
		title='';
	}
	
	document.getElementById(cssid).innerHTML = '<div id=movediv_  onmousedown=\"canmove(this, event)\" class="frame">'+
	'<div class="frame-a">'+
    	'<div class="w24"><img src="/admin/images/frame/top-1.gif" /><\/div>'+
        '<div class="f-am1"><div style="position:absolute;top:13px;right:25px;"><img style="cursor:pointer;" onclick="logoutdiv(\''+cssid+'\')" src="/admin/images/frame/close.gif" /></div><\/div>'+
        '<div class="w24"><img src="/admin/images/frame/top-3.gif" /><\/div>'+
    '<\/div>'+
	'<div class="frame-b">'+
    	'<div class="f-bl"><\/div>'+
		'<div class="f-bm">'+infos+
		'<\/div>'+
        '<div class="f-br"><\/div>'+
	'<\/div>'+
	'<div class="frame-a">'+
    	'<div class="w24"><img src="/admin/images/frame/bot-1.gif" /><\/div>'+
        '<div class="f-am2"></div>'+
        '<div class="w24"><img src="/admin/images/frame/bot-3.gif" /><\/div>'+
    '<\/div>'
'<\/div>';
	
	loading('move_div_info','big');//载入

	//var ss=new shade_show("movediv_");
	//ss.show_fade();//渐变显示调用 
	
	var mobj=document.getElementById("movediv_");
	
	mobj.className=css;
	
	document.documentElement.scrollTop = 0; 

	var yw = document.documentElement.clientWidth; 
	
	var yh = document.documentElement.clientHeight; 
	
	var w = mobj.clientWidth||mobj.offsetWidth;
	
	var h = mobj.clientHeight||mobj.offsetHeight;
		
	if(!top && !left)
	{
		mobj.style.top=((yh/2)-(h/2))+"px";
			
		mobj.style.left=((yw/2)-(w/2))+"px";
	}else{
		if(!top)
		{
			mobj.style.top=((yh/2)-(h/2))+"px";
				
			mobj.style.left=left+"px";
		}else if(!left)
		{
			mobj.style.top=top+"px";
				
			mobj.style.left=((yw/2)-(w/2))+"px";
		}else{
			mobj.style.top=top+"px";
				
			mobj.style.left=left+"px";
		}

	}
	
	if(Number(cajax))
	{
		ajax_ultimate(url,'','','','move_div_info');
	}else{
		document.getElementById("move_div_info").innerHTML='<iframe src="'+url+'" hspace="0" vspace="0" frameborder="0" scrolling="'+scrolling+'" height="100%" width="100%"><\/iframe>';
	}
}

/*小的*/
function small_movediv(url,css,top,left,cssid)
{
	if(!cssid)
	{
		cssid="movediv";
	}
	document.getElementById(cssid).style.display='';
	
	var infos='<div id=move_div_info_sm style="text-align:center;width:100%;height:100%;"><\/div>';
	
	document.getElementById(cssid).innerHTML = '<div onmousedown=\"canmove(this, event)\" id=movediv_sm class="min-wod">'+
		'<div class="min-wod-a" style="position:relative;Z-INDEX: 998;"><img src="/template/default/f/image/open/bg-min-top.gif" /><div style="position:absolute;Z-INDEX: 999; top:3px;right:5px;"><img style="cursor:pointer;" onclick="logoutdiv(\''+cssid+'\')" src="/template/default/f/image/open/frame/close.gif" /></div></div>'+
			'<div class="min-wod-b">'+infos+'</div>'+
		'<div class="min-wod-a"><img src="/template/default/f/image/open/bg-min-bot.gif" /></div>'+
	'</div>';
	
	loading('move_div_info_sm','small');//载入

	var ss=new shade_show("movediv_sm");
	ss.show_fade();//渐变显示调用 
	
	var mobj=document.getElementById("movediv_sm");
	mobj.className=css;
	document.documentElement.scrollTop = 0; 

	var yw = document.documentElement.clientWidth; 
	
	var yh = document.documentElement.clientHeight; 
	
	var w = mobj.clientWidth||mobj.offsetWidth;
	
	var h = mobj.clientHeight||mobj.offsetHeight;
		
	if(!top && !left)
	{
		mobj.style.top=((yh/2)-(h/2))+"px";
			
		mobj.style.left=((yw/2)-(w/2))+"px";
	}else{
		if(!top)
		{
			mobj.style.top=((yh/2)-(h/2))+"px";
				
			mobj.style.left=left+"px";
		}else if(!left)
		{
			mobj.style.top=top+"px";
				
			mobj.style.left=((yw/2)-(w/2))+"px";
		}else{
			mobj.style.top=top+"px";
				
			mobj.style.left=left+"px";
		}

	}
	
	ajax_ultimate(url,'','','','move_div_info_sm');
}

function logoutdiv(cssid)
{
	if(!cssid)
	{
		cssid="movediv";
	}
	document.getElementById(cssid).style.display='none';
}


function canmove(elementToDrag, event)
{
	if(document.getElementById('yesmove')!=null)
	{
		if(document.getElementById('yesmove').checked!=true){return false;}
	}
	var deltaX = event.clientX - parseInt(elementToDrag.style.left);
	var deltaY = event.clientY - parseInt(elementToDrag.style.top);
	
	elementToDrag.style.cursor = "move";
	
	if (document.addEventListener)
	{//2 级 DOM事件模型
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}else if (document.attachEvent){//IE5+事件模型
	
		document.attachEvent("onmousemove", moveHandler);
		document.attachEvent("onmouseup", upHandler);
	}else {//IE4事件模型
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	
	//禁止起泡
	if (event.stopPropagation)//DOM2
	event.stopPropagation();
	else event.cancelBubble = true;//IE
	
	if (event.preventDefault)
	event.preventDefault();
	else event.cancelBubble = true;
	
	function moveHandler(e)
	{
		if (!e)
		e = window.event;
		
		elementToDrag.style.left = (e.clientX - deltaX) + "px";
		elementToDrag.style.top = (e.clientY - deltaY) + "px";
		
		if (e.stopPropagation)
		e.stopPropagation();
		else e.cancelBubble = true;
	}
	
	function upHandler(e)
	{
		if (!e)
		e = window.event;
		
		elementToDrag.style.cursor = "default";
		
		if (document.removeEventListener)
		{ //DOM2
			document.removeEventListener('mousemove', moveHandler, true);
			document.removeEventListener('mouseup', upHandler, true);
			
		}else if (document.detachEvent) 
		{ //IE5+
			document.detachEvent("onmousemove", moveHandler);
			document.detachEvent("onmouseup", upHandler);
			
			
		}else {//IE4
			document.onmousemove = moveHandler;
			document.onmouseup = upHandler;
		}
		
		if (e.stopPropagation)
		e.stopPropagation();
		else e.cancelBubble = true;
	}
}

function canmoveto2(elementToDrag,elementToDrag2,zhjx,zhjy,fx, event)
{
	if(document.getElementById('yesmove')!=null)
	{
		if(document.getElementById('yesmove').checked!=true){return false;}
	}
	var deltaX = event.clientX - parseInt(elementToDrag.style.left);
	var deltaY = event.clientY - parseInt(elementToDrag.style.top);
	
	elementToDrag.style.cursor = "move";
	
	if (document.addEventListener)
	{//2 级 DOM事件模型
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}else if (document.attachEvent){//IE5+事件模型
	
		document.attachEvent("onmousemove", moveHandler);
		document.attachEvent("onmouseup", upHandler);
	}else {//IE4事件模型
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	
	//禁止起泡
	if (event.stopPropagation)//DOM2
	event.stopPropagation();
	else event.cancelBubble = true;//IE
	
	if (event.preventDefault)
	event.preventDefault();
	else event.cancelBubble = true;
	
	function moveHandler(e)
	{
		if (!e)
		e = window.event;
		elementToDrag.style.left = (e.clientX - deltaX) + "px";
		elementToDrag.style.top = (e.clientY - deltaY) + "px";
		if(fx==0)
		{
			elementToDrag2.style.left = ((e.clientX - deltaX)+zhjx)+ "px";
			elementToDrag2.style.top = ((e.clientY - deltaY) +zhjy)+"px";
		}else{
			elementToDrag2.style.left = ((e.clientX - deltaX)-zhjx)+ "px";
			elementToDrag2.style.top = ((e.clientY - deltaY) -zhjy)+"px";
		}
		if (e.stopPropagation)
		e.stopPropagation();
		else e.cancelBubble = true;
	}
	
	function upHandler(e)
	{
		if (!e)
		e = window.event;
		
		elementToDrag.style.cursor = "default";
		
		if (document.removeEventListener)
		{ //DOM2
			document.removeEventListener('mousemove', moveHandler, true);
			document.removeEventListener('mouseup', upHandler, true);
			
		}else if (document.detachEvent) 
		{ //IE5+
			document.detachEvent("onmousemove", moveHandler);
			document.detachEvent("onmouseup", upHandler);
			
			
		}else {//IE4
			document.onmousemove = moveHandler;
			document.onmouseup = upHandler;
		}
		
		if (e.stopPropagation)
		e.stopPropagation();
		else e.cancelBubble = true;
	}
}


/*取得DIV的背景图*/

function divbackimg(id)
{

	return document.getElementById(id).style.backgroundImage.replace("url(","").replace(")","");
}


/*清理多余HTML代码 content要处理的字符串*/
function DelHtml(content)
{
		a = content.indexOf("<");
		b = content.indexOf(">");
		len = content.length;
		c = content.substring(0, a);
		if(b == -1)
		b = a;
		d = content.substring((b + 1), len);
		content = c + d;
		tagCheck = content.indexOf("<");
		if(tagCheck != -1)
		content = DelHtml(content);
		return content;
} 


/*删除HTML元素比如层 表格等 obj可以似乎ID也可以是对象*/
function Delhtml(obj)
{
	if(typeof(obj)!="object")
	{
		obj = document.getElementById(obj);
	}
	
	obj.parentNode.removeChild(obj);
}

function getIE(e)
{  
	var coordinate=Array;
	var   t=e.offsetTop;
	var   l=e.offsetLeft;
 	while(e=e.offsetParent)
	{  
	  t+=e.offsetTop;  
	  l+=e.offsetLeft;  
	}
	coordinate['top']=t;
	coordinate['left']=l;
	return coordinate;
}
/*临时显示*/
var tooltip=function(){
	var id = 'tt';
	var top = 3;
	var left = 3;
	var maxw = 300;
	var speed = 10;
	var timer = 20;
	var endalpha = 100;
	var alpha = 0;
	var tt,t,c,b,h;
	var ie = document.all ? true : false;
	return{
		show:function(v,w){
			if(tt == null){
				tt = document.createElement('div');
				tt.setAttribute('id',id);
				t = document.createElement('div');
				t.setAttribute('id',id + 'top');
				c = document.createElement('div');
				c.setAttribute('id',id + 'cont');
				b = document.createElement('div');
				b.setAttribute('id',id + 'bot');
				tt.appendChild(t);
				tt.appendChild(c);
				tt.appendChild(b);
				document.body.appendChild(tt);
				tt.style.opacity = 0;
				tt.style.filter = 'alpha(opacity=80)';
				document.onmousemove = this.pos;
			}
			tt.style.display = 'block';
			c.innerHTML = v;
			tt.style.width = w ? w + 'px' : 'auto';
			if(!w && ie){
				t.style.display = 'none';
				b.style.display = 'none';
				tt.style.width = tt.offsetWidth;
				t.style.display = 'block';
				b.style.display = 'block';
			}
			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
			h = parseInt(tt.offsetHeight) + top;
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(1)},timer);
		},
		pos:function(e){
            var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
			tt.style.top = u + 'px';
			tt.style.left = (l + left) + 'px';
		},
		fade:function(d){
			var a = alpha;
			
			if((a != endalpha && d == 1) || (a != 0 && d == -1)){
				var i = speed;
				if(endalpha - a < speed && d == 1){
					i = endalpha - a;
				}else if(alpha < speed && d == -1){
					i = a;
				}
				alpha =80;// a + (i * d);
				tt.style.opacity = alpha * .01;
				tt.style.filter = 'alpha(opacity=' + alpha + ')';
				alpha = 0;
			}else{
				clearInterval(tt.timer);
				if(d == -1){tt.style.display = 'none'}
			}
		},
		hide:function(){
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
		}
	};
}();

/*IE6兼容PNG格式图片*/
function ie6forpng()
{
	ie6forpng.prototype.correctPNG=function()
	{
	  for(var i=0; i<document.images.length; i++)
	  {
		  var img = document.images[i]
		  var imgName = img.src.toUpperCase()
		  if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
		 {
			 var imgID = (img.id) ? "id='" + img.id + "' " : ""
			 var imgClass = (img.className) ? "class='" + img.className + "' " : ""
			 var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
			 var imgStyle = "display:inline-block;" + img.style.cssText 
			 if (img.align == "left") imgStyle = "float:left;" + imgStyle
			 if (img.align == "right") imgStyle = "float:right;" + imgStyle
			 if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle        
			 var strNewHTML = "<span "+ imgID + imgClass + imgTitle + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";" + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader" + "(src='" + img.src + "', sizingMethod='scale');\"></span>" 
			 img.outerHTML = strNewHTML
			 i = i-1
		 }
	  }
	}
	
	ie6forpng.prototype.png=function()
	{
		var version=navigator.appVersion.split(";");
		var trim_Version=version[1].replace(/[ ]/g,""); 
		
		if(navigator.appName=="Microsoft Internet Explorer" && trim_Version=="MSIE6.0")
		{
			window.attachEvent("onload", this.correctPNG); 
		}
	}
}
var ie6forpng=new ie6forpng();
ie6forpng.png();
/*IE6兼容PNG格式图片END*/

/*数组化*/
function arrayed(str)
{
	var s=str.split(',');
	var ss=Array();
	
	for(i=0;i<s.length;i++)
	{
		var v=s[i].split(':');
		ss[v[0]]=v[1];
	}
	return ss;
}

//这个函数的功能是：当秒小于10的时候，就在秒前面加上一个数字0 
function add_zero(temp) 
{ 
	if(temp<10) return "0"+temp; 
	else return temp; 
} 
/*将秒转换成时分秒*/
String.prototype.toHis=function(){
    var h,m,s;
   h=parseInt(this/3600)
   m=parseInt((this%3600)/60)
   s=this%60;
	return add_zero(h)+':'+add_zero(m)+':'+add_zero(s);
}

/*
图片预加载
url 图片URL地址
callback 回调函数

//被回调的方法
function imgLoaded(){alert(this.width);}
<input type="button" value="loadImage" onclick="loadImag('xxx.jpg',imgLoaded)"/>
*/
function loadImage(url,callback,bfb,djg)
{
   var img = new Image(); //创建一个Image对象，实现图片的预下载
   img.src = url;
   if (img.complete)
   { // 如果图片已经存在于浏览器缓存，直接调用回调函数
   		if(callback)
		{
			eval(callback + "(bfb,djg)");
		}
   }

   img.onload = function () { //图片下载完毕时异步调用callback函数。
   		if(callback)
		{
		eval(callback + "(bfb,djg)");
		}
   }
}

function getEvent() //同时兼容ie和ff的写法
{ 
	if(document.all)  return window.event;   
	func=getEvent.caller;       
	while(func!=null){ 
		var arg0=func.arguments[0];
		if(arg0)
		{
		  if((arg0.constructor==Event || arg0.constructor ==MouseEvent) || (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation))
		  { 
		  return arg0;
		  }
		}
		func=func.caller;
	}
	return null;
}

function getxy()
{
		var event=getEvent();
        if (navigator.appName=='Microsoft Internet Explorer')
		{
			return {x:event.x,y:event.y}
        }else if (navigator.appName=='Netscape') {
			return {x:event.pageX,y:event.pageY}
        } 	
}

function gotos(url)
{
	window.location.href=url;
}

/*图片预加载*/
Jay_loadImg=function (){

	this.pos=0;/* 进度 */
	this.count=0;/* 图片总数 */
	this.clip=[];/* 图片地址夹 */
	this.last='';/* 最后一个图片地址,显示用 */

};

Jay_loadImg.prototype={/* 一个小小的图片轮流加载器 */

	add:function(url){this.clip.push(url);this.count++;return this;},/* 添加一个地址 */
	load:function(){
		var J1=this.clip.pop();/* 弹出一个准备下载的地址 */
		var J2=this.clip.pop();/* 弹出一个准备下载的地址 */
		var J3=this.clip.pop();/* 弹出一个准备下载的地址 */
		var J4=this.clip.pop();/* 弹出一个准备下载的地址 */
		var J5=this.clip.pop();/* 弹出一个准备下载的地址 */
		
		
		
		var img1=new Image(),img2=new Image(),img3=new Image(),img4=new Image(),img5=new Image(),Root=this;
		img1.src=J1;
		img2.src=J2;
		img3.src=J3;
		img4.src=J4;
		img5.src=J5;
		
		this.pos+=5;/* 计数器累加 */
		
		if(this.pos>=this.count)
		{
			this.pos=this.count;
		}
		
		this.ready(J5);/* 单张图片准备下载-回调 */
		
		if(J1==undefined || J2==undefined  || J3==undefined  || J4==undefined  || J5==undefined)return this.end();/* 全部完成-回调 */
		img1.onerror=function(){this.error=true;};/* 下载错误也算成功 */
		img2.onerror=function(){this.error=true;};/* 下载错误也算成功 */
		img3.onerror=function(){this.error=true;};/* 下载错误也算成功 */
		img4.onerror=function(){this.error=true;};/* 下载错误也算成功 */
		img5.onerror=function(){this.error=true;};/* 下载错误也算成功 */
		
		(function(){
			if((img1.complete||img1.error) && (img2.complete||img2.error) && (img3.complete||img3.error) && (img4.complete||img4.error) && (img5.complete||img5.error)){
				//Root.onload(J);/* 成功-回调 */
				return Root.load();/* 下载下一张 */			
			}
			setTimeout(arguments.callee,100);/* 100毫秒后再测试 */
		})();
		
		
	},
	ready:Array,onload:Array,end:Array/* 空回调函数占位符,皆传入一个当时src参数 */

};
/*图片预加载END*/

/*
//handler是回调函数
function confirmInfo(str,handler)
{
		ymPrompt.confirmInfo(str,null,null,'您要做哪种选择？',handler);
}

function alert_(str)
{
		ymPrompt.alert(str,null,null,'提示信息！');
}

function succeedInfo(str)
{
		ymPrompt.succeedInfo(str,400,260,'操作成功！');
}

function errorInfo(str)
{
		ymPrompt.errorInfo(str,null,null,'操作失败！');
}
*/
/*
提示框
str  内容
confirmInfo 选择 
handler 回调函数
parameter 回调函数参数
*/
function alert_(str,confirmInfo,handler,parameter)
{
	var bodyheight = document.compatMode=="CSS1Compat" ? document.documentElement.clientHeight : document.body.clientHeight;
	str=str?str:'';
	confirmInfo=confirmInfo?confirmInfo:'';
	handler=handler?handler:'';
	parameter=parameter?parameter:'';
	document.getElementById('tck_qwrt_').style.height=bodyheight;
	document.getElementById('tck_qwrt_').style.display='';
	document.getElementById('tck_qwrt').style.display='';
	
	if(confirmInfo==1)
	{
		var xz="<div class='wod-b3-diva'><input class='button-m' style='border:0;' type='button' onclick=\"xconfirmInfo(1,'"+handler+"','"+parameter+"')\" value='确定' /></div>"+
            "<div class='wod-b3-divb'><input class='button-m' style='border:0;' type='button' onclick=\"xconfirmInfo(2,'','')\" value='取消' /></div>";
	}else{
		var xz="<div class='wod-b3-divx'><input class='button-m' style='border:0; float:left;' type='button' onclick=\"alert_close('"+handler+"','"+parameter+"')\" value='确定' /></div>";
	}
		var yyu="<div class='min-wod'>"+
		"<div class='min-wod-a'><img src='/template/default/f/image/open/bg-min-top.gif' /></div>"+
		"<div class='min-wod-b'>"+
			"<div style='padding-top:8px;'>"+
				"<div class='wod-b3 concave'>"+str+"</div>"+
			"</div>"+
			"<div class='wod-b2'>"+xz+ "</div>"+
		"</div>"+
		"<div class='min-wod-a'><img src='/template/default/f/image/open/bg-min-bot.gif' /></div>"+
	"</div>";
	document.getElementById('tck_qwrt').innerHTML=yyu;
}

function alert_close(handler,parameter)
{
	if(handler){eval(handler + "(parameter)"); }
	document.getElementById('tck_qwrt').innerHTML='';
	document.getElementById('tck_qwrt').style.display='none';
	document.getElementById('tck_qwrt_').style.display='none';
}

function xconfirmInfo(e,handler,parameter)
{
	if(!e)
	{
		document.getElementById('tck_qwrt').innerHTML='';
	}else{
		alert_close(handler,parameter);
	}
}

////////////////////////
	function socketreg(user_name,flag)
	{
		sends("reg:user:"+user_name+":"+flag+":###");
	}

 	function link_nc(host,post,post1)
	{
		thisMovie("ExternalInterfaceExample").link_nc(host,post,post1); 
 	}
	//发送
	function sends(str)
	{
		//document.forms["form1"].output.value += "发送socket: " + str + "\n";
		thisMovie("ExternalInterfaceExample").sends(str); 
	}
	var socket_flag=0;
	//接收
	function getsocket(str)
	{
		if(str=="pulse")
		{
			return;
		}
		
		switch(str){
            case "connect_success":
               //连接成功的函数
			   //document.getElementById("output").value="连接成功\n";
			   gosocket();
			   socket_flag=1;
                break;
				
            case "connect_close":
                //断开重连接
				socket_flag=0;
				document.getElementById('jlsockets').style.display='';
				document.getElementById('jlsockets').innerHTML='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>连接被断开，正在准备从新连接，情稍等....  <a  class="noshiyong" href="#" onclick="close_socket()">我不使用聊天和信件通知，请点击此</a>，或刷新页面';
				setTimeout("autoclick()",2000);
                break;
			 case "securityError":
                //安全错误
				socket_flag=0;
                break;
			 case "ioErrorHandler":
                //IO错误
				socket_flag=0;
                break;
			//default;
			 default:
			 	//数据处理
				socketdata(str);
			 	break;
			
        }
	}
	
	function close_socket()
	{
		document.getElementById('jlsockets').style.display='none';
	}
	
	function socketdata(str)
	{
		var strarr=Array();
		var strzarr=Array();
		strarr=str.split("###");
		for(i=0;i<strarr.length;i++)
		{
			strzarr=strarr[i].split(":");
			var newstr=strarr[i].replace(strzarr[0]+":"+strzarr[1]+":"+strzarr[2]+":",""); 
			switch(strzarr[0]){
				case "reg":
						//loginload();
						if(newstr=="ok")
						{
							document.getElementById('jlsockets').style.display='none';
						}
					break;
					
				case "chat":
						chat(strzarr[1],strzarr[2],newstr);
					break;
				
				case "data":
					
					break;
					
				case "dataget":
						//document.getElementById("output").value+=strzarr[3]+"\n";
					break;
				
				case "correspondent":
						correspondent(strzarr[1],strzarr[2],newstr);
					break;
				 default:
					//异常
					
					break;
			}
		}
	}
	
	function autoclick()
	{
		link_nc('58.221.36.66', '4502','943');
	}

	function goto(url)
	{
		window.location.href=url;
	}
//////////////////////////////////////////////////////


     function thisMovie(movieName) {
         if (navigator.appName.indexOf("Microsoft") != -1) {
             return window[movieName];
         } else {
             return document[movieName];
         }
     }
     function sendToActionScript(value) {
         thisMovie("ExternalInterfaceExample").sendToActionScript(value);
		 sends(value)
     }
     function sendToJavaScript(value) {
         //document.forms["form1"].output.value += "ActionScript says: " + value + "\n";
     }
	 ///////////////////////////////////////////
	 
	 
	/*删除HTML元素比如层 表格等*/
	function Delhtml(obj)
	{
		obj.parentNode.removeChild(obj);
	}
	