<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


if (!isset($init)) exit();


$perPage=20;
  









if (!empty($_GET['approveTX']) || !empty($_GET['denyTX'])) {
    
  if (!empty($_GET['approveTX'])) $m = 'approve';
  else $m = 'deny';
    
  $id = ($m == 'approve') ? (int)$_GET['approveTX'] : (int)$_GET['denyTX'];
    
  $tx_q = db_query("SELECT * FROM `withdrawals` WHERE `id`=$id LIMIT 1");
    
  if (db_num_rows($tx_q) != 0) {
    
    $tx = db_fetch_array($tx_q);
      
    db_query("DELETE FROM `withdrawals` WHERE `id`=$tx[id] LIMIT 1");
      
    if ($m == 'deny') {
      db_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND(`balance`+$tx[amount],9),8) WHERE `id`=$tx[player_id] LIMIT 1");
      
      echo '<div class="zprava zpravagreen"><b>Success:</b> Withdrawal rejected.</div>';      
    }
    else {

      $amount = (double)$tx['amount'] * 1;
        
      $txid = walletRequest('sendtoaddress', array($tx['address'], $amount) );

      echo '<div class="zprava zpravagreen"><b>Success:</b> Withdrawal approved.<br>Transaction ID: <i>'.$txid.'</i></div>';
        
      db_query("INSERT INTO `transactions` (`player_id`,`amount`,`txid`) VALUES ($tx[player_id],($amount*-1),'$txid')"); 
    } 
  }   
} 











$page=1;
if (!empty($_GET['_page']) && is_numeric($_GET['_page']) && is_int((int)$_GET['_page'])) {
  $page=(int)$_GET['_page'];
  $lima=-$perPage+($page*$perPage);
}
else $lima=0;  

$query_=db_query("SELECT * FROM `transactions` ORDER BY `time` DESC LIMIT $lima,$perPage");
$pocet=db_num_rows(db_query("SELECT `id` FROM `transactions`"));
$pages_=$pocet/$perPage;
$xplosion=explode('.',(string)$pages_);
$pages=(int)$xplosion[0]+1;

