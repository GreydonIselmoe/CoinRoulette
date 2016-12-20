<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


header('X-Frame-Options: DENY'); 

session_start();
if (!isset($_SESSION['logged_']) || $_SESSION['logged_']!==true) exit();

$init=true;
include __DIR__.'/../../inc/db-conf.php';
include __DIR__.'/../../inc/db_functions.php';
include __DIR__.'/../../inc/functions.php';
include __DIR__.'/../ga_class.php';

if (empty($_GET['newtoken']) || empty($_GET['totp']) || empty($_GET['id'])) exit();

$verify=Google2FA::verify_key(prot($_GET['newtoken']),$_GET['totp'],0);

if ($verify==true) {

  db_query("UPDATE `admins` SET `ga_token`='".prot($_GET['newtoken'])."' WHERE `id`=".prot($_GET['id'])." LIMIT 1");

  echo json_encode(array('success'=>'yes'));
}
else echo json_encode(array('success'=>'no'));
?>
