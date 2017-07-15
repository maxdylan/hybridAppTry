<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Config.php";
require_once 'log.php';

$imsi=$_GET['imsi'];
if($imsi==null){
	echo '';
	return ;
}

//{
//"appid":"fa8fsdafs0as",
//"token":"D3IF6D6SA7EFSFWEDIEREFA3SDF2F5DA",
//"dt":"1427093913",
//"imsi":"460016414701020"
//}
if(strlen($imsi)==13){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"card_number":"%s"}';
}else if(strlen($imsi)==15){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"imsi":"%s"}';
}else if(strlen($imsi)==19){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"iccid":"%s"}';
}else{
	echo '{"code":"01"}';
	return ;
}

//$jsonTmp='{
//"appid":"%s",
//"token":"%s",
//"dt":"%s",
//"imsi":"%s"
//}';


$dt=time();
$token=strtoupper(md5(WxPayConfig::CARDAPPID.WxPayConfig::CARDAPPSECRET.$dt));
$json=sprintf($jsonTmp,WxPayConfig::CARDAPPID,$token,$dt,$imsi);
$url='http://rxcall.cn/if/cardinfo';
$result=http_post_json($url, $json);
if(count($result)==2){
	if($result[0]==200){
		echo $result[1];
	}else{
		echo '';
	}
}else{
	echo '';
}



function http_post_json($url, $jsonStr)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charset=utf-8',
      'Content-Length: ' . strlen($jsonStr)
	)
	);
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	return array($httpCode, $response);
}