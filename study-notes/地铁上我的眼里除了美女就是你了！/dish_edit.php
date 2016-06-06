<?php 
include_once('inc/global.php');
$ms=new Mysqls();
$cpage="dish";

//ץͼ
if($_POST['sub'])
{
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$dishname=trim($_POST['dishname']);	
	$stage_id=isset($_POST['stage_id'])?intval($_POST['stage_id']):0;
	$time_unit=isset($_POST['time_unit'])?intval($_POST['time_unit']):0;
	$content=trim($_POST['content']);
	$tips=trim($_POST['tips']);
	$taste=trim($_POST['taste']);//��ζ
	$method=trim($_POST['method']);//����
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
	
	//ͼƬ
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
	//��ɾ���˵ı�ǩ
	$sql="delete from `cf_good_dish` where dish_id={$id} limit 10";
	$ms->query($sql);
	if($taste)
	{
		$ms->insert('cf_good_dish',array('dish_id'=>$id,'title'=>'��ζ','content'=>$taste));
	}
	if($method)
	{
		$ms->insert('cf_good_dish',array('dish_id'=>$id,'title'=>'����','content'=>$method));
	}
	header("Location: dish.php?cate_id=".$_POST['cid']);
	die();
}

//�б�
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
//��Ч����
$eff=explode(",",$data['effect_ids']);

$sql="SELECT cate_id FROM `cf_cate_dish`  where dish_id={$id} and state=1 ";
$cate_data=$ms->getRows($sql);
foreach($cate_data as $k=>$v)
{
	$sql="SELECT title FROM `cf_cate` where state=1 and id={$v['cate_id']} limit 1";
	$tmp=$ms->getRow($sql);
	$cate_data[$k]['title']=$tmp['title'];
}

//����
$sql="SELECT * FROM `cf_use_dish`  where dish_id={$id}";
$use_data=$ms->getRows($sql);

//����
$sql="SELECT * FROM `cf_stage_dish`  where dish_id={$id}";
$stage_data=$ms->getRows($sql);

$sql="SELECT * FROM `cf_good_dish` where dish_id={$id}";
$good_data=$ms->getRows($sql);
$good_arr=array();
foreach($good_data as $v)
{
	$good_arr[]=$v['content'];
}

