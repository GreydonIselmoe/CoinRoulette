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

$player=db_fetch_array(db_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

maintenance();

validateAccess($player['id']);

if (empty($_GET['lastId']) || (int)$_GET['lastId']==0) {
  $lastid=0;
  $limit=100;
}
else {
  $lastid=(int)$_GET['lastId'];
  $limit=500;
}

$content='';

$messages=db_query("SELECT * FROM `chat` WHERE `id`>$lastid ORDER BY `time` DESC,`id` DESC LIMIT $limit");
$messages_array=array();

while ($message=db_fetch_array($messages)) {
  $messages_array[]=$message;  
}

$messages=array_reverse($messages_array);

foreach ($messages as $message) {
  $content.='<div class="chat-message" data-messid="'.$message['id'].'">';  
  $sender=db_fetch_array(db_query("SELECT `alias` FROM `players` WHERE `id`=$message[sender] LIMIT 1"));  
  
  if ($sender==false) $sender['alias']='[unknown]';
  
  $content.='<div class="chat-m-user">'.$sender['alias'].'</div>';
  $content.='<div class="chat-m-time">'.date('H:i', strtotime($message['time'])).'</div>';
  $content.='<div class="chat-m-text">'.$message['content'].'</div>';
  $content.='</div>';
}

echo json_encode(array('content'=>$content));

?>
