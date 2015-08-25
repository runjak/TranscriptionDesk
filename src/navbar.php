<?php require_once 'config.php'; ?><nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
                    <a href="listItems.php">Scans</a>
                </li>
                <li>
                    <a href="statistics.php">Statistics</a>
                </li>
                <li>
                    <a href="contact.php">Contact</a> 
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transcribing<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="explanation.php">What are transciptions?</a></li>
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
                <?php
                    $user = Config::getUserManager()->verify();
                    if($user === null){
                ?><li>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">Login</button>
                </li><?php } else { ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="auth/logout.php" type="button" class="btn btn-danger btn-lg">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<?php require_once 'navbar/loginModal.php';
