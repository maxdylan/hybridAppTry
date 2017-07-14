<?php
//根据卡的类型来返回不同的套餐,主要是灵活的管理套餐。

$type=$_GET['type'];
$iccid=$_GET['iccid'];

if($type==''){
	echo '';
	return;
}
$jsonCombo='';

if($type=='f'){//fanrui
	$jsonCombo='{
		"type":"f",
		"combos":[
			{
				"id":"A012",
				"title":"2G/半年",
				"data":2048,
				"time":6,
				"price":65,
				"tip":"全国可用，2G流量限半年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/A012.png"
			},
			{
				"id":"A013",
				"title":"2G/年",
				"data":2048,
				"time":12,
				"price":68,
				"tip":"全国可用，2G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/A013.png"
			},
			{
				"id":"A014",
				"title":"4G/年",
				"data":4096,
				"time":12,
				"price":128,
				"tip":"全国可用，4G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/A014.png"
			},
			{
				"id":"A105",
				"title":"6G/年",
				"data":6144,
				"time":12,
				"price":178,
				"tip":"全国可用，6G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/A105.png"
			},
			{
				"id":"A016",
				"title":"8G/2年",
				"data":8192,
				"time":24,
				"price":248,
				"tip":"全国可用，8G流量限两年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/A016.png"
			}
		]
	}';


}else if($type=='y'){//yiluwo
	//	Combo  combo6=new Combo("Y218", "2G/年", 2048, 12, 68, "全国可用，2G流量限一年有效");
	//		Combo  combo7=new Combo("Y219", "4G/年", 4096, 12, 128, "全国可用，4G流量限一年有效");
	//		Combo  combo8=new Combo("Y225", "6G/年", 6144, 12, 178, "全国可用，6G流量限一年有效");
	$jsonCombo='{
		"type":"f",
		"combos":[
			{
				"id":"Y219",
				"title":"4G/年",
				"data":4096,
				"time":12,
				"price":128,
				"tip":"全国可用，4G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/Y219.png"
			},
			{
				"id":"Y225",
				"title":"6G/年",
				"data":6144,
				"time":12,
				"price":178,
				"tip":"全国可用，6G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/Y225.png"
			}
		]
		}';

}else if($type=='m'){
	//{
	//    "error": 0,
	//    "reason": "D22252BD0A2B83FE58813B48CBB14416",
	//    "result": {
	//        "simNo": "861064623630880",
	//        "iccid": "89860616020001308803",
	//        "imsi": "460063512030880",
	//        "simState": 2,
	//        "package": "2G半年套餐",
	//        "surplusUsage": 2048,
	//        "amountUsageData": 2048,
	//        "activeTime": "",
	//        "expireTime": "",
	//        "surplusPeriod": 180,
	//        "PackageList": [
	//            {
	//                "PackageId": 215,
	//                "PackageName": "2G半年套餐",
	//                "Price": 120,
	//                "OriginalPrice": 120,
	//                "PeriodType": "6个月",
	//                "PeriodDays": 180,
	//                "isUsageReset": false,
	//                "isAddPackage": false,
	//                "QRCodeUrl": "http://7xjcrk.com1.z0.glb.clouddn.com/52d96ec61fc346469be6983b68d6a3aa.png",
	//                "PackagePeriod": null,
	//                "PeriodNumber": 0,
	//                "PackageInfo": "2048MB，流量不清零，半年有效，全国通用，总流量用完即停机，可累加包年套餐",
	//                "UsageToPeriod": 2048,
	//                "AmountUsageData": 2048,
	//                "ExpireTime": ""
	//            },
	//            {
	//                "PackageId": 20,
	//                "PackageName": "2G一年(包年套餐)",
	//                "Price": 120,
	//                "OriginalPrice": 180,
	//                "PeriodType": "12个月",
	//                "PeriodDays": 365,
	//                "isUsageReset": false,
	//                "isAddPackage": false,
	//                "QRCodeUrl": "http://7xjcrk.com1.z0.glb.clouddn.com/60b1910a49174fafbcb9522b9acfbe8f.png",
	//                "PackagePeriod": null,
	//                "PeriodNumber": 0,
	//                "PackageInfo": "2048MB，流量不清零，一年有效，全国通用，总流量用完即停机，可累加包年套餐。",
	//                "UsageToPeriod": 2048,
	//                "AmountUsageData": 2048,
	//                "ExpireTime": "2017-09-14"
	//            },
	//            {
	//                "PackageId": 101,
	//                "PackageName": "4G一年(包年套餐)",
	//                "Price": 240,
	//                "OriginalPrice": 320,
	//                "PeriodType": "12个月",
	//                "PeriodDays": 365,
	//                "isUsageReset": false,
	//                "isAddPackage": false,
	//                "QRCodeUrl": "http://7xjcrk.com1.z0.glb.clouddn.com/f81ba4197bad4606a186c77b527d954d.png",
	//                "PackagePeriod": null,
	//                "PeriodNumber": 0,
	//                "PackageInfo": "4096MB，流量不清零，一年有效，全国通用，总流量用完即停机，可累加包年套餐。",
	//                "UsageToPeriod": 4096,
	//                "AmountUsageData": 4096,
	//                "ExpireTime": "2017-09-14"
	//            },
	//            {
	//                "PackageId": 130,
	//                "PackageName": "6G一年(包年套餐)",
	//                "Price": 360,
	//                "OriginalPrice": 480,
	//                "PeriodType": "12个月",
	//                "PeriodDays": 365,
	//                "isUsageReset": false,
	//                "isAddPackage": false,
	//                "QRCodeUrl": "http://7xjcrk.com1.z0.glb.clouddn.com/8c63d893eac14984a22e335787c6894c.png",
	//                "PackagePeriod": null,
	//                "PeriodNumber": 0,
	//                "PackageInfo": "6144MB，流量不清零，一年有效，全国通用，总流量用完即停机，可累加包年套餐。",
	//                "UsageToPeriod": 6144,
	//                "AmountUsageData": 6144,
	//                "ExpireTime": "2017-09-14"
	//            },
	//            {
	//                "PackageId": 219,
	//                "PackageName": "8G二年(包年套餐)",
	//                "Price": 480,
	//                "OriginalPrice": 480,
	//                "PeriodType": "24个月",
	//                "PeriodDays": 720,
	//                "isUsageReset": false,
	//                "isAddPackage": false,
	//                "QRCodeUrl": "http://7xjcrk.com1.z0.glb.clouddn.com/179016cf49ab4491b683e06a80dd9793.png",
	//                "PackagePeriod": null,
	//                "PeriodNumber": 0,
	//                "PackageInfo": "8192MB，流量不清零，有效期两年，全国通用，总流量用完即停机，可累加包年套餐",
	//                "UsageToPeriod": 8192,
	//                "AmountUsageData": 8192,
	//                "ExpireTime": "2018-09-14"
	//            }
	//        ]
	//    }
	//}

	$jsonCombo='{
		"type":"m",
		"combos":[
			{
				"id":"M215",
				"title":"2G/半年",
				"data":2048,
				"time":6,
				"price":65,
				"tip":"全国可用，2G流量限半年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/M215.png"
			},
			{
				"id":"MM20",
				"title":"2G/年",
				"data":2048,
				"time":12,
				"price":68,
				"tip":"全国可用，2G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/MM20.png"
			},
			{
				"id":"M101",
				"title":"4G/年",
				"data":4096,
				"time":12,
				"price":128,
				"tip":"全国可用，4G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/M101.png"
			},
			{
				"id":"M130",
				"title":"6G/年",
				"data":6144,
				"time":12,
				"price":178,
				"tip":"全国可用，6G流量限一年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/M130.png"
			},
			{
				"id":"M219",
				"title":"8G/2年",
				"data":8192,
				"time":24,
				"price":248,
				"tip":"全国可用，8G流量限两年有效",
				"imgurl":"http://mirror.micronavi.cn/xintupay/img/M219.png"
			}
		]
	}';
}

echo $jsonCombo;
