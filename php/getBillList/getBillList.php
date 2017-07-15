<?php
  libxml_disable_entity_loader(false);
  header("content-Type: text/html; charset=utf-8");
  $channelId = $_GET['channel'];
  $normalTags = array('billId','billName','billDescribe','billPrice');
  $dom = new DOMDocument();
  $dom->load('../../asset/billList.xml');
  $channleList = $dom->getElementsByTagName('channel');
  $result = array();
  // echo $channleList;
  foreach ($channleList as $key => $value) {
    $_channle = $value->getAttribute('name');
    if($_channle == $channelId){
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
  echo urldecode($beJson);

 ?>
