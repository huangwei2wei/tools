<?php 
require("../kernel/base/lib/public.php");

if(isset($_GET["a"]) && $_GET["a"])
{
	$data3=dbarr("SELECT * FROM `base_technology_test` WHERE 1");
	
	foreach($data3 as $value)
	{
		//echo "UPDATE  `base_technology` SET  `coordinate` ='".$value["xy"]."' WHERE  `id` =".$value["id"]."<br>";
		dbarr("UPDATE  `base_technology` SET  `coordinate` ='".$value["xy"]."' WHERE  `id` =".$value["id"]);
	}

	header("Location: ./t.php");exit;
}

if(isset($_POST["post"]) && $_POST["post"])
{
		$name=$_POST["name"];
		foreach($name as $key=>$value)
		{
			dbarr("UPDATE  `base_technology_test` SET  `id` =".$value." WHERE  `tmp_id` =".$key);
		}
}



$data=dbarr("SELECT * FROM `base_technology_test` WHERE 1");
$data1=dbarr("SELECT technology_name,id FROM `base_technology` WHERE 1");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script src="../map/public.js"></script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="width:1400px; height:700px; overflow:hidden; position:relative; background-color:#6FF;">
	<div id="map" style="width:7011px; height:805px; position:absolute; left:6px; top:9px; overflow:hidden; background-image:url(a.png)">
    <?php 
        foreach($data as $key=>$value)
        {
			$str="";
			foreach($data1 as $v)
			{
				if($v["id"]==$value["id"])
				{
					$sell="selected";
				}else{
					$sell="";
				}
				
				$str.="<option ".$sell." value='".$v["id"]."'>".$v["technology_name"]."</option>";	
			}
            $xy=explode(":",$value["xy"]);
            $tmp_id=$value['tmp_id'];
            echo '<div style="position:absolute; width:200px; height:30px; top:'.$xy[1].'px; left: '.$xy[0].'px;"><select name="name['.$tmp_id.']" id="name">'.$str.'</select></div>';
        }
    ?>
  </div>
</div>
<input name="post" type="hidden" id="post" value="1" />
<div style="width:800px; text-align: right;"><input type="submit" name="button" id="button" value="提交" /></div>
</form>
<a href="t.php?a=1">校对数据 </a>
<script>
dragdrop.registerDragdropHandler("map","map");
</script>
</body>
</html>
