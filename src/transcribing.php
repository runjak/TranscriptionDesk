<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Transcribing</title>
        <?php require_once 'head.php';?>
        <script src="js/transcribe.js"></script>
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/transcribe.css" rel="stylesheet">
        <link rel="stylesheet" href="css/ol.css" type="text/css">
    </head>
    <body>
        <!-- Navigation -->
        <?php require_once('navbar.php'); ?>
        <!-- Page Content -->
        <div class="container">
            <div class="cont">
                <div class="row">
                    <div class="col-sm-6 sp editor">
                        <div class="pane-label"><code>Picture</code></div>
                        <div class="inner">
                            <div id="map" class="map"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 sp markdown">
                        <div class="pane-label"><code id="markdown">Markdown</code></div>
                        <div class="inner">
                            <h1 id="selectionName"></h1>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="editor"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pane-label"><code>Comments</code></div>
                        <div class="inner">
                            <h1>Comments</h1>
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                            veritatis et quasi architecto beatae vitae.</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

