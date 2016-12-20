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


$interval = 5;   // The minimal number of seconds between deposit checks.. 


$settings = db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));


if (ini_get('safe_mode')==false) set_time_limit(0);

if (/**true) {//*/db_num_rows(db_query("SELECT * FROM `system` WHERE `id`=1 AND `deposits_last_round`<NOW()-INTERVAL $interval SECOND LIMIT 1"))==1) {
  include __DIR__.'/../../inc/check_deposits.php';
  _checkDeposits();
}


if ($settings['maintenance']) $mt = 'yes'; else $mt = 'no';

echo json_encode(array('maintenance' => $mt));

?>
