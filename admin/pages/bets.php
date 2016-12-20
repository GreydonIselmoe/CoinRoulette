<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


if (!isset($init)) exit();
  
$perPage=20;
  
$page=1;
if (!empty($_GET['_page']) && is_numeric($_GET['_page']) && is_int((int)$_GET['_page'])) {
  $page=(int)$_GET['_page'];
  $lima=-$perPage+($page*$perPage);
}
else $lima=0;  

$query_=db_query("SELECT * FROM `spins` WHERE `bet_amount`!=0 ORDER BY `time` DESC LIMIT $lima,$perPage");
$pocet=db_num_rows(db_query("SELECT `id` FROM `spins` WHERE `bet_amount`!=0"));
$pages_=$pocet/$perPage;
$xplosion=explode('.',(string)$pages_);
$pages=(int)$xplosion[0]+1;

?>
<h1>Bets</h1>
<div class="strankovani">
  Page: 
  <?php
    $pagesvetsi=false;
    $pages_real=$pages;
    if ($pages>15) {
      $pagesvetsi=true;
      $pages=15;
    }
    $e=0;

    if ($pagesvetsi) {
      if ($page>8) {
        $e=$page-8;
        $pages=$page+7;
        if ($pages>$pages_real) $pages=$pages_real;
      }
    }
    if ($e!=0) echo '... ';
    for ($i=$e;$i<$pages;$i++) {
      $t_dec=(($i+1)==$page)?'underline':'none';
      echo '<a style="text-decoration: '.$t_dec.';" href="./?p=bets&_page='.($i+1).'">'.($i+1).'</a> ';
    }
    if ($pages_real>$pages) echo ' ...';
  ?>
</div>

<table class="vypis_table">
  <tr class="vypis_table_head">
    <th>ID</th>
    <th>Player</th>
    <th>Time</th>
    <th>Bet</th>
    <th>Multiplier</th>
    <th>Player's Profit</th>
  </tr>
  <?php
  while ($row=db_fetch_array($query_)) {
    if (db_num_rows(db_query("SELECT `alias` FROM `players` WHERE `id`=$row[player] LIMIT 1"))!=0)
      $player=db_fetch_array(db_query("SELECT `alias` FROM `players` WHERE `id`=$row[player] LIMIT 1"));
    else $player['alias']='[unknown]';
    

    echo '<tr class="vypis_table_obsah">';
    echo '<td><small>'.$row['id'].'</small></td>';
    echo '<td title="'.$player['alias'].'" onclick="javascript:prompt(\'Alias:\',\''.$player['alias'].'\');"><small>'.zkrat($player['alias'],10,'<b>...</b>').'</small></td>';
    echo '<td><small><small>'.str_replace(' ','<br>',$row['time']).'</small></small></td>';
    echo '<td><small><b>'.n_num($row['bet_amount']).'</b> '.$settings['currency_sign'].'</small></td>';
    echo '<td><small>'.$row['multiplier'].'</small></td>';
    echo '<td><small>'.profit($row['payout']-$row['bet_amount']).'</small></td>';
    echo '</tr>'."\n";
  }
    
  ?>
</table>
