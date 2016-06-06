<?php
/*
	抓取维基百科简介数据
*/

/*
$curl = curl_init();
// 设置你需要抓取的URL
curl_setopt($curl, CURLOPT_URL, 'http://www.baidu.com');
// 设置header
curl_setopt($curl, CURLOPT_HEADER, 1);
// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 运行cURL，请求网页
$data = curl_exec($curl);
// 关闭URL请求
curl_close($curl);
// 显示获得的数据
var_dump($data);
*/
$url="http://zh.wikipedia.org/wiki/小炒肉";
$content=curl($url);
$content=preg_replace("/(\r|\n|\t)/","",$content);
$n=preg_match('/<div id="mw-content-text"(.*)<div class="printfooter">/isU',$content,$arr);
$content=$arr[0];
preg_match_all('/<p>(.*)<\/p>/isU',$content,$arr);
$baike="";
if($arr[1])
{
	foreach($arr[1] as $k=>$v)
	{
		$baike.=strip_tags($v);
	}	
}
echo $baike;

function curl($url){//curl模拟浏览器方式获取图片
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,20);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	$img = curl_exec($ch); 
	return $img;
}
?>