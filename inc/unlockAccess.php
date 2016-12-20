<?php
/*
 *  © CoinSlots 
 *  Demo: http://www.btcircle.com/coinslots
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/

if (!isset($init)) exit();


?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $settings['title'].' - '.$settings['description']; ?></title>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="./styles/bootstrap-coingames.css">
    <link type="text/css" rel="stylesheet" href="./styles/unlock_page.css">
    <link type="text/css" rel="stylesheet" href="./styles/themes/Basic/style.css" class="themeLinker">
    <script type="text/javascript" src="./scripts/jquery.js"></script>
    <script type="text/javascript" src="./scripts/sha256.js"></script>
    <script type="text/javascript" src="./scripts/unlock_page.js"></script>
    <script type="text/javascript">
      function unique() {
        return '<?php echo $_GET['unique']; ?>';
      }
      function default_theme() {
        return '<?php echo $settings['active_theme']; ?>';
      }
    </script>
  </head>
  <body>
    <div class="loginDiv">
      <input type="password" placeholder="Enter password to unlock this account..."><a href="#" onclick="javascript:unlock();return false;">UNLOCK</a>
    </div>
  </body>
</html>

