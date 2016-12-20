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
include __DIR__.'/../../inc/wallet_driver.php';
include __DIR__.'/../../inc/db_functions.php';
include __DIR__.'/../../inc/functions.php';

if (empty($_GET['_unique']) || db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();

$player=db_fetch_array(db_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

maintenance();

$settings=db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

if (db_num_rows(db_query("SELECT `id` FROM `investors` WHERE `player_id`=$player[id] LIMIT 1"))==0) {
  db_query("INSERT INTO `investors` (`player_id`) VALUES ($player[id])");
}


$investor=db_fetch_array(db_query("SELECT * FROM `investors` WHERE `player_id`=$player[id]"));

$reservedBalance=db_fetch_array(db_query("SELECT SUM(`balance`) AS `sum` FROM `players`"));
$reservedWaitingBalance=db_fetch_array(db_query("SELECT SUM(`amount`) AS `sum` FROM `deposits`"));
$serverBalance=walletRequest('getbalance');
$serverFreeBalance=($serverBalance-$reservedBalance['sum']-$reservedWaitingBalance['sum']);

$invested=$investor['amount'];
$share=(($serverFreeBalance)==0)?0:(($invested/$serverFreeBalance)*(100-$settings['inv_perc']));

$return = array(
  'canInv'      =>  '<b>'.n_num($player['balance'], true).'</b> '.$settings['currency_sign'],
  'invested'    =>  '<b>'.n_num($invested, true).'</b> '.$settings['currency_sign'],
  'share'       =>  '<b>'.n_num($share).'</b> %' 
);
echo json_encode($return);

?>