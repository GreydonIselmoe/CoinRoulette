<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


header('X-Frame-Options: DENY'); 

session_start();
if (isset($_GET['logout'])) {
  $_SESSION['logged_']=false;
  header('Location: ./?logouted');
  exit();
}
$init=true;
include __DIR__.'/../inc/db-conf.php';
include __DIR__.'/../inc/db_functions.php';
include __DIR__.'/../inc/functions.php';
if (!empty($_POST['hash_one']) && !empty($_POST['hash_sec']) && db_num_rows(db_query("SELECT `id` FROM `admins` WHERE `username`='".prot($_POST['hash_one'])."' AND `passwd`='".hash('sha256',$_POST['hash_sec'])."' LIMIT 1"))!=0) {
  $this_admin=db_fetch_array(db_query("SELECT `username`,`ga_token` FROM `admins` WHERE `username`='".prot($_POST['hash_one'])."' AND `passwd`='".hash('sha256',$_POST['hash_sec'])."' LIMIT 1"));
  if ($this_admin['ga_token']=='') {
    $_SESSION['logged_']=true;
    $_SESSION['username']=$this_admin['username'];
    db_query("INSERT INTO `admin_logs` (`admin_username`,`ip`,`browser`) VALUES ('".$_SESSION['username']."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."')");
    header('Location: ./');
  }
  else {
    $_SESSION['2f_1']['username']=$this_admin['username'];
    $_SESSION['2f_1']['ga_token']=$this_admin['ga_token'];
    header('Location: ./?totp');
  }
  exit();  
}
else if (!empty($_POST['totp'])) {
  include __DIR__.'/ga_class.php';

  $verify=Google2FA::verify_key($_SESSION['2f_1']['ga_token'],$_POST['totp'],0);
   
  if ($verify==true) {
    $_SESSION['logged_']=true;
    $_SESSION['username']=$_SESSION['2f_1']['username'];
    $_SESSION['2f_1']=false;
    db_query("INSERT INTO `admin_logs` (`admin_username`,`ip`,`browser`) VALUES ('".$_SESSION['username']."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."')");
    
    header('Location: ./');
  }
} 
header('Location: ./?login_error');
?>