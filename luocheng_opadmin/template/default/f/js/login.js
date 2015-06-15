// JavaScript Document
//登陆处理
function login(url)
{
	var login_a='<div id="login_ssss" style=" width:198px; height:227px; text-align:center; margin-left: 70px;"></div>';
	$("login_e_from").innerHTML=login_a;
	loading("login_ssss",'big');
	var login_lasturl="/index.php?module=login&methods=login_situation";
	ajax_ultimate(login_lasturl,"login_situation","","",url);
}

function login_situation(data,url)
{
	data=json_x(data);
	if(data.e==100)
	{//已经登录
		$("login_top_info").innerHTML=data.data.username+'  <a href="/index.php?module=login&methods=unlogin">退出登录</a>';
		loginuser(data.data);
	}else{
		$("login_top_info").innerHTML='<a href="http://www.56uu.com/login/" >[请登录]</a> <a href="http://www.56uu.com/reg/">[免费注册]</a> ';
		nologinuer(url);
	}
}

//登录用户
function loginuser(data)
{
	user=data;
	
	var login_a='<div class="box2">\
  <div class="box2_top">\
    <ul>\
      <li>用户登录</li>\
    </ul>\
  </div>\
  <div class="box2_main">\
    <div class="index_logon1 ft1 "><span><img src="/template/default/f/image/user_header.gif"></span></div>\
    <div class="index_logon2 ft1 ">56uu欢迎您！<br>\
      用户名：<span>'+user.username+'</span></div>\
    <div class="cr"></div>\
    <div class="index_ip">上次登录地：'+user.address+'（IP:'+user.lastip+'）<br>\
      上次登录时间：2011-07-11 12:45:28</div>\
    <div class="index_ip"><a href="/index.php?module=gamequestion" target="_self"><img src="/template/default/f/image/index_logon1.gif"></a> <a href="http://www.56uu.com/index.php?module=center&methods=resetpass" target="_self"><img src="/template/default/f/image/index_logon2.gif"></a> <a href="http://pay.56uu.com/" target="_blank"><img src="/template/default/f/image/index_logon3.gif"></a> <a href="/index.php?module=login&methods=unlogin" onclick="" target="_self"><img src="/template/default/f/image/index_logon4.gif"></a></div>\
    <div class="game_pre">\
             <div class="game_pre_text">您上次玩过的游戏：<br>\
              <span id="scwgdyx" class="lg_1 "></span>\
              <div class="cr"></div>\
            </div>\
            <div style="width:90%;text-align:center;padding-top:4px;"><a href="/index.php?module=xsk" target="_blank"><img src="/template/default/f/image/gift_car.jpg"></a></div>\
  </div>\
</div>\
</div>';
	$("login_e_from").innerHTML=login_a;
	
	ajax_ultimate("/index.php?module=game&methods=scwgdyx","","","","scwgdyx");
}

//未登陆用户
function nologinuer(url)
{
	if(!url){url="";}
	if(url){url='&msgurl='+url;}
	
	var login_a='<div class="box2" > \
          <div class="box2_top" > \
            <ul> \
              <li>用户登录</li> \
            </ul> \
          </div> \
          <div class="box2_main" > \
            <form action="/index.php?module=login&methods=loginact'+url+'" name="userlogin" id="userlogin" method="POST"> \
              <table class="login_table"> \
                <tr> \
                  <td class="td_1">用户名：</td> \
                  <td class="td_2"><input type="text" name="username" id="username" style="width:150px;text-align:left;"></td> \
                </tr> \
                <tr> \
                  <td class="td_1">密&nbsp;&nbsp;码：</td> \
                  <td class="td_2"><input type="password" name="password" id="password" style="width:150px;text-align:left;"></td> \
                </tr> \
                <tr> \
                  <td></td> \
                  <td><input type="checkbox" name="automatic" value="automatic" id="automatic" onclick=""> \
                    <label for="rls">下次自动登录（公共场所慎用）</label></td> \
                </tr> \
                <tr > \
                  <td></td>	\
                  <td><input type="image" src="/template/default/f/image/login.gif"> \
                    <a href="http://www.56uu.com/index.php?module=center&methods=getpwd">忘记密码？</a></td> \
                </tr> \
                <tr> \
                  <td  colspan="2"><div class="bottom_line"></div></td>	\
                </tr> \
                <tr > \
                  <td colspan="2" class="td_3">还没有56UU帐号？免费注册玩遍所有游戏</td> \
                </tr> \
                <tr > \
                  <td colspan="2" class="td_3"><a href="http://www.56uu.com/reg/" target="_self"><img src="/template/default/f/image/reg.gif"></a>\
                </tr> \
              </table> \
            </form> \
            <div class="cr"></div> \
          </div> \
        </div> ';
		$("login_e_from").innerHTML=login_a;
}

//开服列表
function open_server_list()
{
	var url="http://www.56uu.com/index.php?module=game&methods=newgamelsit&domain=1";
	//ajax_ultimate(url,"open_server_list_");
	loadContent(url,"open_server_list_","",99);
}

function open_server_list_(data)
{
	/*
	if(data)
	{
		data=json_x(data);
	}else{
		return false;
	}*/
	
	if(!data){return false;}
	
	var oTest = document.getElementById("open_server_listHTML");//ul的ID
	var newNode;
	var server_list;
	
	for(var i=0;i<data.length;i++)
	{
		server_list='<span class="gm1"><a href="'+data[i]["url"]+'" target="_blank">'+data[i]["fromdate"]+'</a></span> <span class="gm2"><a href="'+data[i]["url"]+'" target="_blank">'+data[i]["gametitle"]+'</a></span> <span class="gm3"> <a href="'+data[i]["gameurl"]+'" target="_blank">'+data[i]["title"]+'</a></span> <span class="gm4">'+data[i]["line"]+'</span>';
		
		newNode = document.createElement("li");
		//newNode.setAttribute("class", "gm_right_main_bg");
		newNode.innerHTML =server_list;
		oTest.appendChild(newNode);
	}
}