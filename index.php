<?php

header('X-Frame-Options: DENY'); 

$init=true;
include __DIR__.'/inc/start.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $settings['title'].' - '.$settings['description']; ?></title>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="./content/styles/jquery-ui.min.css">
    <link type="text/css" rel="stylesheet" href="./content/styles/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="./content/styles/mcs.css">
    <link type="text/css" rel="stylesheet" href="./content/styles/main.css">
    <link rel="icon" href="./content/styles/imgs/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="./content/scripts/jquery.js"></script>
    <script type="text/javascript" src="./content/scripts/jquery-ui.min.js"></script>
    <script type="text/javascript" src="./content/scripts/bootstrap.js"></script>
    <script type="text/javascript" src="./content/scripts/qrlib.js"></script>
    <script type="text/javascript" src="./content/scripts/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="./content/scripts/mcs.min.js"></script>
    <script type="text/javascript" src="./content/scripts/sha256.js"></script>
    <script type="text/javascript" src="./content/scripts/main.js"></script>
    <script type="text/javascript" src="./content/scripts/jQueryRotate.js"></script>
    <script type="text/javascript">
      function unique() {
        return '<?php echo $unique; ?>';
      }
      function cursig() {
        return '<?php echo $settings['currency_sign']; ?>';
      }
      function giveaway_freq() {
        return '<?php echo $settings['giveaway_freq']; ?>';
      }
      function min_inv() {
        return '<?php echo $settings['inv_min']; ?>';
      }
      function default_theme() {
        return '<?php echo $settings['active_theme']; ?>';
      }
    </script>
    <audio id="audio-spinning">
      <source src="content/sounds/spinning/spinning.mp3" type="audio/mpeg">
      <source src="content/sounds/spinning/spinning.ogg" type="audio/mpeg">
    </audio>
    <audio id="audio-success">
      <source src="content/sounds/success/success.mp3" type="audio/mpeg">
      <source src="content/sounds/success/success.ogg" type="audio/ogg">
    </audio>
    <audio id="audio-lose">
      <source src="content/sounds/lose/lose.mp3" type="audio/mpeg">
      <source src="content/sounds/lose/lose.ogg" type="audio/ogg">
    </audio>
    <audio id="audio-chips">
      <source src="content/sounds/chips/blackjack_chip.mp3" type="audio/wav">
    </audio>
  </head>
  <body>
    <div id="menu">
      <div class="logo">
      <a href="./"><?php echo $settings['title']; ?></a>
      </div>
      <div class="nav-right">
        <div class="bal_main">
          <div class="bal_title">Balance</div>
          <div class="bal_status"><span class="balance"><?php echo n_num($player['balance'], true); ?></span> <?php echo $settings['currency_sign']; ?></div>
        </div>
        <button class="withdraw_btn btn btn-primary">Withdraw</button>
        <button class="deposit_btn btn btn-primary">Deposit</button>
      </div>
    </div>
    <div class="leftblock"></div>
    <div class="page">
      <div class="game">
        <a href="#" class="closeLeft" onclick="javascript:leftbox.toggle();return false;"><span class="glyphicon glyphicon-remove"></span></a>
        <div class="leftbuttons">
          <button data-toggle="tooltip" data-placement="right" title="My&nbsp;Account" onclick="javascript:leftCon('profile');"><span class="glyphicon glyphicon-user"></span></button>
          <button data-toggle="tooltip" data-placement="right" title="Provably&nbsp;Fair" onclick="javascript:leftCon('fair');"><span class="glyphicon glyphicon-ok"></span></button>
          <button data-toggle="tooltip" data-placement="right" title="Stats" onclick="javascript:leftCon('stats');"><span class="glyphicon glyphicon-stats"></span></button>
          <button data-toggle="tooltip" data-placement="right" title="News" onclick="javascript:leftCon('news');"><span class="glyphicon glyphicon-flag"></span></button>
          <?php if ($settings['chat_enable']) { ?>
          <button data-toggle="tooltip" data-placement="right" title="Chat" onclick="javascript:leftCon('chat');"><span class="glyphicon glyphicon-comment"></span></button>
          <?php } ?>
          <?php if ($settings['giveaway']) { ?>
          <button data-toggle="tooltip" data-placement="right" title="Giveaway" onclick="javascript:leftCon('giveaway');"><span class="glyphicon glyphicon-gift"></span></button>
          <?php } ?>
          <?php if ($settings['inv_enable']) { ?>
          <button data-toggle="tooltip" data-placement="right" title="Invest" onclick="javascript:leftCon('invest');" class="last-child"><span class="glyphicon glyphicon-briefcase"></span></button>
          <?php } ?>
        </div>

        <div id="roulette">
          <div class="container" id="game">
            <div class="wheel">
              <img id="wheel" src="content/images/wheel.png">
              <img id="ball" src="content/images/ball.png">
            </div>
            <div class="control">

              <div id="total">Total amount: 0</div>
              <div id="history">
                <div id="result-number"></div>
                <div id="result"></div>
              </div>
             
              <div id="conversion-notice">10 credits = 0.000001 BTC, 1000 credits = 0.0001 BTC</div>
              <div class="board">
                <div class="board-cell bet-0" data-bet-position="0"><div class="number">0</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1" class="board-cell bcol-1 brow-3 red-cell"><div class="number">1</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2" class="board-cell bcol-1 brow-2 black-cell"><div class="number">2</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="3" class="board-cell bcol-1 brow-1 red-cell"><div class="number">3</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="4" class="board-cell bcol-2 brow-3 black-cell"><div class="number">4</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="5" class="board-cell bcol-2 brow-2 red-cell"><div class="number">5</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="6" class="board-cell bcol-2 brow-1 black-cell"><div class="number">6</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="7" class="board-cell bcol-3 brow-3 red-cell"><div class="number">7</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="8" class="board-cell bcol-3 brow-2 black-cell"><div class="number">8</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="9" class="board-cell bcol-3 brow-1 red-cell"><div class="number">9</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="10" class="board-cell bcol-4 brow-3 black-cell"><div class="number">10</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="11" class="board-cell bcol-4 brow-2 black-cell"><div class="number">11</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="12" class="board-cell bcol-4 brow-1 red-cell"><div class="number">12</div><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="13" class="board-cell bcol-5 brow-3 black-cell"><div class="number">13</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="14" class="board-cell bcol-5 brow-2 red-cell"><div class="number">14</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="15" class="board-cell bcol-5 brow-1 black-cell"><div class="number">15</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="16" class="board-cell bcol-6 brow-3 red-cell"><div class="number">16</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="17" class="board-cell bcol-6 brow-2 black-cell"><div class="number">17</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="18" class="board-cell bcol-6 brow-1 red-cell"><div class="number">18</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19" class="board-cell bcol-7 brow-3 red-cell"><div class="number">19</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="20" class="board-cell bcol-7 brow-2 black-cell"><div class="number">20</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="21" class="board-cell bcol-7 brow-1 red-cell"><div class="number">21</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="22" class="board-cell bcol-8 brow-3 black-cell"><div class="number">22</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="23" class="board-cell bcol-8 brow-2 red-cell"><div class="number">23</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="24" class="board-cell bcol-8 brow-1 black-cell"><div class="number">24</div><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="25" class="board-cell bcol-9 brow-3 red-cell"><div class="number">25</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="26" class="board-cell bcol-9 brow-2 black-cell"><div class="number">26</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="27" class="board-cell bcol-9 brow-1 red-cell"><div class="number">27</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="28" class="board-cell bcol-10 brow-3 black-cell"><div class="number">28</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="29" class="board-cell bcol-10 brow-2 black-cell"><div class="number">29</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="30" class="board-cell bcol-10 brow-1 red-cell"><div class="number">30</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="31" class="board-cell bcol-11 brow-3 black-cell"><div class="number">31</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="32" class="board-cell bcol-11 brow-2 red-cell"><div class="number">32</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="33" class="board-cell bcol-11 brow-1 black-cell"><div class="number">33</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="34" class="board-cell bcol-12 brow-3 red-cell"><div class="number">34</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="35" class="board-cell bcol-12 brow-2 black-cell"><div class="number">35</div><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="36" class="board-cell bcol-12 brow-1 red-cell"><div class="number">36</div><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="1,4,7,10,13,16,19,22,25,28,31,34" class="multiple-bet board-cell bcol-13 brow-3 bet-r3">2:1<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,5,8,11,14,17,20,23,26,29,32,35" class="multiple-bet board-cell bcol-13 brow-2">2:1<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="3,6,9,12,15,18,21,24,27,30,33,36" class="multiple-bet board-cell bcol-13 brow-1 bet-r1">2:1<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,2,3,4,5,6,7,8,9,10,11,12" class="multiple-bet board-cell small-cell brow-4 bcol-1">1<sup>st</sup>&nbsp; 12<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="13,14,15,16,17,18,19,20,21,22,23,24" class="multiple-bet board-cell small-cell brow-4 bcol-5">2<sup>nd</sup>&nbsp; 12<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="25,26,27,28,29,30,31,32,33,34,35,36" class="multiple-bet board-cell small-cell brow-4 bcol-9 bet-3rd-12">3<sup>rd</sup>&nbsp; 12<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18" class="multiple-bet board-cell big-cell brow-5 bcol-1 bet-1-18">1 - 18<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36" class="multiple-bet board-cell big-cell brow-5 bcol-3">Even<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36" class="multiple-bet board-cell big-cell brow-5 bcol-5 red-cell"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,4,6,8,10,11,13,15,17,20,22,24,26,28,29,31,33,35" class="multiple-bet board-cell big-cell brow-5 bcol-7 black-cell"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35" class="multiple-bet board-cell big-cell brow-5 bcol-9">Odd<div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36" class="multiple-bet board-cell big-cell brow-5 bcol-11 bet-19-36">19 - 36<div class="chip" data-bet="0">0</div></div>


                <div data-bet-position="1,2,3" class="horizontal-line-bet bcol-1 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,3" class="horizontal-line-bet bcol-1 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,2" class="horizontal-line-bet bcol-1 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="1,2,3,4,5,6" class="corner-bet bcol-2 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="3,6" class="vertical-line-bet bcol-2 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,3,5,6" class="corner-bet bcol-2 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="2,5" class="vertical-line-bet bcol-2 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,2,4,5" class="corner-bet bcol-2 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="1,4" class="vertical-line-bet bcol-2 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="4,5,6" class="horizontal-line-bet bcol-2 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="5,6" class="horizontal-line-bet bcol-2 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="4,5" class="horizontal-line-bet bcol-2 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="4,5,6,7,8,9" class="corner-bet bcol-3 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="6,9" class="vertical-line-bet bcol-3 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="5,6,8,9" class="corner-bet bcol-3 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="5,8" class="vertical-line-bet bcol-3 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="4,5,7,8" class="corner-bet bcol-3 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="4,7" class="vertical-line-bet bcol-3 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="7,8,9" class="horizontal-line-bet bcol-3 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="8,9" class="horizontal-line-bet bcol-3 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="7,8" class="horizontal-line-bet bcol-3 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="7,8,9,10,11,12" class="corner-bet bcol-4 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="9,12" class="vertical-line-bet bcol-4 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="8,9,11,12" class="corner-bet bcol-4 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="8,11" class="vertical-line-bet bcol-4 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="7,8,10,11" class="corner-bet bcol-4 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="7,10" class="vertical-line-bet bcol-4 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="10,11,12" class="horizontal-line-bet bcol-4 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="11,12" class="horizontal-line-bet bcol-4 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="10,11" class="horizontal-line-bet bcol-4 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="10,11,12,13,14,15" class="corner-bet bcol-5 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="12,15" class="vertical-line-bet bcol-5 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="11,12,14,15" class="corner-bet bcol-5 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="11,14" class="vertical-line-bet bcol-5 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="10,11,13,14" class="corner-bet bcol-5 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="10,13" class="vertical-line-bet bcol-5 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="13,14,15" class="horizontal-line-bet bcol-5 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="14,15" class="horizontal-line-bet bcol-5 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="13,14" class="horizontal-line-bet bcol-5 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="13,14,15,16,17,18" class="corner-bet bcol-6 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="15,18" class="vertical-line-bet bcol-6 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="14,15,17,18" class="corner-bet bcol-6 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="14,17" class="vertical-line-bet bcol-6 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="13,14,16,17" class="corner-bet bcol-6 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="13,16" class="vertical-line-bet bcol-6 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="16,17,18" class="horizontal-line-bet bcol-6 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="17,18" class="horizontal-line-bet bcol-6 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="16,17" class="horizontal-line-bet bcol-6 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="16,17,18,19,20,21" class="corner-bet bcol-7 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="18,21" class="vertical-line-bet bcol-7 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="17,18,20,21" class="corner-bet bcol-7 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="17,20" class="vertical-line-bet bcol-7 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="16,17,19,20" class="corner-bet bcol-7 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="16,19" class="vertical-line-bet bcol-7 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19,20,21" class="horizontal-line-bet bcol-7 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="20,21" class="horizontal-line-bet bcol-7 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19,20" class="horizontal-line-bet bcol-7 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="19,20,21,22,23,24" class="corner-bet bcol-8 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="21,24" class="vertical-line-bet bcol-8 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="20,21,23,24" class="corner-bet bcol-8 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="20,23" class="vertical-line-bet bcol-8 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19,20,22,23" class="corner-bet bcol-8 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="19,22" class="vertical-line-bet bcol-8 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="22,23,24" class="horizontal-line-bet bcol-8 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="23,24" class="horizontal-line-bet bcol-8 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="22,23" class="horizontal-line-bet bcol-8 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="22,23,24,25,26,27" class="corner-bet bcol-9 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="24,27" class="vertical-line-bet bcol-9 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="23,24,26,27" class="corner-bet bcol-9 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="23,26" class="vertical-line-bet bcol-9 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="22,23,25,26" class="corner-bet bcol-9 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="22,25" class="vertical-line-bet bcol-9 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="25,26,27" class="horizontal-line-bet bcol-9 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="26,27" class="horizontal-line-bet bcol-9 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="25,26" class="horizontal-line-bet bcol-9 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="25,26,27,28,29,30" class="corner-bet bcol-10 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="27,30" class="vertical-line-bet bcol-10 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="26,27,29,30" class="corner-bet bcol-10 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="26,29" class="vertical-line-bet bcol-10 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="25,26,28,29" class="corner-bet bcol-10 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="25,28" class="vertical-line-bet bcol-10 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="28,29,30" class="horizontal-line-bet bcol-10 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="29,30" class="horizontal-line-bet bcol-10 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="28,29" class="horizontal-line-bet bcol-10 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="28,29,30,31,32,33" class="corner-bet bcol-11 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="30,33" class="vertical-line-bet bcol-11 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="29,30,32,33" class="corner-bet bcol-11 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="29,32" class="vertical-line-bet bcol-11 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="28,29,31,32" class="corner-bet bcol-11 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="28,31" class="vertical-line-bet bcol-11 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="31,32,33" class="horizontal-line-bet bcol-11 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="32,33" class="horizontal-line-bet bcol-11 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="31,32" class="horizontal-line-bet bcol-11 brow-3"><div class="chip" data-bet="0">0</div></div>

                <div data-bet-position="31,32,33,34,35,36" class="corner-bet bcol-12 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="33,36" class="vertical-line-bet bcol-12 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="32,33,35,36" class="corner-bet bcol-12 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="32,35" class="vertical-line-bet bcol-12 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="31,32,34,35" class="corner-bet bcol-12 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="31,34" class="vertical-line-bet bcol-12 brow-3"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="34,35,36" class="horizontal-line-bet bcol-12 brow-1"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="35,36" class="horizontal-line-bet bcol-12 brow-2"><div class="chip" data-bet="0">0</div></div>
                <div data-bet-position="34,35" class="horizontal-line-bet bcol-12 brow-3"><div class="chip" data-bet="0">0</div></div>
              </div>
            </div>
            <div class="bet-area">
              <span>Bet:</span>
              <div class="chip-size bet-control selected-chip" onclick=" bet = 10;">10</div>
              <div class="chip-size bet-control" onclick=" bet = 100;">100</div>
              <div class="chip-size bet-control" onclick=" bet = 1000;">1k</div>
              <div class="chip-size bet-control" onclick=" bet = 10000;">10k</div>
            </div>
            <div class="action-area">
              <div class="action-button bet-control" onclick="spin();">Spin</div>
              <div class="action-button bet-control" onclick="clear_bets()">Clear Bets</div>
            </div>
            <div id="mute" onclick="mute();"></div>

          </div>
        </div>

      </div>
      <div class="stats">
        <div class="st-switches">
          <a data-load="my_bets" href="#">MY BETS</a>
          <a data-load="all_bets" href="#">ALL BETS</a>
          <a data-load="high" href="#">HIGHEST WINS</a>
        </div>
        <div class="st-switchline"></div>
        <div class="st-stats">
          <table>
            <thead>
              <tr>
                <td>BET ID</td>
                <td>PLAYER</td>
                <td>TIME</td>
                <td>BET</td>
                <td>SPIN</td>
                <td>PAYOUT</td>
                <td>PROFIT</td>
              </tr>
            </thead>
            <tbody class="stats-my_bets"></tbody>
            <tbody class="stats-all_bets"></tbody>
            <tbody class="stats-high"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!--  BLOCKS HIDDEN BY DEFAULT  -->

    <div class="leftCon" id="lc-stats">

      <div class="heading"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;&nbsp;&nbsp;Stats</div>
      <div class="content">


        <div class="_heading _hfirst">Your Stats</div>
        <div class="form-group">
          <label>Total spins</label><br>
          <span class="statsData_y_spins"></span>
        </div>
        <div class="form-group">
          <label>Total wagered</label><br>
          <span class="statsData_y_wagered"></span>
        </div>


        <div class="_heading _hfirst">Global Stats</div>
        <div class="form-group">
          <label>Total spins</label><br>
          <span class="statsData_g_spins"></span>
        </div>
        <div class="form-group">
          <label>Total wagered</label><br>
          <span class="statsData_g_wagered"></span>
        </div>


      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-invest">

      <div class="heading"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;&nbsp;&nbsp;&nbsp;Invest</div>
      <div class="content">

        <div class="form-group" style="margin-top: 10px;">
          <label>You can invest:</label><br>
          <span class="invData_caninvest"></span>
        </div>
        <div class="form-group">
          <label>Invested:</label><br>
          <span class="invData_invested"></span>
        </div>
        <div class="form-group">
          <label>Bankroll share:</label><br>
          <span class="invData_share"></span>
        </div>
        <div class="form-group">
          <label>Invest funds:</label><br>
          <input type="text" class="form-control rightact" style="width: 70%;float:left;" value="0.00000000" id="input-invest"><a href="#" style="width: 30%;" onclick="javascript:invest();return false;" class="leftact btn btn-primary">Invest</a>
        </div>
        <div class="form-group">
          <label>Divest funds:</label><br>
          <input type="text" class="form-control rightact" style="width: 70%;float:left;" value="0.00000000" id="input-divest"><a href="#" style="width: 30%;" onclick="javascript:divest();return false;" class="leftact btn btn-primary">Divest</a>
        </div>


      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-profile">

      <div class="heading"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;&nbsp;My Account</div>
      <div class="content">
        <div class="form-group" style="margin-top: 10px;">
          <label>Alias</label>
          <div style="overflow: hidden;">
            <input style="width: 75%;float:left;" id="input-alias" value="<?php echo $player['alias']; ?>" class="form-control rightact" type="text"><a href="#" onclick="javascript:saveAlias();return false;" class="leftact btn btn-primary saveNick" style="width: 25%;">Save</a>
          </div>
        </div>
        <div class="form-group">
          <label>Password (<span class="pass-en_dis" style="font-weight: bold;"><?php echo ($player['password'] != '') ? 'Enabled' : 'Disabled'; ?></span>)</label>
          <div style="overflow: hidden;">
            <input id="input-pass" style="width: 70%;float:left;" class="form-control rightact" type="password"><a href="#" class="leftact btn btn-primary savePass" onclick="javascript:<?php if ($player['password']=='') echo 'enablePass'; else echo 'disablePass'; ?>();return false;" style="width: 30%;"><?php if ($player['password']=='') echo 'Enable'; else echo 'Disable'; ?></a>
          </div>
        </div>
        <div class="form-group">
          <label>Unique URL</label>
          <input class="form-control" type="text" id="input-unique" style="cursor:pointer;cursor:hand;width: 100%;" onclick="$(this).select();" value="<?php echo $settings['url'].'/?unique='.$player['hash']; ?>">
        </div>
      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-giveaway">

      <div class="heading"><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;&nbsp;&nbsp;Giveaway</div>
      <div class="content">

              <div class="form-group" style="margin-top: 10px;">
                <label>Giveaway Amount</label><br>
                <?php echo '<b>'.n_num($settings['giveaway_amount'],true).'</b> '.$settings['currency_sign']; ?>
              </div>
              <div class="form-group">
                <label>Enter text from image</label><br>
                <a class="captchadiv" href="#" onclick="javascript:$(this).children().remove().clone().appendTo($(this));return false;" data-toggle="tooltip" data-placement="top" title="Click to refresh"><img src="./content/captcha/genImage.php"></a>
                <input type="text" class="captchaInput form-control rightact" style="width: 70%;float:left;" maxlength="7" id="input-captcha"><a href="#" style="width: 30%;" onclick="javascript:claim_bonus();return false;" class="leftact btn btn-primary">Claim</a>
              </div>

      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-news">

      <div class="heading"><span class="glyphicon glyphicon-flag"></span>&nbsp;&nbsp;&nbsp;&nbsp;News</div>
      <div class="content">

        <?php

        $news = db_query("SELECT * FROM `news` ORDER BY `time` DESC");

        while ($new = db_fetch_array($news)) {
          echo '<div class="well" style="overflow:hidden;margin-bottom: 0;margin-top:10px;"><div style="width:100%;text-align:justify;">'.bbcode($new['content']).'</div><div style="width:100%;text-align:right;"><small><i>'.date('Y-m-d',strtotime($new['time'])).'</i></small></div></div>';
        }
        ?>

      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-fair">

      <div class="heading"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;&nbsp;Provably Fair</div>
      <div class="content">

        <div class="_heading _hfirst">Next Shuffle</div>
        <div class="form-group">
          <label>Server seed (Sha256):</label><br>
          <input class="form-control" style="width: 100%;" type="text" id="_fair_server_seed" value="<?php echo hash('sha256',$player['server_seed']); ?>" disabled><br>
        </div>
        <div class="form-group">
          <label>Client seed:</label><br>
          <div style="overflow: hidden;">
            <input class="form-control rightact" style="width: 80%;float:left;" class="form-control" type="text" id="_fair_client_seed" value="<?php echo $player['client_seed']; ?>"><a href="#" class="clientseedsave leftact btn btn-primary">Save</a><br>
          </div>
        </div>

        <div class="_heading">Last Shuffle</div>
        <div class="form-group">
          <label>Server seed (Sha256):</label><br>
          <input class="form-control" style="width: 100%;" type="text" id="_fair_l_server_seed" value="<?php if($player['last_server_seed'] != '') echo hash('sha256',$player['last_server_seed']); ?>" disabled><br>
        </div>
        <div class="form-group">
          <label>Server seed (plain):</label><br>
          <input class="form-control" style="width: 100%;" type="text" id="_fair_l_server_seed_p" value="<?php echo $player['last_server_seed']; ?>" disabled><br>
        </div>
        <div class="form-group">
          <label>Client seed:</label><br>
          <input class="form-control" style="width: 100%;" type="text" id="_fair_l_client_seed" value="<?php echo $player['last_client_seed']; ?>" disabled><br>
        </div>
        <div class="form-group">
          <label>Result:</label><br>
          <input class="form-control" style="width: 100%;" type="text" id="_fair_l_result" value="<?php echo $player['last_final_result']; ?>" disabled><br>
        </div>

      </div>
      <div class="footer"></div>

    </div>
    <div class="leftCon" id="lc-chat">

      <div class="heading"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;&nbsp;&nbsp;Chat</div>
      <div class="content"></div>
      <div class="footer">
        <input type="text" class="chat-input" placeholder="Type your message" data-toggle="tooltip" data-placement="top" title="Press ENTER to send">
        <div style="height: 5px;"></div>
      </div>
    </div>


      <div class="modal fade" id="modals-deposit" aria-labelledby="mlabels-deposit" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-deposit">Deposit Funds</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
              Please send at least <b><?php echo n_num($settings['min_deposit']); ?></b> <?php echo $settings['currency_sign']; ?> to this address:
              <div class="addr-p" style="margin:15px;font-weight:bold;font-size:18px;"></div>
              <div class="addr-qr"></div>
              <div class="alert alert-infoo" style="margin: 15px;"><big><b><i>This address is only for a single use. If you want to deposit multiple times, you should generate new address.</i></b></big></div>
              <div style="margin-bottom:15px;">
                <a href="#" class="gray_a" onclick="javascript:_genNewAddress();return false;">New Address</a> <span class="color: lightgray">·</span> <a href="#" class="gray_a pendingbutton" cj-opened="no" onclick="javascript:clickPending();return false;">Show Pending</a>
              </div>
              <div class="pendingDeposits" style="display:none;"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-withdraw" aria-labelledby="mlabels-withdraw" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-withdraw">Withdraw Funds</h4>
            </div>
            <div class="modal-body">
              <div class="m_alert"></div>
              <div class="form-group">
                <label for="input-address">Enter valid <?php echo $settings['currency_sign']; ?> address:</label>
                <input type="text" class="form-control" id="input-address">
              </div>
              <div class="form-group">
                <label for="input-am">Enter amount (min. <?php echo n_num($settings['min_withdrawal']).' '.$settings['currency_sign']; ?>):</label>
                <input type="text" class="form-control" id="input-am" style="width:150px;text-align:center;">
                <small>
                  Balance: <span class="balance" style="font-weight: bold;"><?php echo n_num($player['balance'],true); ?></span> <?php echo $settings['currency_sign']; ?>
                </small>
              </div>
              <button class="btn btn-primary" style="height: 39px;line-height:39px; padding: 0 20px;" onclick="javascript:_withdraw();">Withdraw</button>
            </div>
          </div>
        </div>
      </div>
    <!--  /BLOCKS HIDDEN BY DEFAULT  -->
    <!-- COINTOLI_ID_-CointoliID- -->
    
    
  </body>
</html>
<?php include __DIR__.'/inc/end.php'; ?>