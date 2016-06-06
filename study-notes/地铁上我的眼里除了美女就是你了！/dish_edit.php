<?php 
include_once('inc/global.php');
$ms=new Mysqls();
$cpage="dish";

//抓图
if($_POST['sub'])
{
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$dishname=trim($_POST['dishname']);	
	$stage_id=isset($_POST['stage_id'])?intval($_POST['stage_id']):0;
	$time_unit=isset($_POST['time_unit'])?intval($_POST['time_unit']):0;
	$content=trim($_POST['content']);
	$tips=trim($_POST['tips']);
	$taste=trim($_POST['taste']);//口味
	$method=trim($_POST['method']);//工艺
	$eff=$_POST['eff'];
	$dated=date("Y-m-d H:i:s");
	if($eff)
	{
		$effect_ids=implode(',',$eff);
	}else
	{
		$effect_ids='';
	}
	if(!$id)
	{
		echo "error";
		die();
	}		
	$p=array(
		'title'=>$dishname,
		'content'=>$content,
		'stage_id'=>$stage_id,
		'time_unit'=>$time_unit,
		'tips'=>$tips,
		'effect_ids'=>$effect_ids,
		'pass'=>1,
		'state'=>1
	);
	
	//图片
	if($_POST['picsave'])
	{
		$x=$_POST['x'];
		$y=$_POST['y'];
		$pic=$_POST['pic'];
		$pic=jcrop($id,$pic,$x,$y,620,414);
		if($pic)
		{
			$p['pic']=$pic;
		}
	}
	$ms->update('cf_dish','id='.$id,$p);
	$ms->update('cf_cate_dish','dish_id='.$id,array('state'=>1,'pass'=>1,'dated'=>$dated),'');
	//先删除菜的标签
	$sql="delete from `cf_good_dish` where dish_id={$id} limit 10";
	$ms->query($sql);
	if($taste)
	{
		$ms->insert('cf_good_dish',array('dish_id'=>$id,'title'=>'口味','content'=>$taste));
	}
	if($method)
	{
		$ms->insert('cf_good_dish',array('dish_id'=>$id,'title'=>'工艺','content'=>$method));
	}
	header("Location: dish.php?cate_id=".$_POST['cid']);
	die();
}

//列表
$id = isset($_GET['id'])? intval($_GET['id']) : 0;
$cid = isset($_GET['cid'])? intval($_GET['cid']) : 0;
if(!$id)
{
	echo "error";
	die();
}
$sql="SELECT * FROM `cf_dish`  where id={$id} limit 1";
$data=$ms->getRow($sql);
if($data['finish']<2)
{
	httpPost('http://192.168.0.202/cf/xia5.php','id='.$id);
	sleep(1);
	header("Location: dish_edit.php?id=".$id."&cid=".$cid);
	die();
}
//功效数组
$eff=explode(",",$data['effect_ids']);

$sql="SELECT cate_id FROM `cf_cate_dish`  where dish_id={$id} and state=1 ";
$cate_data=$ms->getRows($sql);
foreach($cate_data as $k=>$v)
{
	$sql="SELECT title FROM `cf_cate` where state=1 and id={$v['cate_id']} limit 1";
	$tmp=$ms->getRow($sql);
	$cate_data[$k]['title']=$tmp['title'];
}

//做法
$sql="SELECT * FROM `cf_use_dish`  where dish_id={$id}";
$use_data=$ms->getRows($sql);

//步骤
$sql="SELECT * FROM `cf_stage_dish`  where dish_id={$id}";
$stage_data=$ms->getRows($sql);

$sql="SELECT * FROM `cf_good_dish` where dish_id={$id}";
$good_data=$ms->getRows($sql);
$good_arr=array();
foreach($good_data as $v)
{
	$good_arr[]=$v['content'];
}

