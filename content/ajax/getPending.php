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

$settings=db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

$pendings='<table class="table table-striped" style="text-align: left;">';
$pendings.='<tr><th>Amount ('.$settings['currency_sign'].')</th><th>Confirmations Left</th></tr>';
$searcher=db_query("SELECT * FROM `deposits` WHERE `player_id`=$player[id] AND `received`!=0");
if (db_num_rows($searcher)==0) $pendings.='<tr><td colspan="2"><i>No pending deposits</i></td></tr>';
while ($dp=db_fetch_array($searcher)) {
  $mins_left=$settings['min_confirmations']-$dp['confirmations'];
  $amount=$dp['amount'];
  
  $pendings.='<tr><td><b>'.n_num($amount,true).'</b></td><td>'.$mins_left.'</td></tr>';        
}
 $pendings.='</table>';

echo json_encode(array('content'=>$pendings));
?>
