<?php
/*
 *  © CoinWheel 
 *  Demo: http://www.btcircle.com
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

db_query('START TRANSACTION');
maintenance();

if (empty($_GET['_unique']) || db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();

$settings=db_fetch_array(db_query("SELECT * FROM `system` LIMIT 1"));
$player=db_fetch_array(db_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1 FOR UPDATE"));

validateAccess($player['id']);

$result = (bcmod(bcadd($player['server_seed'],$player['client_seed']),34));

$wager = 0;
if(isset($_POST['bets'])){
    for ($i = 0; $i < count($_POST['bets']); ++$i) {
        $wager += $_POST['bets'][$i]['amount']/10000000;
    }
}

if($wager > $player['balance']) {
    echo json_encode(array('error' => 'yes','message'=>'invalid_bet'));
    exit();
}

if ($wager < $settings['min_bet'] && $wager!=0) {
    echo json_encode(array('error' => 'yes','message'=>'too_small'));
    exit();
}


$wbalance = walletRequest('getbalance');
$max_wager = (double)$wbalance/$settings['bankroll_maxbet_ratio'];
if ($wager > $max_wager) {
    echo json_encode(array('error' => 'Your bet is too big'));
    exit();
}


//New seed
$s_seed=random_num(32);
$c_seed=random_num(32);


db_query("UPDATE `players` SET `last_client_seed`='".$player['client_seed']."', `client_seed`='$c_seed', `server_seed`='$s_seed',`last_server_seed`='".$player['server_seed']."' WHERE `id`=".$player['id']." LIMIT 1");


$amount = 0;
if(isset($_POST['bets'])) {
    for ($i = 0; $i < count($_POST['bets']); ++$i) {
        if (in_array($result, $_POST['bets'][$i]['fields'])) {
            $amount += (($_POST['bets'][$i]['amount'] * 36 / count($_POST['bets'][$i]['fields']))/10000000);
        }
    }
}
$balance=$player['balance']-$wager+$amount;
$wager !=0?$multiplier = $amount/$wager: $multiplier = 0;

$r_win = 0;
$r_lose = 0;

$amount > 0 ? $r_win = 1 : $r_lose = 1;



if ($settings['inv_enable'] == 1 && $profit != 0) {

    $sFreeBalance = db_fetch_array(db_query("SELECT SUM(`balance`) AS `sum` FROM `players`"));
    $sFreeBalance = $sFreeBalance['sum'];

    $cas_profit = $profit*-1;

    $inv_invest = db_fetch_array(db_query("SELECT SUM(`amount`) AS `sum` FROM `investors` WHERE `amount`!=0"));
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

db_query("UPDATE `players` SET `balance`=TRUNCATE($balance,10),`t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$r_win,`t_profit`=TRUNCATE(ROUND((`t_profit`+$amount),9),8),`last_final_result`=$result WHERE `id`=".$player['id']." LIMIT 1");
db_query("UPDATE `system` SET `t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$r_win,`t_loses`=`t_loses`+$r_lose,`t_player_profit`=TRUNCATE(ROUND((`t_player_profit`+$amount),9),8) LIMIT 1");
db_query("INSERT INTO `spins` (`server_seed`,`client_seed`,`bet_amount`,`payout`,`player`, `multiplier`, `result`) VALUES ('$s_seed','$c_seed', $wager,$amount,".$player['id'].", $multiplier, $result)");


echo json_encode(array('number' => $result, 'wager' => $wager, 'win' => rtrim(rtrim(sprintf("%0.12f",$amount),'0'),'.'), 'server_seed' => hash('sha256',$s_seed), 'old_server_seed' => $player['server_seed'], 'old_server_seed_sha' => hash('sha256',$player['server_seed']), 'old_client_seed' => $player['client_seed'], ''=> $c_seed, 'your_spins' => $player['t_bets']+1, 'total_spins' => $settings['t_bets']+1));

db_query('COMMIT');
?>