function httpPost($url,$vars ='') {//模拟post请求发送数据
	if (!function_exists('curl_init')) {//curl不能使用则通过file_get_contents抓取
		$url = $url.$vars;
		$data = '';
		for($i=0;$i<3;$i++){
			$data = file_get_contents($url);
			if($data){
				return $data;
			}
		}
		return $data;
	}
	$ch = curl_init();//执行POST请求
	curl_setopt($ch, CURLOPT_URL, $url );
	curl_setopt($ch, CURLOPT_POST, 1 );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	curl_close( $ch );
	if($data){
		return $data;
	}
	return false;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" rev="stylesheet" href="style/style.css" type="text/css"/>
<link rel="stylesheet" href="http://baby.ci123.com/yunqi/styles/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript" src="http://file2.ci123.com/ast/js/jquery_172.js"></script>
<script src="http://baby.ci123.com/yunqi/styles/jquery.Jcrop.js" type="text/javascript"></script>
<title>菜列表</title>
<style type="text/css">
.main .tablist label{color:#666;font-size:12px;}
.tip{display:none;position:absolute;width:300px;height:30px;line-height:30px;color:#fff;font-size:20px;font-weight:bold;border-radius:6px;background:#FFAA25;text-align:center;}
.main .tablist .pic{max-width:100px;}
.main .txt{width:200px;padding-left:5px;line-height:28px;height:28px;}
.main  .time{width:150px;}
.main .tabform .cspan{color:#AA8B6B;}
.main .tabform .use{border-collapse:collapse;border-spacing:0;}
.main .tabform .use .name{border:1px solid #EBE9E7;background-color:#F7F5F3;color:#513131;font-weight:bold;padding-left:20px;text-align:left;width:280px;}
.main .tabform .use .unit{border:1px solid #EBE9E7;}
.main .tabform .stage td{border:0;}
.main .tabform .stage .no{font-weight:bold;color:#C8BEAC;font-size:24px;}
.main .tabform .sel{padding:5px;}
.main .tabform .gy{padding:3px;margin-right:10px;display:inline;}
.main .tabform .shitu a{font-weight:bold;margin-right:5px;}
.main .tabform .shitu span{color:#666;}
.main .tabform label{color:#666666;font-size:16px;}
.main .mcontent .command{position:fixed;top:100px;right:50px;}
</style>
</head>
<body>
<div class="main">
	<?php include_once('./global/nav.php');?>
		<div class="mcontent">
		<form name="myform" action="" method="post" onsubmit="return chkForm(this);" enctype="multipart/form-data">
			<table id="tabform" width="100%" border="0" cellspacing="0" cellpadding="0" class="tabform">
				<tr>
					<td class="tright">菜名：</td>
					<td><input type="text" name="dishname" id="dishname" value="<?php echo $data['title'];?>" class="txt" /></td>
				</tr>
				<tr>
					<td class="tright">分类：</td>
					<td><span class="cspan">
						<?php 
						foreach($cate_data as $k=>$v)
						{
							if($k)
							{
								echo " 、";
							}
							echo $v['title'];
						}
						?>
					</span></td>
				</tr>
				<tr>
					<td class="tright">图片：</td>
					<td>
						<div class="shitu">
							<table width="800">
								<tr>
									<td>
									<?php if(strstr($data['pic'],'http://')){?>			
									<a target="_blank" href="http://image.baidu.com/i?word=<?php echo $data['title']?>&ie=utf-8">百度搜图</a>
									<a target="_blank" href="http://shitu.baidu.com/i?rn=10&ftn=indexstu&ct=1&tn=shituresult&objurl=<?php echo urlencode($data['pic']);?>">百度识图</a>
									<?php 
										$stream=curl($data['pic']);
	    								$info = getimagesize('data://image/jpeg;base64,'. base64_encode($stream));
									}else{$info=getimagesize($data['pic']);}
									echo "<span>图片信息：".$info[0]."*".$info[1]."</span>";
									?>
									</td>
									<td><?php include_once('up.php');?></td>
									<td>网络抓图：<input type="text" name="weburl" id="weburl" style="width:230px;height:23px;line-height:23px;" /><input type="button" value="确定" onclick="getPic()" style="height:30px;line-height:30px;" /></td>
								</tr>
							</table>			
							<img src="<?php echo $data['pic'];?>" id="target" />		
						</div>
					</td>
				</tr>
				<tr>
	                <td class="tright">阶段：</td>
	                <td>					
		                <select id="stage_id" name="stage_id" onchange="changeS(this.value,0)" class="sel">
							<option value="0" <?php if($data['stage_id']==0){echo "selected";}?>>全年龄</option>
							<option value="1" <?php if($data['stage_id']==1){echo "selected";}?>>备孕</option>
							<option value="2" <?php if($data['stage_id']==2){echo "selected";}?>>孕早期</option>
							<option value="3" <?php if($data['stage_id']==3){echo "selected";}?>>孕中期</option>
							<option value="4" <?php if($data['stage_id']==4){echo "selected";}?>>孕晚期</option>
							<option value="5" <?php if($data['stage_id']==5){echo "selected";}?>>月子餐</option>
							<option value="6" <?php if($data['stage_id']==6){echo "selected";}?>>2-6个月</option>
							<option value="7" <?php if($data['stage_id']==7){echo "selected";}?>>6-12个月</option>
						</select>	
						<select  id="time_unit" name="time_unit" style="display:none;" class="sel"></select>	
					</td>
                </tr>
                <tr>
					<td class="tright">标签：</td>
					<td>					
						<select name="taste" class="gy">
							<option value="">--口味--</option>
							<option value="微辣" <?php if(in_array('微辣',$good_arr)){echo "selected";}?>>微辣</option>
							<option value="中辣" <?php if(in_array('中辣',$good_arr)){echo "selected";}?>>中辣</option>
							<option value="爆辣" <?php if(in_array('爆辣',$good_arr)){echo "selected";}?>>爆辣</option>
							<option value="咸鲜" <?php if(in_array('咸鲜',$good_arr)){echo "selected";}?>>咸鲜</option>
							<option value="清淡" <?php if(in_array('清淡',$good_arr)){echo "selected";}?>>清淡</option>								
							<option value="甜" <?php if(in_array('甜',$good_arr)){echo "selected";}?>>甜</option>
							<option value="酸辣" <?php if(in_array('酸辣',$good_arr)){echo "selected";}?>>酸辣</option>
							<option value="家常味" <?php if(in_array('家常味',$good_arr)){echo "selected";}?>>家常味</option>
						</select>						
						<select name="method" class="gy">
							<option value="">--工艺--</option>
							<option value="炒" <?php if(in_array('炒',$good_arr)){echo "selected";}?>>炒</option>
							<option value="蒸" <?php if(in_array('蒸',$good_arr)){echo "selected";}?>>蒸</option>
							<option value="煮" <?php if(in_array('煮',$good_arr)){echo "selected";}?>>煮</option>
							<option value="炖" <?php if(in_array('炖',$good_arr)){echo "selected";}?>>炖</option>
							<option value="煎" <?php if(in_array('煎',$good_arr)){echo "selected";}?>>煎</option>
							<option value="炸" <?php if(in_array('炸',$good_arr)){echo "selected";}?>>炸</option>
							<option value="凉拌" <?php if(in_array('凉拌',$good_arr)){echo "selected";}?>>凉拌</option>
						</select>						
					</td>
				</tr>
				<tr>
					<td class="tright">功效：</td>
					<td>
						<input type="checkbox" value="1" name="eff[]" id="eff_1" <?php if(in_array(1,$eff)){echo "checked";}?>>
						<label for="eff_1">补血</label>
						<input type="checkbox" value="2" name="eff[]" id="eff_2" <?php if(in_array(2,$eff)){echo "checked";}?>>
						<label for="eff_2">安胎</label>
						<input type="checkbox" value="3" name="eff[]" id="eff_3" <?php if(in_array(3,$eff)){echo "checked";}?>>
						<label for="eff_3">降血压</label>
						<input type="checkbox" value="5" name="eff[]" id="eff_5" <?php if(in_array(5,$eff)){echo "checked";}?>>
						<label for="eff_5">降血糖</label>
						<input type="checkbox" value="9" name="eff[]" id="eff_9" <?php if(in_array(9,$eff)){echo "checked";}?>>
						<label for="eff_9">清热解毒</label>
						<input type="checkbox" value="11" name="eff[]" id="eff_11" <?php if(in_array(11,$eff)){echo "checked";}?>>
						<label for="eff_11">营养不良</label>
						<input type="checkbox" value="7" name="eff[]" id="eff_7" <?php if(in_array(7,$eff)){echo "checked";}?>>
						<label for="eff_7">便秘</label>
						<br>
						<input type="checkbox" value="13" name="eff[]" id="eff_13" <?php if(in_array(13,$eff)){echo "checked";}?>>
						<label for="eff_13">通乳</label>		
						<input type="checkbox" value="8" name="eff[]" id="eff_8" <?php if(in_array(8,$eff)){echo "checked";}?>>
						<label for="eff_8">补钙</label>
						<input type="checkbox" value="4" name="eff[]" id="eff_4" <?php if(in_array(4,$eff)){echo "checked";}?>>
						<label for="eff_4">去水肿</label>
						<input type="checkbox" value="6" name="eff[]" id="eff_6" <?php if(in_array(6,$eff)){echo "checked";}?>>
						<label for="eff_6">治感冒</label>
						<input type="checkbox" value="10" name="eff[]" id="eff_10" <?php if(in_array(10,$eff)){echo "checked";}?>>
						<label for="eff_10">产后调理</label>
						<input type="checkbox" value="12" name="eff[]" id="eff_12" <?php if(in_array(12,$eff)){echo "checked";}?>>
						<label for="eff_12">健脾开胃</label>
					</td>
				</tr>
				<tr>
					<td class="tright">简介：</td>
					<td><textarea style="width:850px;height:140px;" name="content"><?php echo $data['content'];?></textarea></td>
				</tr>		
				<tr>
					<td class="tright">用料：</td>
					<td>
						<table class="use">	
							<?php 
							foreach($use_data as $v){?>
							<tr><td class="name"><?php echo $v['title'];?></td><td class="unit"><?php echo $v['unit'];?></td></tr>
							<?php }?>
						</table>					
					</td>
				</tr>
				<tr>
					<td class="tright">做法：</td>
					<td>
						<table width="600" class="stage">	
							<?php 
							foreach($stage_data as $k=>$v){?>
							<tr><td class="no" width="40"><?php echo $k+1;?></td><td width="250" class="stagecon"><?php echo $v['content'];?></td><td><img src="<?php echo $v['pic'];?>" /></td></tr>
							<?php }?>
						</table>					
					</td>
				</tr>				
                <tr>
					<td class="tright">小贴士：</td>
					<td><textarea style="width:850px;height:140px;" name="tips"><?php echo $data['tips'];?></textarea></td>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo $id;?>" />
			<input type="hidden" name="cid" value="<?php echo $cid;?>" />
			<input type="hidden" name="x" id="x" value="" />
			<input type="hidden" name="y" id="y" value="" />
			<input type="hidden" name="w" id="w" value="" />
			<input type="hidden" name="h" id="h" value="" />
			<input type="hidden" name="picsave" id="picsave" value="" />
			<input type="hidden" name="pic" id="pic" value="<?php echo $data['pic'];?>" />
			<div class="command">
			<input type="submit" name="sub" value="保  存" style="padding:8px 25px;" /><br />
			<input type="button" name="del" value="删  除" style="padding:8px 25px;" onclick="deleteDish(<?php echo $id;?>)" />
			</div>
		</form>
	</div><!--/mcontent-->
</div>
<!--main-->
<script>	
var cate_id=<?php echo $cid;?>;
var id=<?php echo $id;?>;
function deleteDish(id)
{
	if(!confirm("确定删除"))
	{
		return false;
	}	
	$.post("sub/dish_sub.php",{act:1,id:id},function(data){
		window.location.href="dish.php?cate_id="+cate_id;
	});	
}

var jcrop_api, boundx, boundy;   
var w=<?php echo intval($info[0]);?>;
var h=<?php echo intval($info[1]);?>;
function init()//初始化裁剪图片
{
	var x=(w-620)/2;
	var x2=x+620;
	var y=(h-414)/2;
	var y2=y+414;
	$('#target').Jcrop({
		onChange: updatePreview,
		onSelect: updatePreview,
		borderOpacity:0.1,
		bgColor:'black',
		allowResize:false,
		allowSelect:false, 
		aspectRatio:620/414,
		setSelect: [0,y,620,y2],
		bgOpacity:0.2,
		keySupport:true,
	  },function(){
		var bounds = this.getBounds();
		boundx = bounds[0];
		boundy = bounds[1];
		jcrop_api = this;
	  });
}

function updatePreview(c)//拖动矩形框事件
{
  if (parseInt(c.w)>0)
  {
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h); 
	$("#picsave").val('1');
  }
};
//网络抓图
function getPic()
{
	var weburl=$("#weburl").val();
	$.getJSON("sub/getwebpic.php",{id:id,weburl:weburl},function(data){
		$("#picsave").val('1');
		var url=data.url;
		var w=data.width;
		var h=data.height;
		$("#target").attr('src',url).css({"width":w,"height":h});
		$("#pic").val(url);
		jcrop_api.destroy();
		init();
	});
}
function chkForm(f)
{
	var w=$('#w').val();
	var h=$('#h').val(); 	
	if(w<620 || h<414)
	{
		alert("图片选定区域信息不符合要求\r\n     宽:"+w+" 高:"+h);
		return false;
	}		
	
}
window.onload=function(){ 
	if(w<620 || h<414)
	{
		$.post("sub/dish_sub.php",{act:1,id:id},function(data){
			alert("图片不符合要求，本道菜被删除");
			window.location.href="dish.php?cate_id="+cate_id;
		});	
	}else
	{		
		window.setTimeout("init()",1);
	}
}
</script>
</body>
</html>
