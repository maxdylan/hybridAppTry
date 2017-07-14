<?php
  $channelId = $_GET['channel'];
  $normalTags = array('bill','billName','billDescribe','billPrice');
  $dom = new DOMDocument();
  $dom->load('billList.xml');
  $channleList = $dom->getElementsByTagName('channle');
  $result = array();
  // echo $channleList;
  foreach ($channleList as $key => $value) {
    echo $key;
    $_channle = $value->getAttribute('name');
    // if($_channle == $channelId){
      $billList = $dom->getElementsByTagName('bill');
      foreach ($billList as $k => $v) {
        $result[$k]['billId'] = $v->getAttribute('id');
        foreach ($normalTags as $st) {
          $node = $v->getElementsByTagName($st);
          $result[$k][$st]= $node->item(0)->nodeValue;
        }
      }
    // }
  }
  echo "string2";
  // echo json_encode($result);
 ?>