if ($page==1) {

  $wal_balance=walletRequest('getbalance');
  if (isset($_POST['_am']) && isset($_POST['_adr'])) {
    if (!empty($_POST['_am']) && is_numeric($_POST['_am'])) {
      $amount=(double)$_POST['_am'];
      if (!empty($_POST['_adr'])) {
        $validate=walletRequest('validateaddress',array($_POST['_adr']));
        if ($validate['isvalid']==true) {
          if ($amount<=$wal_balance) {
            $txid=walletRequest('sendtoaddress',array($_POST['_adr'],$amount));
            echo '<div class="zprava zpravagreen"><b>Success:</b> Amount was sent.<br>Transaction ID: <i>'.$txid.'</i></div>';
          }
          else echo '<div class="zprava zpravared"><b>Error:</b> Wallet has insufficient fund.</div>';
        }
        else echo '<div class="zprava zpravared"><b>Error:</b> '.$settings['currency'].' address is not valid.</div>';      
      }
      else echo '<div class="zprava zpravared"><b>Error:</b> '.$settings['currency'].' address is not valid.</div>';
    }
    else echo '<div class="zprava zpravared"><b>Error:</b> Amount is not numeric.</div>';
  }
  
  ?>
  <h1>Wallet</h1>
  <div class="zprava">
  <b>Receiving address:</b><br>
  <big>
  <?php
    echo walletRequest('getnewaddress');
  ?>
  </big>
  </div>
  
  <div class="zprava">
    <b>Withdraw:</b><br>
    <form action="./?p=wallet" method="post">
      Amount: <input type="text" name="_am"> <?php echo $settings['currency_sign']; ?> address: <input type="text" name="_adr"> <input type="submit" value="Withdraw">
    </form>
  </div>
  <div class="zprava">
    <table style="border: 0; border-collapse: collapse;">
      <tr>
        <td style="padding: 0; vertical-align: middle;">
          <b>Total balance:</b><br>
          <big><?php echo n_num($wal_balance,true); ?></big> <?php echo $settings['currency_sign']; ?>
          <br><br>
          <b>Free balance:</b><br>
          <big><?php $usersdeps_=db_fetch_array(db_query("SELECT SUM(`amount`) AS `sum` FROM `deposits`")); $usersdeps_['sum']=(0+(double)$usersdeps_['sum']);  $usersbal_=db_fetch_array(db_query("SELECT SUM(`balance`) AS `sum` FROM `players`")); $usersbal_['sum']=(0+(double)$usersbal_['sum']); echo n_num($wal_balance-$usersbal_['sum']-$usersdeps_['sum'],true); ?></big> <?php echo $settings['currency_sign']; ?>
        </td>
        <td style="vertical-align: middle;">
          <b>Reserved balance (users):</b><br>
          <big><?php echo n_num($usersbal_['sum'],true); ?></big> <?php echo $settings['currency_sign']; ?>
          <br><br>
          <b>Reserved deposits (users):</b><br>
          <big><?php echo n_num($usersdeps_['sum'],true); ?></big> <?php echo $settings['currency_sign']; ?>        
        </td>
      </tr>
    </table>
  </div>






  <?php if ($settings['withdrawal_mode']) { ?>
    <fieldset style="margin-top: 10px;">
      <legend>Pending Withdrawals</legend>
      <table class="vypis_table">
        <tr class="vypis_table_head">
          <th>Time</th>
          <th>Player</th>
          <th>Amount</th>
          <th>Address</th>
          <th>Action</th>
        </tr>
      
        <?php
        
        $penquery_ = db_query("SELECT * FROM `withdrawals`");
        
        while ($tx = db_fetch_array($penquery_)) {
          if (db_num_rows(db_query("SELECT `alias` FROM `players` WHERE `id`=$tx[player_id] LIMIT 1"))!=0)
            $player=db_fetch_array(db_query("SELECT `alias` FROM `players` WHERE `id`=$tx[player_id] LIMIT 1"));
          else $player['alias']='[unknown]';
    
          $amount=n_num($tx['amount'],true);
          
          echo '<tr class="vypis_table_obsah">';
          echo '<td><small><small>'.str_replace(' ','<br>',$tx['time']).'</small></small></td>';
          echo '<td><small>'.$player['alias'].'</small></td>';
          echo '<td><small>'.$amount.'</small></td>';
          echo '<td><small><small>'.$tx['address'].'</small></small></td>';
          echo '<td><a title="Approve" href="#" onclick="javascript:wr_approve('.$tx['id'].');return false;"><span class="glyphicon glyphicon-ok"></a>&nbsp;&nbsp;<a title="Disapprove (return money to player)" href="#" onclick="javascript:wr_deny('.$tx['id'].');return false;"><span class="glyphicon glyphicon-remove"></a></td>';
          echo '</tr>';
          
        }
        if (!db_num_rows($penquery_)) echo '<tr><td colspan="5"><i><small>No pending deposits.</small></i></td></tr>';
        ?>
      </table>
    </fieldset>
    
    <script type="text/javascript">
    
      function wr_approve(tid) {
        if (!confirm('Do you really want to make this payment?')) return;
        location.href = './?p=wallet&approveTX='+tid;
      }
      function wr_deny(tid) {
        location.href = './?p=wallet&denyTX='+tid;    
      }
    
    </script>
  <?php } ?>
  
<?php } ?>

<fieldset style="margin-top: 10px;">
  <legend>Transactions</legend>
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
        echo '<a style="text-decoration: '.$t_dec.';" href="./?p=wallet&_page='.($i+1).'">'.($i+1).'</a> ';
      }
      if ($pages_real>$pages) echo ' ...';
    ?>
  </div>
  <table class="vypis_table">
    <tr class="vypis_table_head">
      <th>Time</th>
      <th>Player</th>
      <th>Amount</th>
      <th>Transaction ID</th>
    </tr>
  
    <?php
    while ($tx=db_fetch_array($query_)) {
      if (db_num_rows(db_query("SELECT `alias` FROM `players` WHERE `id`=$tx[player_id] LIMIT 1"))!=0)
        $player=db_fetch_array(db_query("SELECT `alias` FROM `players` WHERE `id`=$tx[player_id] LIMIT 1"));
      else $player['alias']='[unknown]';

      $amount=n_num($tx['amount'],true);
      if ($amount>0) {
        $am_class='win';
        $amount='+'.$amount;
      }
      else $am_class='lose';
      
      echo '<tr class="vypis_table_obsah">';
      echo '<td><small><small>'.str_replace(' ','<br>',$tx['time']).'</small></small></td>';
      echo '<td><small>'.$player['alias'].'</small></td>';
      echo '<td class="'.$am_class.'"><small>'.$amount.'</small></td>';
      echo '<td><small><small>'.$tx['txid'].'</small></small></td>';
      echo '</tr>';
    }
    ?>
  </table>
</fieldset>



