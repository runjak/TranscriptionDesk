<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Some testpage</title>
        <?php require_once 'head.php';?>
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
