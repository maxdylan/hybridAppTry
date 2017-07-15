<?php
ini_set('date.timezone','Asia/Shanghai');
libxml_disable_entity_loader(false);
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

Log::DEBUG("prepare to generate qrcode");

$productid=$_GET['productid'];

if($productid==''){
	echo '';
	return ;
}

Log::DEBUG("productid: ".$productid);

//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
//A711460060009042249
// $notify = new NativePay();
// $url1 = $notify->GetPrePayUrl($productid);
//
//
//
// echo 'http://mirror.micronavi.cn/intercom/php/payindex/qrcode.php?data='.urlencode($url1);

////模式二
///**
// * 流程：
// * 1、调用统一下单，取得code_url，生成二维码
// * 2、用户扫描二维码，进行支付
// * 3、支付完成之后，微信服务器会通知支付成功
// * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
// */
$notify = new NativePay();
// $input = new WxPayUnifiedOrder();
// $input->SetBody("test");
// $input->SetAttach("test");
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
// $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
// $input->SetTotal_fee("1");
// $input->SetTime_start(date("YmdHis"));
// $input->SetTime_expire(date("YmdHis", time() + 600));
// $input->SetGoods_tag("test");
// $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
// $input->SetTrade_type("NATIVE");
// $input->SetProduct_id("123456789");
// $result = $notify->GetPayUrl($input);
// $url2 = $result["code_url"];
$price = "0";
$combname = "套餐名称";
function getBillDetail($channel,$username,$billid){
	$dom = new DOMDocument();
	$dom->load('../../asset/billList.xml');
	$channleList = $dom->getElementsByTagName('channel');
	foreach ($channleList as $key => $value) {
		$_channle = $value->getAttribute('name');
		if($_channle == $channel){
			$billList = $dom->getElementsByTagName('bill');
			foreach ($billList as $k => $v) {
				$node = $v->getElementsByTagName('billId');
				$nodeV = $node->item(0)->nodeValue;
				if($nodeV == $billid){
					global $combname,$price;
					$node = $v->getElementsByTagName('billName');
					$combname = $node->item(0)->nodeValue;
					$node = $v->getElementsByTagName('billPrice');
					$price = $node->item(0)->nodeValue;
					Log::DEBUG("in foreach price: ".$price."   combname: ".$combname);
					break;
				}
			}
			break;
		}
	}
}

//统一下单
$input = new WxPayUnifiedOrder();
//productid = channel##username##billid
$params = explode('@@',$productid);
$channel = $params[0];
$username = $params[1];
$billid = $params[2];
getBillDetail($channel,$username,$billid);
Log::DEBUG("price: ".$price."   combname: ".$combname);
if($price!="0"){
	$input->SetBody($combname);
	$input->SetAttach("套餐购买 ");
	$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
	// $timetmp=date("YmdHis");
	// $timetmp=substr($timetmp, 2);
	// $input->SetOut_trade_no($productid.$timetmp);
	$input->SetTotal_fee($price);
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag($combname);
	$input->SetNotify_url("http://mirror.micronavi.cn/intercompay/php/payindex/notify.php");
	$input->SetTrade_type("NATIVE");
	$input->SetProduct_id($productid);
	$result = $notify->GetPayUrl($input);
	$url2 = $result["code_url"];
}else{
	$input->SetBody("test");
	$input->SetAttach("test");
	$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
	$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
	$input->SetTotal_fee("1");
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag("test");
	$input->SetNotify_url("http://mirror.micronavi.cn/intercompay/php/payindex/notify.php");
	$input->SetTrade_type("NATIVE");
	$input->SetProduct_id("123456789");
	$result = $notify->GetPayUrl($input);
	$url2 = $result["code_url"];
}
echo $url2;
// echo 'http://mirror.micronavi.cn/intercom/php/payindex/qrcode.php?data='.urlencode($url1);
?>
