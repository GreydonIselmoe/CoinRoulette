<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


mysqli_report(MYSQLI_REPORT_STRICT);

try {
     $con = new mysqli($_GET['db_host'],$_GET['db_user'],$_GET['db_pass'],$_GET['db_db']);
} catch (Exception $e ) {
     echo json_encode(array('error'=>'yes'));
     exit();
}

echo json_encode(array('error'=>'no'));

?>