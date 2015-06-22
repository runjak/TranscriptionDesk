<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Some testpage</title>
  <meta name="description" content="This does… a thing.">
  <meta name="author" content="https://github.com/runjak/TranscriptionDesk">
  </head>
  <body>
    <h1>Thumbnails from Omeka:</h1>
    <?php
      require_once 'config.php';
      foreach(Config::getOmeka()->getCollections() as $collection){
        foreach($collection->getItems() as $item){
          foreach($item->getFiles() as $file){
            $src = $file->getSquareThumbnailFileUrl();
            echo "<img src='$src'/>";
          }
        }
      }
    ?>
  </body>
</html>
