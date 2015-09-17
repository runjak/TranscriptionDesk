<html lang="en">
    <head>
        <?php require_once 'head.php';?>
        <title>Main page</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Test page for mufiForm.js</h1>
                    <div id="a"></div>
                    <div id="b"></div>
                </div>
            </div>
        </div>
        <script>
            require(['mufiForm','mufiTags'], function(mufiForm, tags){
                mufiForm.showMufiInput('#a','#b');
                window.mufiTags = tags;
            });
        </script>
    </body>
</html>
