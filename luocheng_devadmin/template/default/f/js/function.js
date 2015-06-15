// JavaScript Document
//描述文字处理
function act_input(obj,type)
{
	if(type)
	{
		if(obj.getAttribute("data")==obj.value)
		{
			obj.style.color="#000";
			obj.value="";
		}	
	}else{
		if(!obj.value)
		{
			obj.style.color="#ccc";
			obj.value=obj.getAttribute("data");
		}
	}
}

//刷新验证
function replace_code()
{
	$("verifyimg").src=$("verifyimg").src+"&time="+timestamp();
}

//资讯切换游戏查询
function infotogame(obj)
{
	var get=getargs();
	var module=get.module;
	var page=get.page;
	
	if(!module){module="info";}
	if(!page){page=1;}
	url_="/index.php?module="+module+"&game_id="+obj.value;
	goto(url_);
}


//资讯选择游戏默认
function info_select_game()
{
	var get=getargs();
	
	var game_id=get.game_id;
	if(!game_id){game_id=0;}
	
	if(is_select_item_value($("game_id"),game_id))
	{
		set_select_item($("game_id"), game_id);
	}
}

//编辑通用函数
/*
 * obj							本身对象
 * url							修改地方的url
 * filed						要修改的字段
 * query_filed		条件字段
 * query_value		条件值
 * textleng     显示长度
 * html         显示外部html
 * */
function editdata(obj,url,filed,query_filed,query_value,textleng,html)
{
	var w= obj.offsetWidth;
	var h= obj.offsetHeight;
	
	/*
	var newNode;
	
	newNode = document.createElement("input");
	newNode.setAttribute("id","tmpinput");
	newNode.setAttribute("style","width:"+w+"px;height:"+h+"px");
	newNode.setAttribute("onblur", "javascript:editove(this);");
	newNode.setAttribute("value",obj.innerHTML);
	obj.appendChild(newNode);
	*/
	
	if(html)
	{
		var inp=html;
		obj.innerHTML=inp;
		$("tmpinput").focus();
	}else{
		var inp="";
		if(obj.getAttribute("data").length>150)
		{
			inp="<textarea onblur=\"editove('"+obj.id+"',this,'"+url+"','"+filed+"','"+query_filed+"','"+query_value+"',"+textleng+","+html+")\" rows='4' cols='100' name=\"tmpinput\" id=\"tmpinput\">"+obj.getAttribute("data")+"</textarea>";
		}else{
			inp="<input onblur=\"editove('"+obj.id+"',this,'"+url+"','"+filed+"','"+query_filed+"','"+query_value+"',"+textleng+","+html+")\" style=\"width:"+(w)+"px;height:"+h+"px;_height:18px;_line-height:18px;margin:0px;padding:0px;\" name=\"tmpinput\" id=\"tmpinput\" value=\""+obj.getAttribute("data")+"\"/>";
		}
		
		obj.innerHTML=inp;
		$("tmpinput").focus();
		$("tmpinput").select();
	}
}

function editove(id,data,url,filed,query_filed,query_value,textleng,html)
{
	 if($(id).getAttribute("data")!=data.value)
	 {
		 $(id).setAttribute("data",data.value);
		 ajax_ultimate(url+"&query_filed="+query_filed+"&query_value="+query_value+"&filed="+filed+"&data="+data.value,"runeditove");
	 }
	 
	 if(html)
	 {
		 $(id).innerHTML=html[data.value];
	 }else{
		 textleng=textleng?textleng:10;
		 $(id).innerHTML=mSubstr(data.value,textleng);
	 }
}

function runeditove(msg)
{
	if(msg!=1){
		alert(msg);
	}
}
