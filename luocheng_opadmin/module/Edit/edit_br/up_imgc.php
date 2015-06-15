<?php
require("../../lib/public.php");

$up=new upfile;

$y=date("Y",time());

$m=date("m",time());

$d=date("d",time());

$p=$y.'/'.$m.'/'.$d.'/';

$uptypes=array(  //上传文件类型列表  必须
		 'image/jpg',  
		 'image/jpeg', 
		 'image/png', 
		 'image/pjpeg', 
		 'image/gif', 
		 'image/bmp', 
		 'image/x-png',
    ); 
	
$upparameter=array( //上传控制数组 必须
		 'upload_file_size' =>1024*300,         //允许上传容量
		 'store_dir' =>D_R.'upfile/editor/'.$p,         	 //上传存储路径
		 'filename'=>'upfile',           //表单名
		 'accept_overwrite' =>'',  //是否被允许覆盖                    空为不允许        默认为空
		 'show' =>'',        //是否显示上传过程中的信息           空为不显示        默认为空
		 'autoback' =>'',    //如果出错则返回上一页               空位不返回  	  默认为空
		 'bbn' =>'',         //是否保存原始名                     空位否           默认为空
 	);
createDir(D_R.'upfile/editor/'.$p);

$imgname=$up->upfile_all($uptypes,$upparameter);

$imgp=main_url().'upfile/editor/'.$p.$imgname;
?>

<script language="javascript">
function face(i)
{
	str = '';
	switch(i){
		case 1:
		  stradd(1,95);
		break;
		case 2:
		  stradd(100,123);
		break;
		case 3:
		  stradd(200,239);
		break;
	}

}

function stradd(starti,endi)
{
	window.returnValue = "<?=$imgp;?>";
	window.close();
}

face(1);
</script>