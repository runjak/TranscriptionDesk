<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Another testpage</title>
        <?php require_once 'head.php';?>
    </head>
    <body>
        <?php include_once('navbar.php') ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Showcasing all retrievable Omeka Pictures</h1>
                </div>
            </div>
        </div>
        <div class="container">
            <br>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <?php
                    require_once 'config.php';
                    $i = 0;
                    foreach(Config::getOmeka()->getCollections() as $collection){
                        foreach($collection->getItems() as $item){
                            foreach($item->getFiles() as $file){
                                $src = $file->getFullsizeFileUrl();
	                        if ($i == 0){
                                    echo " <li data-target='#myCarousel' data-slide-to='0' class='active'></li>";
                                    $i = 1;
	                        } else {
                                    echo " <li data-target='#myCarousel' data-slide-to='$i'></li>";
	                            $i = $i + 1;
	                        }
                            }
                        }
                    }
                ?>
                </ol>
                <div class="carousel-inner" role="listbox">
                <?php
                    $i = 0;
                    require_once 'config.php';
                    foreach(Config::getOmeka()->getCollections() as $collection){
                        foreach($collection->getItems() as $item){
                            foreach($item->getFiles() as $file){
                                $src = $file->getOriginalFileUrl();
	                        if ($i == 0){
	                            echo "<div class='item active'><img src='$src' alt='active'></div>";
                                    $i = 1;
	                        } else {
	                            echo "<div class='item'><img src='$src' alt='test'></div>";
                                    $i = $i + 1;
                                }
                            }
                        }
                    }
                ?>
                </div>
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class"lead">A carousel display of all retrievable pictures from Omeka.</p>
                </div>
            </div>
        </div>
    </body
</html>
