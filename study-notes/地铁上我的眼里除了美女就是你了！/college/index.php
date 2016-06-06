<?php
session_start();
$from=$_GET['via'];
if($from)
{
$_SESSION['from']=$from;
}
if(isset($_POST['Submit']) || isset($_POST['Submit_x'])){
	$nick = trim($_POST['nick']);
	$_SESSION['nick'] = $nick;
	header("Location:view.php");
	die();
} 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include_once('../global/meta.php');?>
<title>测测你的宝宝会考上哪所大学-来自妈妈社区</title>
<style type="text/css">
body,ul,li,dl,dt,dd{margin:0;padding:0;}
body{ background:url(../images/bg.png);background-size:16px 7px;padding-bottom:120px;}
.ititle{margin:18px auto;text-align:center;font-weight:bold;font-size:20px;color:#333;}
.face{width:296px;height:251px;margin:18px auto 0 auto;background:url(../images/a.png) no-repeat; background-size:296px 251px;}
.info{ padding-top:135px;}
.info td{ height:40px;}
.sub{ text-align:center; padding-top:24px;}
</style>
</head>

<body>
<form name="myform" method="post" action="" onSubmit="return chkForm(this)">
	<div class="ititle">测测你的宝宝会考上哪所大学</div>
<div class="face">
	<div class="info">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td align="right" width="130">输入名字：</td>
		<td><input type="text" name="nick" id="dd" style="width:100px; padding:8px; line-height:18px;font-size:18px;"  /> </td>
	  </tr>
	</table>
	</div>
</div>
<div class="sub"><input type="image" src="../images/sub2.png"  width="243" height="59" name="Submit" value="提交" /></div>
</form>

<script type="text/javascript">
var pub=false;
function chkForm(f){
	if(pub==false){
                pub=true;
        }else{
                return false;
        }

	var name = f.nick.value;
	name = name.replace(/[ ]/g,"");
	if(name.length<1 || name.length>4){
		alert('请输入1-4个长度的名字');
		f.nick.value=''
		f.nick.focus();
		pub=false;
		return false;
	}else if(name=='请输入你要测试的名字'){
		alert('请输入你要测试的名字');
		f.nick.value='';
		f.nick.focus();
		pub=false;
		return false;
	}
	return true;
}
</script>
<div style="display:none;"><script type="text/javascript" src="http://js.tongji.linezing.com/3458928/tongji.js"></script></div>
</body>
</html>
