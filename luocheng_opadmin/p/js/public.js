<!--
/**
 * +--------------------------------------------------
 * |   ��������opens(theURL,winName,size)
 * |   ���ã��򿪹̶���С�Ĵ���
 * |   ������
 * |   theURL    Ҫ�򿪵ĵ�ַ   
 * |   winName   ��������
 * |   size      ���ڳߴ� ��Ϊfullscreen ʱΪȫ��
 * |   ����ֵ����
 * +--------------------------------------------------
 */

function opens(theURL,winName,size)
{
  window.open(theURL,winName,size);
}

/*���ɼ���*/
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

/*������ж�*/
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
	
	/*AJAXִ��״̬*/
	ajaxsends.prototype.ajax_yun=function()
	{
		//��ȡִ��״̬
		this_.ajax.onreadystatechange = function() 
		{
			//���ִ����״̬��������ô�Ͱѷ��ص����ݸ�ֵ������ָ���Ĳ�
			if (this_.ajax.readyState == 4 && this_.ajax.status == 200)
			{
				var	strdiv = this_.ajax.responseText;  //��ȡPHPҳ���ӡ����������
				
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
				
				/*�ͷű���������*/
				this_.url=this_.fun=this_.method=this_.fromid=this_.id=strdiv=jsstr=this_.ajax=this_.vars=null;
				delete this_.ajax ; 
				this_=null;
				if(getOs()=='MSIE'){CollectGarbage;}
				
		//////////////////////////////////�����Ǵ���//////////////////////////////////////////////////
			}
		}
	}
	
	
	/* ʵ����AJAX*/
	ajaxsends.prototype.user_InitAjaxw=function()
	{
		 if (window.ActiveXObject)
		 {
			//IE
			try {
				//IE6.0����
				return new ActiveXObject("Microsoft.XMLHTTP");
			}catch (e1) {
					//IE5.5����
					return new ActiveXObject("Msxml2.XMLHTTP");
			}
		} else if (window.XMLHttpRequest) {
			//FireFox
			return new XMLHttpRequest();
		}

	}
	
	/*��ȡ��URL*/
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
			alert('��action����Ϊ�գ�Ҫ�ύ�ĵ�ַ����');
			return false;
		}
		
		u[0]=url;
		u[1]=fromid;
		
		return u;
	}
	
	/*ִ��JS */
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
	
	
	/*POST�ύ*/
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
		//���Ϳ�
		
		try
		{
			var oForm=document.getElementById(this_.url[1]);

		}catch(err){
		
		   var oForm=document.getElementsByName(this_.url[1])[0];
		}
		
		var sBody=getRequestBody(oForm);
		
		this_.ajax.send(sBody);
	}

	

	/*GET�ύ*/
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
		
		this_.get();//GET��ʽ
	}else{
		this_.post();//POST��ʽ	
	}
	
	if(getOs()=='MSIE'){CollectGarbage();}//�ͷŻ���
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
  *   AJAX������
  *   ����˵����
  *   url �ύҳ��  ��ѡ���� geturl(id) �Զ��� Ϊ�� ��3��״̬ ��ѡ ������Ϊ��ѡ
  *   fun ���ú��� Ĭ�ϵ���client  ��ѡ���� ������ �Զ���
  *   method�ύ��ʽ get post Ĭ�� GET 
  *   fromid �ύ����ID������
  *   id     ���Դ�һ�����ز���
  *   vars �����ݷ��ظ��ⲿ������ע�⣺��ʹ����֮������ȶ����ⲿ�������磺var gamehtml='';ajax_ultimate('index_.php','','','','','gamehtml');������������ȴ�ģ����򱨴� ���ĵȼ���ߣ��������������ٴ���ID����ֵ
  
  *   ��methodΪGETʱ ֻ����� url ��fum��������
  *   ��methodΪPOSTʱ ���URL���õ��� geturl()����ʱֻ����� url,fun,method���� 
  *   ���URLΪ�û�����·��ʱ ��Ҫ��url,fun,method,id��4����������ȫ
  *   ���URLΪ��ʱ����Ҫ���� url,fun,method,id 4������
  *   geturl(id)��AJAX���ӵ��ú���
  *   ���ã�ΪAJAXȡ��FORM����·�� ����IDΪ FORM��ID������
  *   POST�÷���3�� 
  *   1��URL �û��Զ����ַ Fun ��Ϊ�� ,method ΪPOST ����FORM��ID  ajax_ultimate(url,fun,method,id)
  *   2��URL Ϊ�� Fun ��Ϊ�� ,method ΪPOST  ,id �ύ����ID������ ajax_ultimate('',fun,method,id)
  *   3��URL ����url() ����  Fun ��Ϊ�� ,method ΪPOST ajax_ultimate(geturl(ID),fun,method,id)
