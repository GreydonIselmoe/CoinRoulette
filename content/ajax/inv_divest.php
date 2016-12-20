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

$player=db_fetch_array(db_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

maintenance();

if (db_num_rows(db_query("SELECT `id` FROM `investors` WHERE `player_id`=$player[id] LIMIT 1"))==0) {
  db_query("INSERT INTO `investors` (`player_id`) VALUES ($player[id])");
}

$investor=db_fetch_array(db_query("SELECT * FROM `investors` WHERE `player_id`=$player[id] LIMIT 1"));

if ((double)$_GET['amount']<=0) {
  echo json_encode(array('error'=>'yes'));
  exit();
}

$amount=(double)$_GET['amount'];

if ($investor['amount']<$amount) {
  echo json_encode(array('error'=>'yes'));
  exit();
}

db_query("UPDATE `investors` SET `amount`=TRUNCATE(ROUND((`amount`-$amount),9),8) WHERE `player_id`=$player[id] LIMIT 1");


db_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND((`balance`+$amount),9),8) WHERE `id`=$player[id] LIMIT 1");





echo json_encode(array('error'=>'no'));


?> 