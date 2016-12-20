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

$settings=db_fetch_array(db_query("SELECT * FROM `system` LIMIT 1"));

$player=db_fetch_array(db_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

$server_seed = unserialize($player['server_seed']);
$client_seed = (int)$player['client_seed'];


if (!isset($_GET['w']) || (double)$_GET['w']<0 || (double)$_GET['w']>$player['balance']) {     // bet amount
  echo json_encode(array('error' => 'Invalid bet'));
  exit();
}


$wager = (double)$_GET['w'];
if ($wager < $settings['min_bet'] && $wager != 0) {
  echo json_encode(array('error' => 'Your bet is too small'));
  exit();
}

$wbalance = walletRequest('getbalance');
$max_wager = (double)$wbalance/$settings['bankroll_maxbet_ratio'];
if ($wager > $max_wager) {
  echo json_encode(array('error' => 'Your bet is too big'));
  exit();  
}

$index = ($server_seed['seed_num'] + $client_seed) % 128;

(int)$result1 = $server_seed[ 'wheel1' ][ $index ];
(int)$result2 = $server_seed[ 'wheel2' ][ $index ];
(int)$result3 = $server_seed[ 'wheel3' ][ $index ];


if ($result1 == 1 && $result2 == 1 && $result3 == 1)
  $multiplier = $settings['jackpot'];
else if ($result1 == 2 && $result2 == 2 && $result3 == 2)
  $multiplier = 600;
else if ($result1 == 3 && $result2 == 3 && $result3 == 3)
  $multiplier = 200;
else if ($result1 == 4 && $result2 == 4 && $result3 == 4)
  $multiplier = 50;
else if ($result1 == 5 && $result2 == 5 && $result3 == 5)
  $multiplier = 10;
else if ($result1 == 6 && $result2 == 6 && $result3 == 6)
  $multiplier = 5;
else if (($result1 == 6 && $result2 == 6) || ($result1 == 6 && $result3 == 6) || ($result2 == 6 && $result3 == 6))
  $multiplier = 2;
else if ($result1 == 6 || $result2 == 6 || $result3 == 6)
  $multiplier = 1;
else
  $multiplier = 0;

$payout = $wager * $multiplier;
$profit = ($wager * -1) + $payout;

$r_win = 0; $r_lose = 0; $r_tie = 0;

if ($multiplier < 1) $r_lose = 1;
else if ($multiplier > 1) $r_win = 1;
else $r_tie = 1;


$player_q = db_query("SELECT * FROM `players` WHERE `id`=$player[id] AND `balance` >= $wager LIMIT 1");
if (db_num_rows($player_q) == 0) {
  echo json_encode(array('error' => 'Invalid bet'));
  exit();
}
$player = db_fetch_array($player_q);


$newBalance = $player['balance'] + $profit;



if ($settings['inv_enable'] == 1 && $profit != 0) {
  
  $sFreeBalance = db_fetch_array(db_query("SELECT SUM(`balance`) AS `sum` FROM `players`"));
  $sFreeBalance = $sFreeBalance['sum'];
  
  $cas_profit = $profit*-1;
  
  $inv_invest = db_fetch_array(mysql_query("SELECT SUM(`amount`) AS `sum` FROM `investors` WHERE `amount`!=0"));
  $inv_invest = $inv_invest['sum'];
  $cas_invest = ($sFreeBalance - $inv_invest);
  
  db_query("UPDATE `investors` SET `amount`=(`amount`+(($cas_profit/100)*((`amount`/$sFreeBalance)*(100-$settings[inv_perc])))),`profit`=(`profit`+(($cas_profit/100)*((`amount`/$sFreeBalance)*(100-$settings[inv_perc])))) WHERE `amount`!=0");
  
  $cas_percprofit = 0;
    
  $q = db_query("SELECT * FROM `investors` WHERE `amount`!=0");
  while ($inv = db_fetch_array($q)) {
    $cas_percprofit += (($cas_profit/100)*(($inv['amount']/$sFreeBalance)*($settings['inv_perc'])));
  }
  
  db_query("UPDATE `system` SET `inv_casprofit`=(`inv_casprofit`+(($cas_profit/100)*(($cas_invest/$sFreeBalance)*(100)))+$cas_percprofit) LIMIT 1");
}

          
db_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND($newBalance,9),8),`t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$r_win,`t_profit`=TRUNCATE(ROUND((`t_profit`+$profit),9),8) WHERE `id`=$player[id] LIMIT 1");
db_query("INSERT INTO `spins` (`player`,`bet_amount`,`server_seed`,`client_seed`,`result`,`multiplier`,`payout`) VALUES ($player[id],$wager,'".serialize($server_seed)."','$client_seed','".$result1.','.$result2.','.$result3."',$multiplier,$payout)");
db_query("UPDATE `system` SET `t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$r_win,`t_loses`=`t_loses`+$r_lose,`t_ties`=`t_ties`+$r_tie,`t_player_profit`=TRUNCATE(ROUND((`t_player_profit`+$profit),9),8) LIMIT 1");

//new seed
                                                                                                                                                                      
$newSeed    = serialize( generateServerSeed() );
$newCSeed   = random_num(8);

db_query("UPDATE `players` SET `last_server_seed`=`server_seed`,`server_seed`='$newSeed',`last_client_seed`=`client_seed`,`client_seed`='$newCSeed',`last_final_result`='$result1,$result2,$result3' WHERE `id`=$player[id] LIMIT 1");


echo  json_encode(array(
                    'error' =>  'no',
                    'val1'  =>  $result1,
                    'val2'  =>  $result2,
                    'val3'  =>  $result3,
                    'fair'  =>  array(
                                  
                                  'newSeed'           => hash( 'sha256', seedExport($newSeed) ),
                                  'newCSeed'          => $newCSeed,
                                  'lastSeed_sha256'   => hash( 'sha256', seedExport($player['server_seed']) ),
                                  'lastSeed'          => seedExport( $player['server_seed'] ),
                                  'lastCSeed'         => $client_seed,
                                  'lastResult'        => "$result1,$result2,$result3"
                                  
                                ),
                    'items' =>  array(
                                  'wheel1' => $server_seed['wheel1'],
                                  'wheel2' => $server_seed['wheel2'],
                                  'wheel3' => $server_seed['wheel3']
                                ),
                    'index' => $index
                  
      ));
      
      
      
      
      
mysql_query('COMMIT');      

?>