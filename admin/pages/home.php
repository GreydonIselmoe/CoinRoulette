<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


if (!isset($init)) exit();
?>
<h1>Stats</h1>
<table class="vypis_table">
  <tr class="vypis_table_obsah">
    <td>Number of bets:</td>
    <td><b><?php echo $settings['t_bets']; ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td>Total wagered:</td>
    <td><b><?php echo sprintf("%.8f",$settings['t_wagered']); ?></b> <?php echo $settings['currency_sign']; ?></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: green;">Wins:</td>
    <td style="color: green;"><b><?php echo $settings['t_wins']; ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td>Ties:</td>
    <td><b><?php echo $settings['t_ties']; ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: #d10000;">Losses:</td>
    <td style="color: #d10000;"><b><?php echo ($settings['t_loses']); ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: #a06d00;">W/L ratio:</td>
    <td style="color: #a06d00;"><b><?php if (($settings['t_loses'])>0) echo sprintf("%.3f",$settings['t_wins']/($settings['t_loses'])); else echo 0; ?></b></td>
  </tr>
</table>
<?php if ($settings['inv_enable']==1) { ?>
  <fieldset style="margin-top: 7px;">
    <legend>Invest Stats</legend>
    <table class="vypis_table" style="width: 50%;">
      <tr class="vypis_table_obsah">
        <td>Total Investors:</td>
        <td><b><?php echo db_num_rows(db_query("SELECT `id` FROM `investors` WHERE `amount`!=0")); ?></b></td>
      </tr>
      <tr class="vypis_table_obsah">
        <td title="Total invested by investors">Total Invested:</td>
        <td><b><?php $tsum=db_fetch_array(db_query("SELECT SUM(`amount`) AS `am` FROM `investors` WHERE `amount`!=0")); echo sprintf("%.8f",$tsum['am']); ?></b> <?php echo $settings['currency_sign']; ?></td>
      </tr>
      <tr class="vypis_table_obsah">
        <td title="= free balance">House Investment:</td>
        <td><b><?php $usersinv_=db_fetch_array(db_query("SELECT SUM(`amount`) AS `sum` FROM `investors` WHERE `amount`!=0")); $usersinv_['sum']=($settings['inv_enable']==1)?(0+(double)$usersinv_['sum']):0; $usersdeps_=db_fetch_array(db_query("SELECT SUM(`amount`) AS `sum` FROM `deposits`")); $usersdeps_['sum']=(0+(double)$usersdeps_['sum']);  $usersbal_=db_fetch_array(db_query("SELECT SUM(`balance`) AS `sum` FROM `players`")); $usersbal_['sum']=(0+(double)$usersbal_['sum']); echo sprintf("%.8f",walletRequest('getbalance')-$usersbal_['sum']-$usersdeps_['sum']-$usersinv_['sum']); ?></b> <?php echo $settings['currency_sign']; ?></td>
      </tr>
      <tr class="vypis_table_obsah">
        <td>Total Investor's Profit:</td>
        <td><b><?php $tsum=db_fetch_array(db_query("SELECT SUM(`profit`) AS `am` FROM `investors` WHERE `profit`!=0")); if ($tsum['am']<0) echo '<span style="color: #d10000;">'.sprintf("%.8f",$tsum['am']).'</span>'; else echo '<span style="color: green;">'.sprintf("%.8f",$tsum['am']).'</span>'; ?></b> <?php echo $settings['currency_sign']; ?></td>
      </tr>
      <tr class="vypis_table_obsah">
        <td title="Commision + investement from Free balance">Total house profit:</td>
        <td><b><?php if ($settings['inv_casprofit']<0) echo '<span style="color: #d10000;">'.sprintf("%.8f",$settings['inv_casprofit']).'</span>'; else echo '<span style="color: green;">'.sprintf("%.8f",$settings['inv_casprofit']).'</span>'; ?></b> <?php echo $settings['currency_sign']; ?></td>
      </tr>
    </table>
  </fieldset>
<?php } ?>
<br><br>
<table class="vypis_table">
  <tr class="vypis_table_head">
    <th>Period</th>
    <th>Real house edge</th>
    <th>Profit</th>
  </tr>
  <tr>
    <td>Last hour</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 1 HOUR")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 24h</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 24 HOUR")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 7d</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 7 DAY")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 30d</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 30 DAY")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 6m</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 6 MONTH")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 12m</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins` WHERE `time`>NOW()-INTERVAL 12 MONTH")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Since start</td>
    <td><?php $this_q=db_fetch_array(db_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `spins`")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
</table>