<?php 
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (!isset($init)) exit();


function walletRequest($method,$params=null) {

  if ( in_array('error', $params)) {
    $res = 'error';
    $key = array_search('error', $params);
    unset($params['key']);
  }
  else $res = 'result';

  $data=array(
    'method' => $method,
    'params' => array_values((array)$params),
    'id' => $method
  );
  include __DIR__.'/driver-conf.php';
  $options=array(
    'http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/json',
      'content' => json_encode($data)
    )
  );
  
  
  $context=stream_context_create($options);
  if ($response=@file_get_contents($driver_login,false,$context)) {
    $return=json_decode($response,true);
    return $return[$res];
  }
  else return null;
}
?>