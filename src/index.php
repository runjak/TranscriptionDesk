<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Some testpage</title>
  <meta name="description" content="This does… a thing.">
  <meta name="author" content="https://github.com/runjak/TranscriptionDesk">
  </head>
  <body>
    <h1>Yeah, it worked!</h1>
    <p>Have a look at some <a href="omekaThumbnails.php">thumbnails</a>!</p>
    <p>It appears that you've got this part working :)</p>
    <p>Another <a href="phptestfile.php">test</a>.</p>
    <?php
      phpinfo();
    ?>
    <h1>Testing database:</h1>
    <?php
      require_once 'config.php';
      $db = Config::getDB();
      $set = $db->query("SHOW TABLES");
      while($r = $set->fetch_assoc()){
        foreach($r as $k => $v){
            echo "$k => $v<br>";
        }
      }
    ?>
  </body>
</html>
