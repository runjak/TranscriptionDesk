<!-- jQuery Version 1.11.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- Custom CSS -->
<style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    .modal-dialog {
        width: 300px;
    }
    .modal-footer {
        height: 70px;
        margin: 0;
    }
    .modal-footer .btn {
        font-weight: bold;
    }
    .modal-footer .progress {
        display: none;
        height: 32px;
        margin: 0;
    }
    .input-group-addon {
        color: #fff;
        background: #3276B1;
    }
</style>

<script language="JavaScript"> 
$(document).ready(function(){
        $('.modal-footer button').click(function(){
                    var button = $(this);

                            if ( button.attr("data-dismiss") != "modal" ){
                                            var inputs = $('form input');
                                                        var title = $('.modal-title');
                                                        var progress = $('.progress');
                                                                    var progressBar = $('.progress-bar');

                                                                    inputs.attr("disabled", "disabled");

                                                                                button.hide();

                                                                                progress.show();

                                                                                            progressBar.animate({width : "100%"}, 100);

                                                                                            progress.delay(1000)
                                                                                                                    .fadeOut(600);

            button.text("Close")
                    .removeClass("btn-primary")
                    .addClass("btn-success")
                    .blur()
                    .delay(1600)
                    .fadeIn(function(){
                        title.text("Log in is successful");
                        button.attr("data-dismiss", "modal");
                    });
        }
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        var inputs = $('form input');
        var title = $('.modal-title');
        var progressBar = $('.progress-bar');
        var button = $('.modal-footer button');

                inputs.removeAttr("disabled");

                title.text("Log in");

                        progressBar.css({ "width" : "0%" });

                        button.removeClass("btn-success")
                                            .addClass("btn-primary")
                                                            .text("Ok")
                                                                            .removeAttr("data-dismiss");
                                        
    });
});
</script>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Transcriptiondesk</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="phptestfile.php">Pictures</a>
                </li>
                <li>
                    <a href="contact.php">Contact</a> 
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transcribing<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="explenation.php">What are transciptions?</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="tutorial.php">Tutorial</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="showcase.php">Transcribed documents</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="transcribing.php">Start Transcribing</a></li>
                    </ul>
                </li>             
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Login</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Log in</h4>
            </div> <!-- /.modal-header -->

            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="uLogin" placeholder="Login">
                            <label for="uLogin" class="input-group-addon glyphicon glyphicon-user"></label>
                        </div>
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" class="form-control" id="uPassword" placeholder="Password">
                            <label for="uPassword" class="input-group-addon glyphicon glyphicon-lock"></label>
                        </div> <!-- /.input-group -->
                    </div> <!-- /.form-group -->

                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Remember me
                        </label>
                    </div> <!-- /.checkbox -->
                </form>

            </div> <!-- /.modal-body -->

            <div class="modal-footer">
                <button class="form-control btn btn-primary">Ok</button>

                <div class="progress">
                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" style="width: 0%;">
                        <span class="sr-only">progress</span>
                    </div>
                </div>
            </div> <!-- /.modal-footer -->

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
