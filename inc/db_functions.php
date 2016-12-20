<?php

function db_query($q) {
  include __DIR__.'/db-conf.php';
  $return = mysqli_query($db,$q);
  mysqli_close($db);
  return $return;
}
function db_fetch_array($q) {
  return mysqli_fetch_array($q);
}
function db_num_rows($q) {
  return mysqli_num_rows($q);
}
function db_last_insert_id() {
  include __DIR__.'/db-conf.php';
  $return = mysqli_last_insert_id($db);
  mysqli_close($db);
  return $return;
}
function db_real_escape_string($q) {
  include __DIR__.'/db-conf.php';
  $return = mysqli_real_escape_string($db,$q);
  mysqli_close($db);
  return $return;
}

?>