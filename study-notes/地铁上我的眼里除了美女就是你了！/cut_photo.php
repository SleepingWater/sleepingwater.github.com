<?php 
/*
 	* @abstract cut_photo
	* @param $key
	* @author 眭钰
*/
$info=getimagesize("./uploads/sunset.jpg");

if($_POST['sub'])
{	
	$rate=100;
	$old_src=$_POST['p'];
	$file_name1=time().rand(100,999).'1'.'.jpg';
	$file_name2=time().rand(100,999).'2'.'.jpg';
	$file_name3=time().rand(100,999).'3'.'.jpg';
	$file_name4=time().rand(100,999).'4'.'.jpg';
	$upfile1='./uploads/'.$file_name1;
	$upfile2='./uploads/'.$file_name2;
	$upfile3='./uploads/'.$file_name3;
	$upfile4='./uploads/'.$file_name4;
	switch($info[2])
	{
		case 1:$img=imagecreatefromgif($old_src); break;
		case 2:$img=imagecreatefromjpeg($old_src); break;
		case 3:$img=imagecreatefrompng($old_src); break;
		default:return false;
	}
	$x=intval($_POST['x']);
	$y=intval($_POST['y']);
	$h=intval($_POST['h']);
	$w=intval($_POST['w']);
	
	$new_img=imagecreatetruecolor($w, $h);
	imagecopyresampled($new_img, $img, 0, 0, $x, $y, $w, $h, $w, $h);
	//imagejpeg($new_img, $upfile, $rate);
	$new_img1=imagecreatetruecolor(100, 100);
	imagecopyresampled($new_img1, $new_img, 0, 0, 0, 0, 100, 100, $w, $h);
	imagejpeg($new_img1, $upfile1, $rate);
	
	$new_img2=imagecreatetruecolor(200, 200);
	imagecopyresampled($new_img2, $new_img, 0, 0, 0, 0, 200, 200, $w, $h);
	imagejpeg($new_img2, $upfile2, $rate);
	
	$new_img3=imagecreatetruecolor(300, 300);
	imagecopyresampled($new_img3, $new_img, 0, 0, 0, 0, 300, 300, $w, $h);
	imagejpeg($new_img3, $upfile3, $rate);
	
	$new_img4=imagecreatetruecolor(400, 400);
	imagecopyresampled($new_img4, $new_img, 0, 0, 0, 0, 400, 400, $w, $h);
	imagejpeg($new_img4, $upfile4, $rate);
	
	imagedestroy($img);
	imagedestroy($new_img);
	imagedestroy($new_img1);
	imagedestroy($new_img2);
	imagedestroy($new_img3);
	imagedestroy($new_img4);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="./styles/css/common.css" rel="stylesheet" type="text/css" />
<link href="./styles/css/index.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://baby.ci123.com/yunqi/styles/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript" src="http://file2.ci123.com/ast/js/jquery_172.js"></script>
<script src="http://baby.ci123.com/yunqi/styles/jquery.Jcrop.js" type="text/javascript"></script>
<title>首页</title>
<style>
.tip{display:none;position:absolute;width:300px;height:30px;line-height:30px;color:#fff;font-size:20px;font-weight:bold;border-radius:6px;background:#FFAA25;text-align:center;}
.dbl{position:absolute;}
</style>
</head>
<body>
<div class="main">
	<form action="" name="myform" method="post">
		<img id="pic" src="./uploads/sunset.jpg" width="800" height="600" />
		<input type="hidden" name="p" id="p" value="./uploads/sunset.jpg" />
		<input type="hidden" name="x" id="x" value="" />
		<input type="hidden" name="y" id="y" value="" />
		<input type="hidden" name="w" id="w" value="" />
		<input type="hidden" name="h" id="h" value="" />
		<input type="submit" name="sub" value="提交" />
		<input type="button" value="刷新" onclick="refresh()" />
		<div class="dbl" id="dbl" ondblclick="subform()"></div>
	</form>
</div><!--main-->
<div class="tip" id="tip"></div>
<script type="text/javascript">
function subform()
{
	alert(123);
}
//初始化截图
function init()
{	
	$('#pic').Jcrop({
		onChange: updatePreview,
		onSelect: updatePreview,
		borderOpacity:0.1,
		bgColor:'black',
		allowResize:true,
		allowSelect:false, 
		aspectRatio:1/1,
		setSelect: [0,0,400,400],
		bgOpacity:0.3
	  })
}

//拖动矩形框事件
function updatePreview(c)
{
  if (parseInt(c.w) > 0)
  {	
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
	$("#dbl").html().css({"top":c.x,"left":c.y,"width":c.w,"height":c.h}).show();
  }
}

function refresh()
{	
	var s = "刷新中，请稍等.....";
	var w=$("#tip").width();
	var h=$("#tip").height();
	var ww=$(window).width();
	var wh=$(window).height();
	var dh=$(document).height();
	var st=$(document).scrollTop();//scrollTop() 方法返回或设置匹配元素的滚动条的垂直位置。
	var top=(wh-h)/2+st;
	var left=(ww-w)/2;
	$("#tip").html(s).css({"top":top,"left":left,,}).show();
	window.location.reload();
}

window.onload=function(){ 	
	window.setTimeout("init()",1);
}
</script>
</body>
</html>