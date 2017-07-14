<?php
//根据二维码回调的信息进行下单
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class NativeNotifyCallBack extends WxPayNotify
{
	public function unifiedorder($openId, $productid)
	{
		//统一下单
		$input = new WxPayUnifiedOrder();
		$type=substr($productid, 0,4);
		$imsi=substr($productid, 4,15);
		//		$this->setBody($type);
		if($type=='A012'){
			$input->SetBody("联通2G 6个月");
			$input->SetAttach("套餐购买 ");
			$price='65';
			$combname='联通2G 6个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("6500");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通2G 6个月");
		}else if($type=='A013'){
			$input->SetBody("联通2G 12个月");
			$input->SetAttach("套餐购买 ");
			$price='68';
			$combname='联通2G 12个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("6800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通2G 12个月");
		}else if($type=='A014'){
			$input->SetBody("联通4G 12个月");
			$input->SetAttach("套餐购买 ");
			$price='128';
			$combname='联通4G 12个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("12800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通4G 12个月");
		}else if($type=='A105'){
			$input->SetBody("联通6G 12个月");
			$input->SetAttach("套餐购买 ");
			$price='178';
			$combname='联通6G 12个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("17800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通6G 12个月");
		}else if($type=='A016'){
			$input->SetBody("联通8G 24个月");
			$input->SetAttach("套餐购买 ");
			$price='248';
			$combname='联通8G 24个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("24800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通8G 24个月");
		}else if($type=='Y218'){
			$input->SetBody("2G/年");
			$input->SetAttach("套餐购买 ");
			$price='68';
			$combname='2G/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("6800");
//			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通2G/年");
		}elseif($type=='Y219'){
			$input->SetBody("4G/年");
			$input->SetAttach("套餐购买 ");
			$price='128';
			$combname='4G/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("12800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通4G/年");
		}else if($type=='Y225'){
			$input->SetBody("6G/年");
			$input->SetAttach("套餐购买 ");
			$price='178';
			$combname='6G/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("17800");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通6G/年");
		}else if($type=='Y220'){
			$input->SetBody("音乐畅听/年");
			$input->SetAttach("套餐购买 ");
			$price='259';
			$combname='音乐畅听/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("25900");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通音乐畅听/年");
		}else if($type=='Y221'){
			$input->SetBody("视频尊享/年");
			$input->SetAttach("套餐购买 ");
			$price='599';
			$combname='视频尊享/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
			$input->SetTotal_fee("59900");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通视频尊享/年");
		}else if($type=='M215'){
			$input->SetBody("联通2G 6个月");
			$input->SetAttach("套餐购买 ");
			$price='65';
			$combname='联通2G 6个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
//			$input->SetTotal_fee("6500");
			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通2G 6个月");
		}else if($type=='MM20'){
			$input->SetBody("联通2G 12个月");
			$input->SetAttach("套餐购买 ");
			$price='68';
			$combname='联通2G 12个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
//			$input->SetTotal_fee("6800");
			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通2G 12个月");
		}else if($type=='M101'){
			$input->SetBody("联通4G 12个月");
			$input->SetAttach("套餐购买 ");
			$price='128';
			$combname='联通4G 12个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
//			$input->SetTotal_fee("12800");
			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通4G 12个月");
		}else if($type=='M130'){
			$input->SetBody("6G/年");
			$input->SetAttach("套餐购买 ");
			$price='178';
			$combname='6G/年';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
//			$input->SetTotal_fee("17800");
			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通6G/年");
		}else if($type=='M219'){
			$input->SetBody("联通8G 24个月");
			$input->SetAttach("套餐购买 ");
			$price='248';
			$combname='联通8G 24个月';

			$timetmp=date("YmdHis");
			$timetmp=substr($timetmp, 2);
			//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOut_trade_no($productid.$timetmp);
//			$input->SetTotal_fee("24800");
			$input->SetTotal_fee("1");
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("联通8G 24个月");
		}
		//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
//		$timetmp=date("YmdHis");
//		$timetmp=substr($timetmp, 1);
//		$input->SetOut_trade_no($product_id.$timetmp);
//		$input->SetTotal_fee("1");
//		$input->SetTime_start(date("YmdHis"));
//		$input->SetTime_expire(date("YmdHis", time() + 600));
//		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://mirror.micronavi.cn/xintupay/payindex/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetOpenid($openId);
		$input->SetProduct_id($product_id);
		$result = WxPayApi::unifiedOrder($input);
		return $result;
	}

//	public function setBody($type){
//		if($type=='A711'){
//			$input->SetBody("69元流量套餐");
//			$input->SetAttach("套餐购买 ");
//		}else if($type='A802'){
//			$input->SetBody("79元流量套餐");
//			$input->SetAttach("79元流量套餐 ");
//		}else{
//			$input->SetBody("其他套餐购买");
//			$input->SetAttach("其他套餐购买 ");
//		}
//	}

	public function NotifyProcess($data, &$msg)
	{
		//echo "处理回调";
//		Log::DEBUG("NativeNotify call back:" . json_encode($data));

		if(!array_key_exists("openid", $data) ||
		!array_key_exists("product_id", $data))
		{
			$msg = "回调数据异常";
			return false;
		}
			
		$openid = $data["openid"];
		$product_id = $data["product_id"];

		//统一下单
		$result = $this->unifiedorder($openid, $product_id);
		if(!array_key_exists("appid", $result) ||
		!array_key_exists("mch_id", $result) ||
		!array_key_exists("prepay_id", $result))
		{
			$msg = "统一下单失败";
			return false;
		}

		$this->SetData("appid", $result["appid"]);
		$this->SetData("mch_id", $result["mch_id"]);
		$this->SetData("nonce_str", WxPayApi::getNonceStr());
		$this->SetData("prepay_id", $result["prepay_id"]);
		$this->SetData("result_code", "SUCCESS");
		$this->SetData("err_code_des", "OK");
		return true;
	}
}

//Log::DEBUG("begin notify!");
$notify = new NativeNotifyCallBack();
$notify->Handle(true);
