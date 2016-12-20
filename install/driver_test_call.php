<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


include __DIR__.'/driver_test.php';
$test=walletRequest('getinfo','http://'.$_GET['w_user'].':'.$_GET['w_pass'].'@'.$_GET['w_host'].':'.$_GET['w_port'].'/');

if ($test===null) {
  echo json_encode(array('error'=>'yes'));
}
else echo json_encode(array('error'=>'no'));

?>