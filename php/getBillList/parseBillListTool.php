<?php
class ParseBillListTool{
  private $billListPath = "../../asset/billList.xml";

  public function getAllListByChannel($channel){
    $normalTags = array('billId','billName','billDescribe','billPrice');
    $dom = new DOMDocument();
    $dom->load("../../asset/billList.xml");
    $channleList = $dom->getElementsByTagName('channel');
    $result = array();
    // echo $channleList;
    foreach ($channleList as $key => $value) {
      $_channle = $value->getAttribute('name');
      if($_channle == $channel){
        $billList = $dom->getElementsByTagName('bill');
        foreach ($billList as $k => $v) {
          foreach ($normalTags as $st) {
            $node = $v->getElementsByTagName($st);
            $nodeV = $node->item(0)->nodeValue;
            $result[$k][$st]= urlencode($nodeV);
          }
        }
      }
    }
    $beJson = json_encode($result);
    return urldecode($beJson);
  }

  public function getSimpleList($channel){
    $normalTags = array('billId','billName','billPrice');
    $dom = new DOMDocument();
    $dom->load("../../asset/billList.xml");
    $channleList = $dom->getElementsByTagName('channel');
    $result = array();
    // echo $channleList;
    foreach ($channleList as $key => $value) {
      $_channle = $value->getAttribute('name');
      if($_channle == $channel){
        $billList = $dom->getElementsByTagName('bill');
        foreach ($billList as $k => $v) {
          foreach ($normalTags as $st) {
            $node = $v->getElementsByTagName($st);
            $nodeV = $node->item(0)->nodeValue;
            $result[$k][$st]= urlencode($nodeV);
          }
        }
      }
    }
    $beJson = json_encode($result);
    return urldecode($beJson);
  }

  public function getBillById($channel,$billId){

    $dom = new DOMDocument();
    $dom->load('http://mirror.micronavi.cn/intercompay/asset/billList.xml');
    $channleList = $dom->getElementsByTagName('channel');
    $result = "{\"billId\":\"".$billId."\",\"billName\":\"";
    // echo $channleList;
    foreach ($channleList as $key => $value) {
      $_channle = $value->getAttribute('name');
      if($_channle == $channel){
        $billList = $dom->getElementsByTagName('bill');
        foreach ($billList as $k => $v) {
          $node = $v->getElementsByTagName('billId');
          $nodeV = $node->item(0)->nodeValue;
          if($nodeV==$billId){
            // $result.$step;
            $result = $result.$v->getElementsByTagName('billName')->item(0)->nodeValue."\",\"billDescribe\":\"".$v->getElementsByTagName('billDescribe')->item(0)->nodeValue."\",\"billPrice\":\"".$v->getElementsByTagName('billPrice')->item(0)->nodeValue."\"}";
            break;
          }
        }
      }
    }
    return $result;
  }
}
 ?>
