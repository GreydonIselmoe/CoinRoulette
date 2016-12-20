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

mysql_query('START TRANSACTION');


if (empty($_GET['_unique']) || db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1 FOR UPDATE"))==0) exit();

validateAccess($player['id']);

maintenance();

$validate = walletRequest('validateaddress', array($_GET['valid_addr']));
if (!$validate['isvalid']) {
  $error = 'yes';
  $con = 'Address is not valid.';
}
else {

  $settings = db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));
  $player = db_fetch_array(db_query("SELECT `id`,`balance` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

  if (!is_numeric($_GET['amount']) || (double)$_GET['amount'] > $player['balance'] || (double)$_GET['amount'] < $settings['min_withdrawal']) {
    $error = 'yes';
    $con = 'You have insufficient funds.';
  }
  else {

    $amount = (double)$_GET['amount'];
    db_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND((`balance`-$amount),9),8) WHERE `id`=$player[id] LIMIT 1");    

    if ($settings['withdrawal_mode']) {
      
      db_query("INSERT INTO `withdrawals` (`player_id`,`amount`,`address`) VALUES ($player[id],$amount,'".prot($_GET['valid_addr'])."')");
      
      echo json_encode(array('error'=>'half','content'=>''));
      exit();
    }

    $txid = walletRequest('sendtoaddress', array($_GET['valid_addr'],$amount));
    db_query("INSERT INTO `transactions` (`player_id`,`amount`,`txid`) VALUES ($player[id],(0-$amount),'$txid')");
    $error = 'no';
    $con = $txid;
  }
}
$return=array(
  'error' => $error,
  'content' => $con
);

echo json_encode($return);


mysql_query('COMMIT');

?>
