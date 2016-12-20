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

$player=db_fetch_array(db_query("SELECT `password`,`id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

maintenance();

if (empty($_GET['pass']) || $player['password']!=hash('sha256',$_GET['pass'])) {
  echo json_encode(array('color'=>'red','content'=>'Entered password is invalid.'));
  exit();
}


db_query("UPDATE `players` SET `password`='' WHERE `id`=$player[id] LIMIT 1");


$_SESSION['granted']='no';

echo json_encode(array('color'=>'green','content'=>'Password has been disabled.'));
?>
