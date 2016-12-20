<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (!isset($init)) exit();     

function prot($hodnota,$max_delka=0) {
  $text=db_real_escape_string(strip_tags($hodnota));
  if ($max_delka!=0)  $vystup=substr($text,0,$max_delka);
  else  $vystup=$text;
  return $vystup;
}

function generateHash($length,$capt=false) {
  if ($capt==true) $possibilities='123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'; 
  else $possibilities='abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $return='';
  for ($i=0;$i<$length;$i++)  $return.=$possibilities[mt_rand(0,strlen($possibilities)-1)];
  return $return;
}
function random_num($length) {
  $possibilities='1234567890';
  $return='';
  for ($i=0;$i<$length;$i++)  $return.=$possibilities[mt_rand(0,strlen($possibilities)-1)];
  return $return;
}

function newPlayer($wallet) {
  do $hash=generateHash(32);  
  while (db_num_rows(db_query("SELECT `id` FROM `players` WHERE `hash`='$hash' LIMIT 1"))!=0);
  $alias='Player_';
  $alias_i=db_fetch_array(db_query("SELECT `autoalias_increment` AS `data` FROM `system` LIMIT 1"));
  $alias_i=$alias_i['data'];
  db_query("UPDATE `system` SET `autoalias_increment`=`autoalias_increment`+1 LIMIT 1");
  db_query("INSERT INTO `players` (`hash`,`alias`,`time_last_active`,`server_seed`,`client_seed`) VALUES ('$hash','".$alias.$alias_i."',NOW(),'".random_num(32)."','".random_num(32)."')");
  header('Location: ./?unique='.$hash.'# Do Not Share This URL!');
  exit();
}

function zkrat($str,$max,$iflonger) {
  if (strlen($str)>$max) {
    $str=substr($str,0,$max).$iflonger;
  }
  return $str;
}
function n_num($num,$showall=false) {
  $r=sprintf("%.8f",$num);
  if ($showall==true) return $r;
  else return rtrim(rtrim($r,'0'),'.');
}

function validateAccess($player_id) {
  $player=db_fetch_array(db_query("SELECT `password` FROM `players` WHERE `id`=$player_id LIMIT 1"));
  session_start();
  if ($player['password']!='' && (empty($_SESSION['granted']) || $_SESSION['granted']!='yes')) {
    exit();
  }
}

function bbcode($str) {
  
  $str=str_replace( array(
                    '[B]','[/B]','[b]','[/b]','[i]','[/i]','[I]','[/I]','[U]','[/U]','[u]','[/u]','[br]','[BR]'
                  ),array(
                    '<b>','</b>','<b>','</b>','<i>','</i>','<i>','</i>','<u>','</u>','<u>','</u>','<br>','<br>'
                  ),$str);
  
  return $str;
}

function getSpin($multip) {

  switch ($multip) {
    
    case 0:
      return '-';
    case 1:
      return '[6]';
    case 2:
      return '[6] [6]';
    case 5:
      return '[6] [6] [6]';
    case 10:
      return '[5] [5] [5]';
    case 50:
      return '[4] [4] [4]';
    case 200:
      return '[3] [3] [3]';
    case 600:
      return '[2] [2] [2]';
    default:
      return '[1] [1] [1]';
    
  }

}

function profit($profit) {

  $plus = '+';
  if ($profit < 0) { $class = 'loss'; $plus = ''; }
  else if ($profit > 0) $class = 'win';
  else $class = 'neutral';
  
  return '<span class="profit-'. $class .'"><span class="st-plus">' . $plus .'</span>'. sprintf( "%.8f", $profit) . '</span>';

}

function house_edge() {

  $settings = db_fetch_array(db_query("SELECT * FROM `system` WHERE `id`=1"));

  $p_return = 3.57627869
            + 6.95228577
            + 2.38418579
            + 1.60932541
            + 8.56804848
            + 29.59871292
            + 42.60420799
            ;

  $p_return += 0.00000381 * $settings['jackpot'] * 100;

  return 100 - $p_return;

}

function maintenance() {

  $settings = db_fetch_array(db_query("SELECT `maintenance` FROM `system` LIMIT 1"));
  
  if ($settings['maintenance']) exit();

}

function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? array_reverse($files) : false;
}
?>