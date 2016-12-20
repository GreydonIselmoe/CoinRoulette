<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


if (!isset($init)) exit();

if (isset($_POST['addons_form']))
    echo '<div class="zprava zpravagreen"><b>Success!</b> Data was successfuly saved.</div>';  

?>
<h1>Addons</h1>
<form method="post" action="./?p=addons">
  <input type="hidden" name="addons_form" value="1">
  <fieldset>
    <legend>Chat</legend>
    <input type="checkbox" value="1"<?php if ($settings['chat_enable']==1) echo ' checked="checked"'; ?> id="chat_chckbx" name="chat_enable">
    <label for="chat_chckbx" class="chckbxLabel">Enable</label>
  </fieldset>
  <fieldset style="margin-top: 10px;">
    <legend>Free Coins (giveaway)</legend>
    <table style="border: 0; border-collapse: collapse;">
      <tr>
        <td style="padding: 0;">
          <input type="checkbox" value="1"<?php if ($settings['giveaway']==1) echo ' checked="checked"'; ?> id="giveaway" name="giveaway">
          <label for="giveaway" class="chckbxLabel">Enable</label>
        </td>
        <td style="padding-left: 40px;">
          <table style="border: 0; border-collapse: collapse;">
            <tr>
              <td>Amount:</td>
              <td>
                <input type="text" name="giveaway_amount" value="<?php echo $settings['giveaway_amount']; ?>"> <?php echo $settings['currency_sign']; ?><br>
              </td>
            </tr>
            <tr>
              <td>Frequency:</td>
              <td>
                <input type="text" name="giveaway_freq" value="<?php echo $settings['giveaway_freq']; ?>"> s &nbsp;&nbsp;<small><i>Minimal time between requests</i></small>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <small>
      <i>
        <b>Note:</b> To activate the giveaway addon it is required to have installed <b>GD lib</b> (php5-gd). Otherwise, this addon will not function correctly.
      </i>
    </small>
  </fieldset>
  <fieldset style="margin-top: 10px;">
    <legend>Invest Feature</legend>
    <table style="border: 0; border-collapse: collapse; margin-bottom: 20px;">
      <tr>
        <td style="padding: 0;">
          <input type="checkbox" value="1"<?php if ($settings['inv_enable']==1) echo ' checked="checked"'; ?> id="inv_chckbx" name="inv_enable">
          <label for="inv_chckbx" class="chckbxLabel">Enable</label>
        </td>
        <td style="padding-left: 40px;">
          <table style="border: 0; border-collapse: collapse;">
            <tr>
              <td>Comission to house from profit:</td>
              <td>
                <input type="number" name="inv_perc" min="0" max="100" value="<?php echo $settings['inv_perc']; ?>"> %<br>
              </td>
            </tr>
            <tr>
              <td>Minimal Investment:</td>
              <td>
                <input type="text" name="inv_min" value="<?php echo $settings['inv_min']; ?>"> <?php $echo['currency_sign']; ?><br>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <small>
      <i>
        <b>Note:</b> Comission to house from profit determines how much more percentage you get from the profit. For example if you set it to 50%, you'll get 50% of all profits. The other 50% is split between all investors (including house investment) according to their percentage from total bank roll.
        So in total you'll get 50% from profits + your share according to your percentage from total bank roll.
      </i>
    </small>
  </fieldset>
  <div style="width: 100%;text-align: center;">
    <input type="submit" value="Save" style="margin-top: 10px;margin-left: auto;margin-right: auto;">
  </div>
</form>