<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Another testpage</title>
        <meta name="descirption" content="Showcasing Pictures.">
        <meta name="author" content="https://github.com/runjak/TranscriptionDesk">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]--> 
    </head>
    <body>
        <?php
            include_once('header.php')
        ?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
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
</html>>