*/////////////////////////////////////////////////////////////////////////////////////////////////

function ajax_ultimate(url,fun,method,fromid,id,vars)
{
	new ajaxsends(url,fun,method,fromid,id,vars);
	return ;
}


function setCookie(name,value,timet) //Cookie��  Cookie����  Cookie�洢ʱ��
{ //����Cookie
    	var exp  = new Date();    //new Date("December 31, 9998");
        exp.setTime(exp.getTime()+Number(timet));
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getCookie(name)
{//��ȡCookie
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)) return unescape(arr[2]);
        else return null;
}
function delCookie(name)
{//ɾ��Cookie
    var exp = new Date();
        exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
        if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


/*�жϱ��Ƿ����*/
function detection_thing(labelID)
{
	try
		{
			var oForm=document.getElementById(labelID);
			
			return true;	
			
		}catch(err){return false; }
}

/*����Ļ������*/
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
		MyMar=setInterval('scrolldiv_("'+id+'",'+h+')',100);   ///���Ʋ���ƶ�
	}else{
		clearInterval(MyMar);
	}
}

function  scrolldiv_(id,h)
{  //��λ��
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

/**************************BEGINͼ�㽥����ʾ*************************************/
/*
����˵��
div_idҪ���ƽ����ID
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
	   this_.fade_opacity = this_.fade_opacity + 5; //����ʾ�ٶ�
	   obj.style.filter = "Alpha(Opacity=" + this_.fade_opacity + ")"; //͸�����𽥱�С
	   obj.style.opacity = this_.fade_opacity/100; //����FireFox
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
	   obj.style.filter = "Alpha(Opacity=0)"; //͸�����𽥱�С
	   obj.style.opacity = this_.fade_opacity/100; //����FireFox
	   obj.style.display="block";
	   this_.fade_times = setInterval(this_.change_show,1);
	}
}
/**************************ENDͼ�㽥����ʾ**************************************/	

/*
����˵����
url   �ƶ����ڵ����ݵ�ַ
title  ����
css   �ƶ������ʽ�����ð���TOP��LEFT��
cajax  �ǲ���AJAX����iframe��ʽ�������� 1Ϊ������AJAX 0Ϊ����AJAX
scrolling �������iframe �Ƿ����ù�������Ĭ��Ϊ�Զ� ������ֵ no yes auto
top ������ Ĭ�Ͼ���
left ������ Ĭ�Ͼ���
ע�� ʹ��ǰӦ����/imgedit/manipulate.js
����ҳ���м��� <div id='movediv' style="display:none;"></div>��� һ�������ײ�
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
	
	loading('move_div_info','big');//����

	//var ss=new shade_show("movediv_");
	//ss.show_fade();//������ʾ���� 
	
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

/*С��*/
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
	
	loading('move_div_info_sm','small');//����

	var ss=new shade_show("movediv_sm");
	ss.show_fade();//������ʾ���� 
	
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
	{//2 �� DOM�¼�ģ��
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}else if (document.attachEvent){//IE5+�¼�ģ��
	
		document.attachEvent("onmousemove", moveHandler);
		document.attachEvent("onmouseup", upHandler);
	}else {//IE4�¼�ģ��
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	
	//��ֹ����
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
	{//2 �� DOM�¼�ģ��
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}else if (document.attachEvent){//IE5+�¼�ģ��
	
		document.attachEvent("onmousemove", moveHandler);
		document.attachEvent("onmouseup", upHandler);
	}else {//IE4�¼�ģ��
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	
	//��ֹ����
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


/*ȡ��DIV�ı���ͼ*/

function divbackimg(id)
{

	return document.getElementById(id).style.backgroundImage.replace("url(","").replace(")","");
}


/*�������HTML���� contentҪ������ַ���*/
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


/*ɾ��HTMLԪ�ر���� ���� obj�����ƺ�IDҲ�����Ƕ���*/
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
/*��ʱ��ʾ*/
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

/*IE6����PNG��ʽͼƬ*/
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
/*IE6����PNG��ʽͼƬEND*/

/*���黯*/
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

//��������Ĺ����ǣ�����С��10��ʱ�򣬾�����ǰ�����һ������0 
function add_zero(temp) 
{ 
	if(temp<10) return "0"+temp; 
	else return temp; 
} 
/*����ת����ʱ����*/
String.prototype.toHis=function(){
    var h,m,s;
   h=parseInt(this/3600)
   m=parseInt((this%3600)/60)
   s=this%60;
	return add_zero(h)+':'+add_zero(m)+':'+add_zero(s);
}

/*
ͼƬԤ����
url ͼƬURL��ַ
callback �ص�����

//���ص��ķ���
function imgLoaded(){alert(this.width);}
<input type="button" value="loadImage" onclick="loadImag('xxx.jpg',imgLoaded)"/>
*/
function loadImage(url,callback,bfb,djg)
{
   var img = new Image(); //����һ��Image����ʵ��ͼƬ��Ԥ����
   img.src = url;
   if (img.complete)
   { // ���ͼƬ�Ѿ���������������棬ֱ�ӵ��ûص�����
   		if(callback)
		{
			eval(callback + "(bfb,djg)");
		}
   }

   img.onload = function () { //ͼƬ�������ʱ�첽����callback������
   		if(callback)
		{
		eval(callback + "(bfb,djg)");
		}
   }
}

function getEvent() //ͬʱ����ie��ff��д��
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

/*ͼƬԤ����*/
Jay_loadImg=function (){

	this.pos=0;/* ���� */
	this.count=0;/* ͼƬ���� */
	this.clip=[];/* ͼƬ��ַ�� */
	this.last='';/* ���һ��ͼƬ��ַ,��ʾ�� */

};

Jay_loadImg.prototype={/* һ��СС��ͼƬ���������� */

	add:function(url){this.clip.push(url);this.count++;return this;},/* ���һ����ַ */
	load:function(){
		var J1=this.clip.pop();/* ����һ��׼�����صĵ�ַ */
		var J2=this.clip.pop();/* ����һ��׼�����صĵ�ַ */
		var J3=this.clip.pop();/* ����һ��׼�����صĵ�ַ */
		var J4=this.clip.pop();/* ����һ��׼�����صĵ�ַ */
		var J5=this.clip.pop();/* ����һ��׼�����صĵ�ַ */
		
		
		
		var img1=new Image(),img2=new Image(),img3=new Image(),img4=new Image(),img5=new Image(),Root=this;
		img1.src=J1;
		img2.src=J2;
		img3.src=J3;
		img4.src=J4;
		img5.src=J5;
		
		this.pos+=5;/* �������ۼ� */
		
		if(this.pos>=this.count)
		{
			this.pos=this.count;
		}
		
		this.ready(J5);/* ����ͼƬ׼������-�ص� */
		
		if(J1==undefined || J2==undefined  || J3==undefined  || J4==undefined  || J5==undefined)return this.end();/* ȫ�����-�ص� */
		img1.onerror=function(){this.error=true;};/* ���ش���Ҳ��ɹ� */
		img2.onerror=function(){this.error=true;};/* ���ش���Ҳ��ɹ� */
		img3.onerror=function(){this.error=true;};/* ���ش���Ҳ��ɹ� */
		img4.onerror=function(){this.error=true;};/* ���ش���Ҳ��ɹ� */
		img5.onerror=function(){this.error=true;};/* ���ش���Ҳ��ɹ� */
		
		(function(){
			if((img1.complete||img1.error) && (img2.complete||img2.error) && (img3.complete||img3.error) && (img4.complete||img4.error) && (img5.complete||img5.error)){
				//Root.onload(J);/* �ɹ�-�ص� */
				return Root.load();/* ������һ�� */			
			}
			setTimeout(arguments.callee,100);/* 100������ٲ��� */
		})();
		
		
	},
	ready:Array,onload:Array,end:Array/* �ջص�����ռλ��,�Դ���һ����ʱsrc���� */

};
/*ͼƬԤ����END*/

/*
//handler�ǻص�����
function confirmInfo(str,handler)
{
		ymPrompt.confirmInfo(str,null,null,'��Ҫ������ѡ��',handler);
}

function alert_(str)
{
		ymPrompt.alert(str,null,null,'��ʾ��Ϣ��');
}

function succeedInfo(str)
{
		ymPrompt.succeedInfo(str,400,260,'�����ɹ���');
}

function errorInfo(str)
{
		ymPrompt.errorInfo(str,null,null,'����ʧ�ܣ�');
}
*/
/*
��ʾ��
str  ����
confirmInfo ѡ�� 
handler �ص�����
parameter �ص���������
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
		var xz="<div class='wod-b3-diva'><input class='button-m' style='border:0;' type='button' onclick=\"xconfirmInfo(1,'"+handler+"','"+parameter+"')\" value='ȷ��' /></div>"+
            "<div class='wod-b3-divb'><input class='button-m' style='border:0;' type='button' onclick=\"xconfirmInfo(2,'','')\" value='ȡ��' /></div>";
	}else{
		var xz="<div class='wod-b3-divx'><input class='button-m' style='border:0; float:left;' type='button' onclick=\"alert_close('"+handler+"','"+parameter+"')\" value='ȷ��' /></div>";
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
	//����
	function sends(str)
	{
		//document.forms["form1"].output.value += "����socket: " + str + "\n";
		thisMovie("ExternalInterfaceExample").sends(str); 
	}
	var socket_flag=0;
	//����
	function getsocket(str)
	{
		if(str=="pulse")
		{
			return;
		}
		
		switch(str){
            case "connect_success":
               //���ӳɹ��ĺ���
			   //document.getElementById("output").value="���ӳɹ�\n";
			   gosocket();
			   socket_flag=1;
                break;
				
            case "connect_close":
                //�Ͽ�������
				socket_flag=0;
				document.getElementById('jlsockets').style.display='';
				document.getElementById('jlsockets').innerHTML='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>���ӱ��Ͽ�������׼���������ӣ����Ե�....  <a  class="noshiyong" href="#" onclick="close_socket()">�Ҳ�ʹ��������ż�֪ͨ��������</a>����ˢ��ҳ��';
				setTimeout("autoclick()",2000);
                break;
			 case "securityError":
                //��ȫ����
				socket_flag=0;
                break;
			 case "ioErrorHandler":
                //IO����
				socket_flag=0;
                break;
			//default;
			 default:
			 	//���ݴ���
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
					//�쳣
					
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
	 
	 
	/*ɾ��HTMLԪ�ر���� ����*/
	function Delhtml(obj)
	{
		obj.parentNode.removeChild(obj);
	}
	