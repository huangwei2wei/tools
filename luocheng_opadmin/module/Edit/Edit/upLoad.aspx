<%@ Page Language="C#" AutoEventWireup="True" %>

<html>
 <head>
 
    <script language="C#" runat="server">
 
       void Button1_Click(object Source, EventArgs e) 
       {
          if (File1.PostedFile != null && File1.PostedFile.ContentLength!=0) 
          {
			 Application.Lock();
			 string path=Server.MapPath("upLoad\\");
			 DateTime d=DateTime.Now;
			 string FileName=d.Year.ToString()+d.Month.ToString()+d.Day.ToString()+d.Hour.ToString()+d.Minute.ToString()+d.Second.ToString();
			 string [] arr=File1.PostedFile.FileName.Split(".".ToCharArray());
			 string Expand=arr[arr.Length-1];
			 FileName+="."+Expand;
             try
             {
                File1.PostedFile.SaveAs(path+FileName);
                Response.Write("<script>parent.upFile('upLoad/"+FileName+"');<"+"/script>");
             }
             catch (Exception exc) 
             {
                Response.Write("<script>alert('上传文件出错!');<"+"/script>");
             }
             Application.UnLock();
          }
       }
 
    </script>
  <link href="dialog/button.css" rel="stylesheet" type="text/css" />
  <script language="JavaScript">
	window.onload=function(){
		if(top==self){
			document.body.innerHTML="";
			window.close();
		}
	}
 </script>
 </head>
 <body style="padding:0px;margin:0px;text-align:center;background-Color:#BDD9BE;">
    <form enctype="multipart/form-data" runat="server"> 
       <input id="File1" 
              type="file" 
              runat="server" style="border:0px;"><input type=button 
              id="Button1" 
              value="上传文件" 
              OnServerClick="Button1_Click" 
              runat="server" class="button"> 
    </form>
 
 </body>
 </html>
