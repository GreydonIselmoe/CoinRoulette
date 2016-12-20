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

$player=db_fetch_array(db_query("SELECT `id`,`client_seed` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

maintenance();

if (empty($_GET['seed']) || (int)$_GET['seed']==0) {
  echo json_encode(array('color'=>'red','content'=>'This must be a number.','repaired'=>$player['client_seed']));
  exit();
}

$repaired=(int)$_GET['seed'];

db_query("UPDATE `players` SET `client_seed`='".substr((string)$repaired,0,8)."' WHERE `id`=$player[id] LIMIT 1");

echo json_encode(array('color'=>'green','content'=>'Client seed has been set.','repaired'=>substr((string)$repaired,0,8)));
?>
