<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


error_reporting(0);
header('X-Frame-Options: DENY'); 

$init=true;
include __DIR__.'/../../inc/db-conf.php';
include __DIR__.'/../../inc/db_functions.php';
include __DIR__.'/../../inc/functions.php';

if (empty($_GET['_unique']) || db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();

$player = db_fetch_array(db_query("SELECT `password`,`id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));


maintenance();

if (empty($_GET['pass']) || $player['password']!=hash('sha256',$_GET['pass'])) {
  echo json_encode(array('error'=>'yes','content'=>'Entered password is invalid.','pass'=>hash('sha256',$_GET['pass'])));
  exit();
}


session_start();

$_SESSION['granted']='yes';

echo json_encode(array('error'=>'no'));
?>
