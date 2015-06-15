<?php 
namespace kernel\base\base_class;

class send_mail{
	/*用法
	$send_mail=new send_mail();
	$mail=$send_mail->config_mail();
	$mail->Subject    =$data_arr[0];
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From send1.56uu.com"; // optional, comment out and test
	$mail->MsgHTML($data_arr[1]);
	
	//$mail->AddAttachment("images/phpmailer.gif");      // attachment 
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	
	foreach($mail_arr as $key=>$value)
	{
		if($value["email"])
		{
			if(!$value["nickname"]){$value["nickname"]="";}
			$mail->ClearAllRecipients();//清除邮件地址
			$mail->AddAddress(trim($value["email"]),$value["nickname"]);
			if($mail->Send())
			{
				echo trim($value["email"])."===ok\n";
			}else{
				echo trim($value["email"])."===on\n";
			}
		}
	}
	
	file_put_contents("/data0/htdocs/www/mail/log/mailerrorlog.log",implode($email->Errmail,"\n"));
	file_put_contents("/data0/htdocs/www/mail/log/senderrorlog.log",implode($email->Errsend,"\n"));
	*/
	
	function config_mail($setfrom="send1@56uu.com",$setfromname="56uu客服部")
	{
		error_reporting(E_STRICT);
		date_default_timezone_set("Asia/Shanghai");	//设定时区东八区
		require_once(D_R.'/kernel/base/adhibition/base_class/mail/class.phpmailer.php');
		require_once(D_R."/kernel/base/adhibition/base_class/mail/class.smtp.php");
		$mail	= new PHPMailer();					//new一个PHPMailer对象出来
		$body	= eregi_replace("[\]",'',$body);	//对邮件内容进行必要的过滤
		$mail->CharSet ="UTF-8";	//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
		$mail->IsSMTP();			// 设定使用SMTP服务
		$mail->SMTPDebug  = 1;		// 启用SMTP调试功能
		// 1 = errors and messages
		// 2 = messages only
		$mail->SMTPAuth   = true;		// 启用 SMTP 验证功能
		$mail->SMTPSecure = "";			// 安全协议ssl
		$mail->Host       = "mail.56uu.com";	// SMTP 服务器
		$mail->Port       = 25;					// SMTP服务器的端口号
		$mail->Username   = "send1@56uu.com";		// SMTP服务器用户名
		$mail->Password   = "L123456";            // SMTP服务器密码
		$mail->SetFrom($setfrom,$setfromname); //$mail->SetFrom('发件人地址，如admin#jiucool.com #换成@', '发件人名称');
		return $mail;
	}
}
?>