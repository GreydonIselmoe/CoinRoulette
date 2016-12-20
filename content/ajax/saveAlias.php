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

$player=db_fetch_array(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

maintenance();

if (empty($_GET['alias'])) {
  echo json_encode(array('color'=>'red','content'=>'Alias can\'t be empty.'));
  exit();
}

$repaired=substr(prot($_GET['alias']),0,25);

if (db_num_rows(db_query("SELECT `id` FROM `players` WHERE `alias`='$repaired' LIMIT 1"))!=0) {
  echo json_encode(array('color'=>'red','content'=>'This alias is already taken.'));
  exit();
}

db_query("UPDATE `players` SET `alias`='$repaired' WHERE `id`=$player[id] LIMIT 1");

echo json_encode(array('color'=>'green','content'=>'Alias has been saved.','repaired'=>$repaired));
?>
