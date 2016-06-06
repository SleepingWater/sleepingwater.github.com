<?php
session_start();
include_once('../global/config.php');
if(isset($_SESSION['nick'])){
	$nick=$_SESSION['nick'];
	unset($_SESSION['nick']);
}else{
	if($_SESSION['from'])
	{
	echo "<script>window.location.href='index.php?via=1';</script>";
	die();
	}else{
	echo "<script>window.location.href='index.php';</script>";
	die();
	}
}
$uid=0;
if($_SESSION['from'])
{
$uid=11;
}
$agent=$_SERVER['HTTP_USER_AGENT'];
$via='qq';
if(strpos($agent,'MicroMessenger'))
{
        $via='wx';
}
if(strpos($agent,"iPhone") || strpos($agent,"iPad"))
{
        $os='ios';
        if($via=='wx')
        {
                $appurl="window.location='http://a.app.qq.com/o/simple.jsp?pkgname=com.ci123.pregnancywap&g_f=991653'";
        }else
        {
                $appurl="window.location='http://baby.ci123.com/yunqi/m/down.php'";
        }
        $down_msg='【在Safari中打开】';
}else
{
        $os='android';//安卓
        $down_msg='【在浏览器中打开】';
        if($via=='wx')
        {
        $appurl='wShare(2);';
        }else
        {
                $appurl="window.location='http://baby.ci123.com/yunqi/m/down.php?from=weixin_act'";
        }
}
if($uid==0)
{
if(!strpos($agent,'MicroMessenger') && $os=='android')
{
        //微信安卓，浏览器打开
        header("location:http://baby.ci123.com/yunqi/m/down.php?from=weixin_act");die();
}
}
$result = getResult($nick);//生成图片和文案
$result['title']='测测你宝宝考上哪所大学';//标题（必须）36个汉字(拼接到微博)
$result['url']='baby.ci123.com/weixin/college/';//分享的链接（必须）
function getResult($nick){//获取结果
	$word1 = array(
	'考上了哈弗大学，没错，你成为美国八任总统的校友！',
	'考上了斯坦福大学，你完成了周小栀十年的斯坦福梦想！',
	'考上了剑桥大学，徐志摩内心的康桥，轻轻的我走了，正如我轻轻的来！',
	'考上了麻省理工，世界理工大学之最，78位诺贝尔奖，同学都想抱我大腿！',
	'考上了耶鲁大学，据说耶鲁的图书馆会让你的走路姿势都不一样了！',
	'考上了哥大，奶茶妹妹、王二哥、钢琴家云卷卷，鸡冻啊！',
	'考上了伦敦大学，是皇宫、是城堡、是学子的天堂！',
	'考上芝加哥大学，如果可以请在这个礼堂办婚礼，顺便邀请89位诺贝尔奖做宾客！',
	'考上了东京大学，亚洲最高学府，升职加薪，走上人生的巅峰！',
	'考上了清华，荷塘月色，你将作为清华学子改写下一个情人坡故事！',
	'考上了北大，有燕园之美，有公主楼，更有我天朝的文化气息！',
	'考上了武大，落樱缤纷，美女妖娆，让你目不暇接！',
	'考上了厦大，依山伴海，中国最美丽最浪漫的校园！',
	'考上了中山大学，建筑最具中国古典特色美，学风气息浓厚！',
	'考上了深圳大学，你有个牛逼的校友叫马化腾！',
	'考上了南京大学，大哉一诚天下动，学子遍布全中国',
	'考上了港大，浓郁的欧洲文化气息，丰厚的奖学金，无法拒绝！',
	'考上了中国人民大学，好好的做人民公仆！',
	'考上了复旦大学，百年复旦，震欧铄美声名满！'
	);
	$word2=array(
	'考上了哈弗大学，将成为美国8任总统的校友！',
	'考上了自由之风劲吹的斯坦福大学！',
	'考上了剑桥大学，轻轻地走，轻轻地来！',
	'考上了世界理工大学之最的麻省理工！',
	'考上了全美历史第三悠久的耶鲁大学！',
	'考上了哥伦比亚大学，这里有奶茶妹妹哟！',
	'考上了学子的天堂-伦敦大学！',
	'考上了有89位诺贝尔奖获得者的芝加哥大学！',
	'考上了亚洲最高学府-东京大学！',
	'考上了清华！荷塘月色，写下一个情人坡故事！',
	'考上了浓郁天朝文化气息的北大！',
	'考上了落樱缤纷、美女妖娆的武大！',
	'考上了中国最美丽最浪漫的厦大！',
	'考上了最具中国古典特色美的中山大学！',
	'考上了深圳大学，你有一个牛逼校友马化腾！',
	'考上了大哉一诚天下动的南京大学！',
	'考上了奖学金多到无法拒绝的港大！',
	'考上了中国人民大学，好好的做人民公仆！',
	'考上了复旦大学，百年复旦，震欧铄美声名满！'
	);
	$key = abs(crc32($nick)%count($word1));
	$result['pic'] = 'http://baby.ci123.com/weixin/college/images/'.$key.'.jpg';	
	//$result['pic'] = 'http://local.ci123.com/baby/weixin/score/images/'.$key.'.jpeg';
	$result['comment1'] = "‘".$nick."’".$word1[$key];	
	$result['comment'] = "‘".$nick."’".$word2[$key]; 
	return $result;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include_once('../global/meta.php');?>
<title><?php echo $result['comment'];?></title>
<style type="text/css">
body{background:#333436;}
.main{width:100%;text-align:center;}
.main .head{height:360px;width:100%;overflow:hidden;text-align:center;}
.main .none{display:none;}
.main .show{display:inline;}
.main .showT{color:#fff;font-size:1.3em;font-weight:bold;line-height:20px;padding:5px 0;}
.loading{display:none;width:200px;color:#2b2b2b;padding:30px 40px 56px;background:#FFF;margin:-150px auto;}
.load1{float:left;width:200px;height:12px;overflow:hidden; padding:1px;border:#8FCC52 1px solid;}
.load2{background:#8FCC52;height:12px;}
.txt{text-align:center;clear:both;float:left; width:200px;padding-top:12px;}
/*.sharebt{width:160px;height:40px;margin:20px auto;line-height:40px;text-align:center; color:#FFFFFF;font-size:16px;background:#4a9fef; border-bottom:#2678c4 3px solid;border-radius:5px;}*/
.sharebt{margin:10px 0;}

.sbgshow{display:block;position:fixed;top:0;left:0;width:100%;height:700px;text-align:center;color:#fff;font-size:26px;line-height:1.7em;background:rgba(0,0,0,0.85);}
.sbgshow .arron{ position:absolute;top:8px;right:8px;width:100px;height:100px;background:url(http://baby.ci123.com/yunqi/m/weixin/images/arron.png) no-repeat; background-size:100px 100px;}
.sbgshow p{padding-top:78px;}
.sbg{display:none;position:fixed;top:0;left:0;width:100%;height:700px;text-align:center;color:#fff;font-size:26px;line-height:1.7em;background:rgba(0,0,0,0.85);}
.sbg .arron{ position:absolute;top:8px;right:8px;width:100px;height:100px;background:url(http://baby.ci123.com/yunqi/m/weixin/images/arron.png) no-repeat; background-size:100px 100px;}
.sbg p{padding-top:78px;}
/**添加下部的内容
*/
.sbg2{position:fixed;z-index:999;top:0;left:0;width:100%;height:700px;color:#222;line-height:1.7em;display:none;background:rgba(0,0,0,0.8);}
.sbg2 .t{margin:120px 24px 0 24px;padding:12px;font-size:14px;line-height:1.5em;background:#fff;}
.sbg2 .t span{color:#f66;font-size:16px;}
.sbg2 .j2{height:148px;margin-right:38px;background:url(http://baby.ci123.com/yunqi/m/images/j2.png) top right no-repeat;background-size:80px 148px;}
.app2{position:fixed;z-index:1000;left:0;bottom:0; width:100%;padding:5px 12px;color:#fff;background:rgba(0,0,0,0.5);}
.app2 .a{font-size:18px;font-weight:bold;padding-bottom:4px;}
.app2 .b{font-size:10px;}
.app2 .load{float:left;background:#f66;color:#fff;font-size:16px;margin-top:4px;padding:7px 14px;border-radius:5px;}
</style>
</head>
<body>
<div class="main">
	<div class="head">
		<div class="none" id="rs2"><?php echo $result['comment1'];?></div><br />
		<img class="none" id="rs" src="<?php echo $result['pic'];?>" width="250" height="250" onclick="shareWx()" />
	</div><!--/head-->
    <div class="loading" id="loading">
        <div class="load1">
            <div class="load2" id="load2"></div>
        </div>
        <div class="txt" id="loadtxt">正在分析你的个人信息</div>
    </div>
    <div id="sharebt" class="none" onclick="shareWx()"><img src="../images/share1.png" width="243" height="59" /></div>
    <?php if($uid<1){?>
<div class="app2" onClick="<?php echo $appurl;?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="58"><img src="http://baby.ci123.com/yunqi/m/images/96.png" width="42" height="42" /></td>
		<td><div class="a">妈妈社区</div>
			<div class="b">怀孕、育儿交流社区</div>
		</td>
		<td valign="top"><div class="load">下载妈妈社区</div></td>
	  </tr>
	</table>
</div>
	<?php }?>
</div><!--/main-->
<div id="sbg" class="sbg">
	<div class="arron"></div>
	<p id="msg1">请点击右上角<br />点击【分享到朋友圈】<br />让更多朋友来测试吧！</p>
	<p id="msg2">请点击右上角<br /><?php echo $down_msg;?><br />安装《妈妈社区》App。</p>
</div>

<script type="text/javascript">
var txt=new Array(
	'正在分析你的性格','正在分析你的前世','正在分析你的今世','正在分析你的情感','正在分析你的生活'
);
var at;
var at2;
var num=0;
var os="<?php echo $os;?>";
var uid="<?php echo $uid; ?>";
var color=new Array('009898','FE8060','9832CB','9B9B47','CB326C','A1A31A');
function goDown()
{
document.getElementById("msg").innerHTML='点击右上角<br />选择<?php echo $down_msg;?>，安装《妈妈社区》App。';
wShare(1);
}


window.onload=function()
{
	var w=10;
	var bw=document.body.clientWidth;
	var bh=window.screen.availHeight;
	bw=(bw-344)/2;

	//alert(navigator.userAgent);
	if(navigator.userAgent.indexOf("MSIE 6.0")!=-1)
	{
		//document.getElementById("posbg").style.height='660px';
	}
	document.getElementById("loading").style.left=bw+'px';
	document.getElementById("loading").style.display='block';
	at=setInterval(function(){
		document.getElementById("load2").style.width=w+'px';
		if(w>=200)
		{
			clearInterval(at);
			//document.getElementById("posbg").style.display='none';
			document.getElementById("loading").style.display='none';
		}
		w+=2;
	},18);
	at2=setInterval(function(){
		document.getElementById("loadtxt").style.color='#'+color[num];
		document.getElementById("loadtxt").innerHTML=txt[num];
		if(num==4)
		{
			clearInterval(at2);
			document.getElementById("rs").className="show";
			document.getElementById("rs2").className="showT";
			document.getElementById("sharebt").className="sharebt";
		}
		num++;
	},500);
	if(uid<1)
	{
		//setTimeout(function(){document.getElementById('ads').className='show';},3000);
	}
}

function shareWx()
{
	if(uid<1)
	{
		wShare(1);
	}else{
		if(os=='ios')
		{
			IosShareToWX(1);
		}else if(os=='android'){
			AndriodShareToWX(0);
		}
	}
}

function IosShareToWX(type)
{
	if(type == 0){
		window.location = "ios:wxsession:<?php echo $result['title'];?>||<?php echo $result['comment'];?>||<?php echo $result['url'];?>||<?php echo str_replace('http://','',$result['pic']);?>||";
	}else if(type == 1){
		window.location = "ios:wxtimeline:<?php echo $result['comment'];?>||<?php echo $result['comment'];?>||<?php echo $result['url'];?>||<?php echo str_replace('http://','',$result['pic']);?>||4280000";
	}else if(type == 2){
		window.location = "ios:qq:<?php echo $result['title'];?>||<?php echo $result['comment'];?>||<?php echo $result['url'];?>||<?php echo $result['pic'];?>||";	
	}else if(type == 3){
		window.location = "ios:qzone:<?php echo $result['title'];?>||<?php echo $result['comment'];?>||<?php echo $result['url'];?>||<?php echo $result['pic'];?>||";
	}
}

function wShare(t)
{
	document.getElementById("sbg").className="sbgshow";
	if(t==1)
	{
		document.getElementById("msg1").style.display="";
		document.getElementById("msg2").style.display="none";
	}else{
		document.getElementById("msg2").style.display="";
		document.getElementById("msg1").style.display="none";
	}
	setTimeout(function(){document.getElementById("sbg").className="sbg";},9000);
}

function AndriodShareToWX(type)
{
	var title="<?php echo $result['comment'];?>";
    var msg = "<?php echo $result['comment'];?>";
    var url = "<?php echo $result['url'];?>";
    var pic_url = "<?php echo $result['pic'];?>";
    var id='4280000';
	if(type==1){
    		window.posts.shareToWXSession(title,msg,url,pic_url,id);
	}else{
		window.posts.shareToWXTimeLine(title,msg,url,pic_url,id);
	}
}
</script>
<div style="display:none;"><script type="text/javascript" src="http://js.tongji.linezing.com/3458928/tongji.js"></script></div>
</body>
</html>