function httpPost($url,$vars ='') {//ģ��post����������
	if (!function_exists('curl_init')) {//curl����ʹ����ͨ��file_get_contentsץȡ
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
	$ch = curl_init();//ִ��POST����
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
<title>���б�</title>
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
					<td class="tright">������</td>
					<td><input type="text" name="dishname" id="dishname" value="<?php echo $data['title'];?>" class="txt" /></td>
				</tr>
				<tr>
					<td class="tright">���ࣺ</td>
					<td><span class="cspan">
						<?php 
						foreach($cate_data as $k=>$v)
						{
							if($k)
							{
								echo " ��";
							}
							echo $v['title'];
						}
						?>
					</span></td>
				</tr>
				<tr>
					<td class="tright">ͼƬ��</td>
					<td>
						<div class="shitu">
							<table width="800">
								<tr>
									<td>
									<?php if(strstr($data['pic'],'http://')){?>			
									<a target="_blank" href="http://image.baidu.com/i?word=<?php echo $data['title']?>&ie=utf-8">�ٶ���ͼ</a>
									<a target="_blank" href="http://shitu.baidu.com/i?rn=10&ftn=indexstu&ct=1&tn=shituresult&objurl=<?php echo urlencode($data['pic']);?>">�ٶ�ʶͼ</a>
									<?php 
										$stream=curl($data['pic']);
	    								$info = getimagesize('data://image/jpeg;base64,'. base64_encode($stream));
									}else{$info=getimagesize($data['pic']);}
									echo "<span>ͼƬ��Ϣ��".$info[0]."*".$info[1]."</span>";
									?>
									</td>
									<td><?php include_once('up.php');?></td>
									<td>����ץͼ��<input type="text" name="weburl" id="weburl" style="width:230px;height:23px;line-height:23px;" /><input type="button" value="ȷ��" onclick="getPic()" style="height:30px;line-height:30px;" /></td>
								</tr>
							</table>			
							<img src="<?php echo $data['pic'];?>" id="target" />		
						</div>
					</td>
				</tr>
				<tr>
	                <td class="tright">�׶Σ�</td>
	                <td>					
		                <select id="stage_id" name="stage_id" onchange="changeS(this.value,0)" class="sel">
							<option value="0" <?php if($data['stage_id']==0){echo "selected";}?>>ȫ����</option>
							<option value="1" <?php if($data['stage_id']==1){echo "selected";}?>>����</option>
							<option value="2" <?php if($data['stage_id']==2){echo "selected";}?>>������</option>
							<option value="3" <?php if($data['stage_id']==3){echo "selected";}?>>������</option>
							<option value="4" <?php if($data['stage_id']==4){echo "selected";}?>>������</option>
							<option value="5" <?php if($data['stage_id']==5){echo "selected";}?>>���Ӳ�</option>
							<option value="6" <?php if($data['stage_id']==6){echo "selected";}?>>2-6����</option>
							<option value="7" <?php if($data['stage_id']==7){echo "selected";}?>>6-12����</option>
						</select>	
						<select  id="time_unit" name="time_unit" style="display:none;" class="sel"></select>	
					</td>
                </tr>
                <tr>
					<td class="tright">��ǩ��</td>
					<td>					
						<select name="taste" class="gy">
							<option value="">--��ζ--</option>
							<option value="΢��" <?php if(in_array('΢��',$good_arr)){echo "selected";}?>>΢��</option>
							<option value="����" <?php if(in_array('����',$good_arr)){echo "selected";}?>>����</option>
							<option value="����" <?php if(in_array('����',$good_arr)){echo "selected";}?>>����</option>
							<option value="����" <?php if(in_array('����',$good_arr)){echo "selected";}?>>����</option>
							<option value="�嵭" <?php if(in_array('�嵭',$good_arr)){echo "selected";}?>>�嵭</option>								
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="����" <?php if(in_array('����',$good_arr)){echo "selected";}?>>����</option>
							<option value="�ҳ�ζ" <?php if(in_array('�ҳ�ζ',$good_arr)){echo "selected";}?>>�ҳ�ζ</option>
						</select>						
						<select name="method" class="gy">
							<option value="">--����--</option>
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="��" <?php if(in_array('��',$good_arr)){echo "selected";}?>>��</option>
							<option value="ը" <?php if(in_array('ը',$good_arr)){echo "selected";}?>>ը</option>
							<option value="����" <?php if(in_array('����',$good_arr)){echo "selected";}?>>����</option>
						</select>						
					</td>
				</tr>
				<tr>
					<td class="tright">��Ч��</td>
					<td>
						<input type="checkbox" value="1" name="eff[]" id="eff_1" <?php if(in_array(1,$eff)){echo "checked";}?>>
						<label for="eff_1">��Ѫ</label>
						<input type="checkbox" value="2" name="eff[]" id="eff_2" <?php if(in_array(2,$eff)){echo "checked";}?>>
						<label for="eff_2">��̥</label>
						<input type="checkbox" value="3" name="eff[]" id="eff_3" <?php if(in_array(3,$eff)){echo "checked";}?>>
						<label for="eff_3">��Ѫѹ</label>
						<input type="checkbox" value="5" name="eff[]" id="eff_5" <?php if(in_array(5,$eff)){echo "checked";}?>>
						<label for="eff_5">��Ѫ��</label>
						<input type="checkbox" value="9" name="eff[]" id="eff_9" <?php if(in_array(9,$eff)){echo "checked";}?>>
						<label for="eff_9">���Ƚⶾ</label>
						<input type="checkbox" value="11" name="eff[]" id="eff_11" <?php if(in_array(11,$eff)){echo "checked";}?>>
						<label for="eff_11">Ӫ������</label>
						<input type="checkbox" value="7" name="eff[]" id="eff_7" <?php if(in_array(7,$eff)){echo "checked";}?>>
						<label for="eff_7">����</label>
						<br>
						<input type="checkbox" value="13" name="eff[]" id="eff_13" <?php if(in_array(13,$eff)){echo "checked";}?>>
						<label for="eff_13">ͨ��</label>		
						<input type="checkbox" value="8" name="eff[]" id="eff_8" <?php if(in_array(8,$eff)){echo "checked";}?>>
						<label for="eff_8">����</label>
						<input type="checkbox" value="4" name="eff[]" id="eff_4" <?php if(in_array(4,$eff)){echo "checked";}?>>
						<label for="eff_4">ȥˮ��</label>
						<input type="checkbox" value="6" name="eff[]" id="eff_6" <?php if(in_array(6,$eff)){echo "checked";}?>>
						<label for="eff_6">�θ�ð</label>
						<input type="checkbox" value="10" name="eff[]" id="eff_10" <?php if(in_array(10,$eff)){echo "checked";}?>>
						<label for="eff_10">�������</label>
						<input type="checkbox" value="12" name="eff[]" id="eff_12" <?php if(in_array(12,$eff)){echo "checked";}?>>
						<label for="eff_12">��Ƣ��θ</label>
					</td>
				</tr>
				<tr>
					<td class="tright">��飺</td>
					<td><textarea style="width:850px;height:140px;" name="content"><?php echo $data['content'];?></textarea></td>
				</tr>		
				<tr>
					<td class="tright">���ϣ�</td>
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
					<td class="tright">������</td>
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
					<td class="tright">С��ʿ��</td>
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
			<input type="submit" name="sub" value="��  ��" style="padding:8px 25px;" /><br />
			<input type="button" name="del" value="ɾ  ��" style="padding:8px 25px;" onclick="deleteDish(<?php echo $id;?>)" />
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
	if(!confirm("ȷ��ɾ��"))
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
function init()//��ʼ���ü�ͼƬ
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

function updatePreview(c)//�϶����ο��¼�
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
//����ץͼ
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
		alert("ͼƬѡ��������Ϣ������Ҫ��\r\n     ��:"+w+" ��:"+h);
		return false;
	}		
	
}
window.onload=function(){ 
	if(w<620 || h<414)
	{
		$.post("sub/dish_sub.php",{act:1,id:id},function(data){
			alert("ͼƬ������Ҫ�󣬱����˱�ɾ��");
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
