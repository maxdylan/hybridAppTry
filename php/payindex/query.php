<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Config.php";
require_once 'log.php';


$id=$_GET['id'];

//一路wo
$appid='9B9C420FDC5E5745785702C112F64B0A';
$appsecret='9759959F100FDD106548CBC8D3195DA0';

if($id==''){
	echo '';
	return ;
}

if((strlen($id)==13)||(strlen($id)==15)||strlen($id)==20||strlen($id)==19){

}else{
	echo '';
	return;
}

$outjsonTmp='{
    	"code": %s,
    	"cardisp": "%s",
    	"status": "%s",
    	"statusinfo":"%s",
   		"cardtype": "%s",
    	"cardclass": "%s",
    	"sd": "%s",
    	"ed": "%s",
    	"ud": "%s",
    	"outdata": "%s",
    	"lavedata": "%s",
    	"totaldata": "%s",
    	"imsi": "%s",
    	"iccid": "%s",
    	"card_number": "%s"
		}';
if((strlen($id)==13)||(strlen($id)==15)||strlen($id)==20){
	$dty=time()*1000;
	$token=strtoupper(md5($appid.$appsecret.$dty));
	$urlTmp='http://wechat.cxsz.com.cn/WoCarNetwork/api/findCardInfo.json?appid=%s&dt=%s&token=%s&mobile=%s';
	$url=sprintf($urlTmp,$appid,$dty,$token,$id);
//	$res=file_get_contents($url);
	$arry=http_post_json($url, '');
	if($arry[0]==200){
		$res=$arry[1];
	}
	$resJson='';
	if(($res!='')&&($res!=null)){
		$resJson=json_decode($res);
	}


	if($resJson==''){
	}else{
		if($resJson->code==0){
			$sd=strtotime($resJson->sd);
			$ad=$resJson->outday*24*3600;
			$udtime=$sd+$ad;
			$ud=date('Y-m-d',$udtime);
			$lavedata=$resJson->totaldata-$resJson->outdata;

			if($resJson->status==0){
				$statusinfo='待机';
			}else if($resJson->status==1){
				$statusinfo='已激活';
			}else if($resJson->status==2){
				$statusinfo='已停机';
			}

			$outjson=sprintf($outjsonTmp,$resJson->code,$resJson->cardisp,$resJson->status,$statusinfo,$resJson->cardtype,'',$resJson->sd,$resJson->ed,$ud,$resJson->outdata,$lavedata,$resJson->totaldata,$resJson->imsi,$resJson->iccid,$resJson->card_number);
			echo 'y;'.$outjson;

			//	{
			//    "code": 0,
			//    "card_number": "1064697072238",
			//    "imsi": "460067009072238",
			//    "totalday": "365",
			//    "totaldata": "2000.0",
			//    "sd": "2016-07-18",
			//    "cardisp": "10010",
			//    "iccid": "8986061609001990578Y",
			//    "outdata": "249.0",
			//    "cardid": "218",
			//    "cardtype": "1",
			//    "outday": "22",
			//    "ed": "2017-07-17",
			//    "status": "1"
			//}
			return;
		}
	}
}


//麦联宝
$jsontmpm='{
"id":"%s",
"fields": [ 
	"isMapgoo","simNo","imsi","iccid","simState","package","activeTime","surplusPeriod","expireTime","surplusUsage","amountUsageData"
]
}';
$urlm='http://api.ext.m-m10010.com/api/QueryTerminalDetail/Query?Authorization=D22252BD0A2B83FE58813B48CBB14416';
$jsonm=sprintf($jsontmpm,$id);
$resm=http_post_jsonm($urlm,$jsonm);

if(count($resm)==2){
	if($resm[0]==200){
		$resJsonM=json_decode($resm[1]);
		if($resJsonM->error==0){
			if($resJsonM->result->simState==0){
				$statusinfo='库存';
			}else if($resJsonM->result->simState==1){
				$statusinfo='可测试';
			}else if($resJsonM->result->simState==2){
				$statusinfo='可激活';
			}else if($resJsonM->result->simState==3){
				$statusinfo='已激活';
			}else if($resJsonM->result->simState==4){
				$statusinfo='已停用';
			}else if($resJsonM->result->simState==5){
				$statusinfo='已失效';
			}else if($resJsonM->result->simState==6){
				$statusinfo='已注销';
			}
				
			if($resJsonM->error==0){
				$outdata=$resJsonM->result->amountUsageData-$resJsonM->result->surplusUsage;
				if($resJsonM->result->expireTime!=''){
					$ed=strtotime($resJsonM->result->expireTime);
					$sud=$resJson->surplusPeriod*24*3600;
					$udtime=$sd-$sud;
					$ud=date('Y-m-d',$udtime);
				}else{
					$ud='';
				}
				
				$outJsonM=sprintf($outjsonTmp,$resJsonM->error,'10010',$resJsonM->result->simState,$statusinfo,
				'none','none',$resJsonM->result->activeTime,$resJsonM->result->expireTime,$ud,
				$outdata,$resJsonM->result->surplusUsage,$resJsonM->result->amountUsageData,
				$resJsonM->result->imsi,$resJsonM->result->iccid,$resJsonM->result->simNo);

				echo 'm;'.$outJsonM;
				return ;
			}	
		}
	}else{
	}
}




if(strlen($id)==13){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"card_number":"%s"}';
}else if(strlen($id)==15){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"imsi":"%s"}';
}else if(strlen($id)==19){
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"iccid":"%s"}';
}else if(strlen($id)==20){
	$id=substr($id, 0,19);
	$jsonTmp='{"appid":"%s",
			"token":"%s",
			"dt":"%s",
			"iccid":"%s"}';
}
$dt=time();
$fantoken=strtoupper(md5(WxPayConfig::CARDAPPID.WxPayConfig::CARDAPPSECRET.$dt));
$json=sprintf($jsonTmp,WxPayConfig::CARDAPPID,$fantoken,$dt,$id);

$fanurl='http://rxcall.cn/if/cardinfo';
$result=http_post_json($fanurl, $json);

if(count($result)==2){
	if($result[0]==200){
		echo $result[1].'<br/>';
		//		echo 'f;'.$result[1];
		$resJson=json_decode($result[1]);

		$outjsonTmp='{
    	"code": %s,
    	"cardisp": "%s",
    	"status": "%s",
    	"statusinfo":"%s",
   		"cardtype": "%s",
    	"cardclass": "%s",
    	"sd": "%s",
    	"ed": "%s",
    	"ud": "%s",
    	"outdata": "%s",
    	"lavedata": "%s",
    	"totaldata": "%s",
    	"imsi": "%s",
    	"iccid": "%s",
    	"card_number": "%s"
		}';

		if($resJson->status==0){
			$statusinfo='正常';
		}else if($resJson->status==1){
			$statusinfo='停机';
		}else if($resJson->status==2){
			$statusinfo='销户';
		}else if($resJson->status==4){
			$statusinfo='已激活';
		}else if($resJson->status==5){
			$statusinfo='可测试';
		}else if($resJson->status==6){
			$statusinfo='已停用';
		}

		$outjson=sprintf($outjsonTmp,$resJson->code,$resJson->cardisp,$resJson->status,$statusinfo,$resJson->cardtype,$resJson->cardclass,$resJson->sd,$resJson->ed,$resJson->ud,
		$resJson->outdata,$resJson->lavedata,$resJson->totaldata,$resJson->imsi,$resJson->iccid,$resJson->card_number);

		echo 'f;'.$outjson;
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


