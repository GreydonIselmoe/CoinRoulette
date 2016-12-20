<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


if (!isset($init)) exit();

if (!empty($warnStatus)) {
  echo $warnStatus;
}

?>

<h1>Configuration</h1>
<br>

<fieldset>
  <legend>Basic Settings</legend>

  <form action="./?p=configs" method="post">
    <table>
      <tr>
        <td style="width: 180px;">Site Title:</td>
        <td style="width: 200px;"><input type="text" name="s_title" value="<?php echo $settings['title']; ?>"></td>
      </tr>
      <tr>
        <td>Site URL:</td>
        <td><input type="text" name="s_url" value="<?php echo $settings['url']; ?>"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="Without http://"><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr>
        <td>Site Description:</td>
        <td><input type="text" name="s_desc" value="<?php echo $settings['description']; ?>"></td>
      </tr>
      <tr>
        <td>Currency:</td>
        <td><input type="text" name="cur" value="<?php echo $settings['currency']; ?>"></td>
      </tr>
      <tr>
        <td>Currency Sign:</td>
        <td><input type="text" name="cur_s" value="<?php echo $settings['currency_sign']; ?>"></td>
      </tr>
      <tr>
        <td>Minimal bet:</td>
        <td><input type="text" name="min_bet" value="<?php echo $settings['min_bet']; ?>"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="Amount in <?php echo $settings['currency_sign']; ?>."><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr>
        <td>Minimal deposit:</td>
        <td><input type="text" name="min_deposit" value="<?php echo $settings['min_deposit']; ?>"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="Amount in <?php echo $settings['currency_sign']; ?>."><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr>
        <td>Required confirmations:</td>
        <td><input type="text" name="min_confirmations" value="<?php echo $settings['min_confirmations']; ?>"></td>
      </tr>
      <tr>
        <td>Withdraw approval:</td>
        <td>
          <select name="w_mode">
            <option value="0"<?php if (!$settings['withdrawal_mode']) echo ' selected="selected"'; ?>>Automatic</option>
            <option value="1"<?php if ($settings['withdrawal_mode']) echo ' selected="selected"'; ?>>Manual</option>
          </select>
          <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="When set to manual, each player's withdraw request must be approved in administration (Wallet section)."><span class="glyphicon glyphicon-question-sign"></span></a>
        </td>
      </tr>
      <tr>
        <td>Minimal withdrawal:</td>
        <td><input type="text" name="min_withdrawal" value="<?php echo $settings['min_withdrawal']; ?>"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="Amount in <?php echo $settings['currency_sign']; ?>."><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr>
        <td>Transaction fee:</td>
        <td><input type="text" name="txfee" value="<?php $infofee=walletRequest('getinfo'); echo $infofee['paytxfee']; ?>">  <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="Amount in <?php echo $settings['currency_sign']; ?>. Transaction fee to <?php echo $settings['currency']; ?> network. This is covered by casino for all withdrawals."><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr>
        <td>Bankroll/max bet ratio</td>
        <td><input type="text" name="bankroll_maxbet_ratio" value="<?php echo $settings['bankroll_maxbet_ratio']; ?>"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="The default ratio between amount in wallet and max available bet is set to 25. So for example if you want to allow players to bet 1 <?php echo $settings['currency_sign']; ?>, you have to have 25 <?php echo $settings['currency_sign']; ?> in server bankroll."><span class="glyphicon glyphicon-question-sign"></span></a></td>
      </tr>
      <tr><td colspan="3" style="height: 20px;"></td></tr>
      <tr><td colspan="3" style="border-top: 1px solid gray; height: 20px;"></td></tr>
      <tr>
        <td>Jackpot multiplier</td>
        <td><input type="number" name="jackpot" value="<?php echo $settings['jackpot']; ?>" max="12339" min="0"> <a href="#" style="color: #4F556C;" onclick="javascript:return false;" title="The lower this amount is, the higher is the house edge."><span class="glyphicon glyphicon-question-sign"></span></a></td>
        <td style="padding-top: 3px;"><small><i>Current expected house edge: <b><?php echo round(house_edge(), 4); ?></b> %</i></small></td>
      </tr>
      <tr><td colspan="3" style="height: 20px;"></td></tr>
      <tr><td colspan="3" style="border-top: 1px solid gray; height: 20px;"></td></tr>
      <tr>
        <td></td>
        <td><input type="submit" value="Save"></td>
      </tr>
    </table>
  </form>
  
  <style type="text/css">
    form table tr td a {
      font-size: 14px;  
    }
  </style>

</fieldset>

<fieldset style="margin-top: 10px;">
  <legend>System Maintenance</legend>
  <table style="width: 45%;">
  
    <?php
      if (!$settings['maintenance']) $onoff = 'Activate';
      else $onoff = 'Deactivate';
    ?>
  
    <tr>
      <td style="padding-top: 5px;">Maintenance Mode:</td>
      <td><button style="padding: 4px;" onclick="javascript:location.href='./?p=configs&maintenance';"><?php echo $onoff; ?></button></td>
    </tr>
    <tr>
      <td style="padding-top: 5px;">Reinstall CoinSlots:</td>
      <td><button style="padding: 4px;" onclick="javascript:if(confirm('WARNING! This will enable anyone to access the script installer until the installation is done. Do you want to proceed?')){location.href='./?p=configs&reinstall_4';}">Reinstall</button></td>
    </tr>
  </table>
  
</fieldset>

