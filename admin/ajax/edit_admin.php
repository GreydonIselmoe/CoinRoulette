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

if (empty($_GET['admin']) || !is_numeric($_GET['admin']) || empty($_GET['unm']) || empty($_GET['pass']) || db_num_rows(db_query("SELECT `id` FROM `admins` WHERE `id`='".prot($_GET['admin'])."' LIMIT 1"))==0) exit();

db_query("UPDATE `admins` SET `username`='".prot($_GET['unm'])."',`passwd`='".hash('sha256',$_GET['pass'])."' WHERE `id`='".prot($_GET['admin'])."' LIMIT 1");
echo json_encode(array('error'=>'no'));
?>