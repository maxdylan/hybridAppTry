<?php 
$json='{"code":12,"name":"lixiang"}';
echo $json;
$arr=json_decode($json);
echo count($arr);
echo $arr->code;
echo $arr['code'];

?>