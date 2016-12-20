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

if (empty($_GET['_new']) || db_num_rows(db_query("SELECT `id` FROM `news` WHERE `id`='".prot($_GET['_new'])."' LIMIT 1"))==0) exit();

db_query("DELETE FROM `news` WHERE `id`='".prot($_GET['_new'])."' LIMIT 1");
echo json_encode(array('error'=>'no'));
?>