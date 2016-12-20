<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (!isset($init)) exit();


session_start();

$conf_c = false;
include __DIR__.'/db-conf.php';
if ($conf_c == false) {
  header('Location: ./install/');
  exit();
}
include __DIR__.'/wallet_driver.php';
include __DIR__.'/db_functions.php';
include __DIR__.'/functions.php';


if (empty($_GET['unique'])) {
  if (!empty($_COOKIE['unique_S_']) && db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_COOKIE['unique_S_'])."' LIMIT 1"))!=0) {
    header('Location: ./?unique='.$_COOKIE['unique_S_'].'# Do Not Share This URL!');
    exit();  
  }
  newPlayer($wallet);
}
else { // !empty($_GET['unique'])
  if (db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['unique'])."' LIMIT 1"))!=0) {
    $player=db_fetch_array(db_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['unique'])."' LIMIT 1"));
    $unique=prot($_GET['unique']);
    setcookie('unique_S_',prot($_GET['unique']),(time()+60*60*24*365*5),'/');  
  }
  else {
    setcookie('unique_S_',false,(time()-10000),'/');
    header('Location: ./');    
    exit();
  }
}


$settings = db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));


if ($player['password']!='' && (empty($_SESSION['granted']) || $_SESSION['granted']!='yes')) {  
  include __DIR__.'/unlockAccess.php';
  exit();
}

if ($settings['maintenance']) {
  include __DIR__.'/maintenance.php';
  exit();
}

?>