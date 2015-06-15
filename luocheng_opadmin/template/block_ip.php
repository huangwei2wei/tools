<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<?php
require("islogin.php");
require("kernel/base/lib/public.php");

if($_POST["info"])
{
	file_put_contents(GAME_DIR."res/config/shield_ip/shield_ip.log",$_POST["info"]);
}
$shield_ip=file_get_contents(GAME_DIR."res/config/shield_ip/shield_ip.log");
?>
<form id="form1" name="form1" method="post" action="#">
  <p>
    <label>
      <textarea name="info" cols="80" rows="40" id="textfield"><?php echo $shield_ip?></textarea>
    </label>
  </p>
  <p>
    <label>
      <input type="submit" name="button" id="button" value="提交" />
    </label>
  </p>
</form>
</body>
</html>