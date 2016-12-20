<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (isset($init) && $logged==true) {

  if (!empty($_POST['s_title']) && !empty($_POST['s_url']) && !empty($_POST['s_desc']) && !empty($_POST['cur']) && !empty($_POST['cur_s']) && isset($_POST['min_withdrawal']) && is_numeric((double)$_POST['min_withdrawal']) && isset($_POST['min_bet']) && is_numeric((double)$_POST['min_bet']) && isset($_POST['min_confirmations']) && is_numeric((int)$_POST['min_confirmations']) && isset($_POST['min_deposit']) && is_numeric((double)$_POST['min_deposit']) && isset($_POST['txfee']) && is_numeric((double)$_POST['txfee']) && isset($_POST['bankroll_maxbet_ratio']) && is_numeric((double)$_POST['bankroll_maxbet_ratio'])) {
    
    $w_mode = (isset($_POST['w_mode']) && $_POST['w_mode']) ? 1 : 0;
    
    db_query("UPDATE `system` SET `title`='".prot($_POST['s_title'])."',`url`='".prot($_POST['s_url'])."',`currency`='".prot($_POST['cur'])."',`min_withdrawal`=".(double)$_POST['min_withdrawal'].",`min_bet`=".(double)$_POST['min_bet'].",`min_confirmations`=".(int)$_POST['min_confirmations'].",`min_deposit`=".max(0.00000001,(double)$_POST['min_deposit']).",`currency_sign`='".prot($_POST['cur_s'])."',`description`='".prot($_POST['s_desc'])."',`bankroll_maxbet_ratio`=".(double)$_POST['bankroll_maxbet_ratio'].",`withdrawal_mode`=$w_mode WHERE `id`=1 LIMIT 1");  
    walletRequest('settxfee',array(round((double)$_POST['txfee'],8)));
    $warnStatus='<div class="zprava zpravagreen"><b>Success!</b> Data was successfuly saved.</div>';
  }
  else if (isset($_POST['s_title'])) {
    $warnStatus='<div class="zprava zpravared"><b>Error!</b> One of fields is empty.</div>';
  }
  if (isset($_POST['addons_form'])) {
    $giveaway=(isset($_POST['giveaway']))?1:0;
    $chat_enable=(isset($_POST['chat_enable']))?1:0;
    $inv_enable=(isset($_POST['inv_enable']))?1:0;
    
    db_query("UPDATE `system` SET `giveaway`=$giveaway,`giveaway_amount`=".(double)$_POST['giveaway_amount'].",`giveaway_freq`=".(int)$_POST['giveaway_freq'].",`chat_enable`=$chat_enable,`inv_enable`=$inv_enable,`inv_min`=".max(0,(double)$_POST['inv_min']).",`inv_perc`=".max(0,min((int)$_POST['inv_perc'],100))." LIMIT 1");
  }
  if (isset($_POST['jackpot'])) {
    $j = min((int)$_POST['jackpot'], 12339);
    $j = max($j, 1);
      db_query("UPDATE `system` SET `jackpot`=$j LIMIT 1");
  }
  if (isset($_POST['theme'])) {
    $theme=prot($_POST['theme']);
    $usertheme=(isset($_POST['usertheme']))?1:0;
    
    db_query("UPDATE `system` SET `usertheme`=$usertheme,`active_theme`='".$theme."' LIMIT 1");
  }

  if (isset($_GET['maintenance'])) {
    db_query("UPDATE `system` SET `maintenance`=1-`maintenance` LIMIT 1");    
    
    $maint = db_fetch_array(db_query("SELECT `maintenance` FROM `system` LIMIT 1"));
    
    if ($maint['maintenance']) $onoff = 'activated';
    else $onoff = 'deactivated';
    
    $warnStatus='<div class="zprava zpravagreen"><b>Success!</b> Maintenance mode was '.$onoff.'.</div>';    
  }
  
  if (isset($_GET['reinstall_4'])) {
    db_query("UPDATE `system` SET `installed`=0 LIMIT 1");
    
    header('Location: ../install/');
  }

}
?>