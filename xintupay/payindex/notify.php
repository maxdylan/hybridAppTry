<?php
//支付完成，给用户充值
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "../lib/WxPay.Config.php";
require_once '../lib/WxPay.Notify.php';
require_once 'mysqlTool.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
		&& array_key_exists("result_code", $result)
		&& $result["return_code"] == "SUCCESS"
		&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess1($data, &$msg)
	{
		//		Log::DEBUG("Notify call back:" . json_encode($data));
		$notfiyOutput = array();

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			$this->sendFail($msg);
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			$this->sendFail($msg);
			return false;
		}
		$this->sendSuccess();

		$jsonTmp='
			{
   			"appid": "%s",
    		"token": "%s",
    		"dt": "%s",
    		"imsi": "%s",
    		"cardtype": "%s",
    		"tid": "%s"
			}';
		$tranid=$data['transaction_id'];
		$tranid='LCXT'.$tranid;
		$trade=$data['out_trade_no'];
		$type=substr($trade, 0,4);
		$imsi=substr($trade, 4,15);
		$cardtype=substr($trade, 19,1);
		$sqlexit='select * from cardpay where tranid=\''.$tranid.'\'';
		$sqlinsert='insert into cardpay(tranid,trade,type,imsi)values(\''.$tranid.'\',\''.$trade.'\',\''.$type.'\',\''.$imsi.'\')';
		$mysqlTool=new MySQLTool();
		$resExit=$mysqlTool->queryexit($sqlexit);

		if(!$resExit){
			$res=$mysqlTool->query($sqlinsert);
			if($cardtype=='f'){
				$i=0;
				for($i=0;$i<3;$i++){
					$dt=time();
					$token=strtoupper(md5(WxPayConfig::CARDAPPID.WxPayConfig::CARDAPPSECRET.$dt));
					$json=sprintf($jsonTmp,WxPayConfig::CARDAPPID,$token,$dt,$imsi,$type,$tranid);
					//					Log::DEBUG($json);
					$arr=$this->http_post_json(WxPayConfig::CARDURL, $json);
					$resjson=json_decode($arr[1]);
					//					Log::DEBUG($arr[0].'---'.count($arr));
					if($resjson->code==0){
						Log::DEBUG('f--'.$imsi."--".$type.'--充值成功');
						break;
					}
					sleep(2);
				}
				if($i==3){
					Log::DEBUG('f--'.$imsi."--".$type.'--未充值成功');
				}
			}else if($cardtype=='y'){
				$j=0;
				for($j=0;$j<3;$j++){
					$ydt=time()*1000;
					$yappid='9B9C420FDC5E5745785702C112F64B0A';
					$yappsecret='9759959F100FDD106548CBC8D3195DA0';
					$ytoken=strtoupper(md5($yappid.$yappsecret.$ydt));
					$ytype=substr($type, 1);
					if($ytype=='218'){
						$ytype='95';
					}else if($ytype=='219'){
						$ytype='110';
					}else if($ytype=='225'){
						$ytype='129';
					}
					$geturl='http://wechat.cxsz.com.cn/WoCarNetwork/api/buyCard.json?appid='.$yappid.'&token='.$ytoken.'&dt='.$ydt.'&mobile='.$imsi.'&cardid='.$ytype.'&tid='.$tranid;
					//$res=file_get_contents($geturl);
					$arr=$this->http_post_json($geturl, '');
					$res=$arr[1];
					Log::DEBUG($ytype.'--->'.$res);
					$resjson=json_decode($res);
					if($resjson->code==0){
						Log::DEBUG('y--'.$imsi."--".$type.'--充值成功');
						break;
					}
					sleep(2);
				}
				if($j==3){
					Log::DEBUG('y--'.$imsi."--".$type.'--未充值成功');
				}
			}else if($cardtype=='m'){
				$k=0;
				$iccid='89860'.$imsi;
				$murl='http://api.ext.m-m10010.com/api/RechargeRenewals2';
				for($k=0;$k<3;$k++){
					if($type=='MM20'){
						$mtype=20;
						$price='1';
					}else if($type=='M101'){
						$mtype=101;
						$price='1';
					}else if($type=='M130'){
						$mtype=130;
						$price='1';
					}else if($type=='M219'){
						$mtype=219;
						$price='1';
					}else if($type=='M215'){
						$mtype=215;
						$price='1';
					}
					$jsonmTmp='{
						"iccid": "%s",
						"packageId": %s,
						"price": %s,
						"transactionId":"%s"
					}';
					$jsonm=sprintf($jsonmTmp,$iccid,$mtype,$price,$tranid);
					$res=$this->http_post_jsonm($url, $jsonStr);
					$resjson=json_decode($arr[1]);
						
					if($resjson->error==0){
						Log::DEBUG('m--'.$iccid."--".$type.'--充值成功');
						break;
					}
					sleep(2);
				}
				if($k==3){
					Log::DEBUG('m--'.$iccid."--".$type.'--未充值成功');
				}
			}else{
				Log::DEBUG('else--'.$imsi."--".$type.'--未充值成功');
			}

		}else{
		}
		return true;
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

	function http_post_jsonm($url, $jsonStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charset=utf-8',
	  'Authorization:D22252BD0A2B83FE58813B48CBB14416',
      'Content-Length: ' . strlen($jsonStr)
		)
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return array($httpCode, $response);
	}
}

//Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle1(false);
