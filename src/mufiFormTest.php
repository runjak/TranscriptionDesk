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
                    <input type="text" class="testInput">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
        <script>
            require(['mufiForm','mufiTags','mufiTagInput'], function(mufiForm, tags, tagInput){
                mufiForm.showMufiInput('#a','#b');
                window.mufiTags = tags;
                var input = $('.testInput');
                $('button.btn.btn-default').click(function(){ tagInput(input); });
            });
        </script>
    </body>
</html>
