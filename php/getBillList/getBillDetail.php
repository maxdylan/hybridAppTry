<?php
  libxml_disable_entity_loader(false);
  require_once 'parseBillListTool.php';

  $channel = $_GET['channel'];
  $billId = $_GET['billid'];

  $parseTool = new ParseBillListTool();
  echo $parseTool->getBillById($channel,$billId);
 ?>
