<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (!isset($included_)) exit();

$fp=file('db.sql',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
$query='';
foreach ($fp as $line) {
  if ($line!='' && strpos($line,'--')===false) {
    $query.=$line;
    if (substr($query,-1)==';') {
      mysqli_query($db,$query);
        $query='';
    }
  }
}
?>