<?php
    require_once 'config.php';
    if(array_key_exists('userId',$_GET)){
        $user = User::fromUserId($_GET['userId']);
    }else{
        $user = Config::getUserManager()->verify();
    }
    if(empty($user)){
        header('LOCATION: index.php');
    }else{
        $name   = $user->getDisplayName();
        $avatar = $user->getAvatarUrl();
        $last   = $user->getLastLogin();
        $tasks  = $user->getTasksCompleted();
?><html lang="en">
    <head>
        <?php require_once 'head.php';?>
        <script src="js/profile.js"></script>
        <script src="js/Chart.min.js"></script>
        <title>Profile of <?php echo $name;?></title>
    </head>
    <body><?php require_once('navbar.php'); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <h2><?php echo $name;?></h2>
                                    <p><strong>Last seen: </strong> <?php echo $last;?></p>
                                    <p><strong>Tasks completed: </strong> <?php echo $tasks;?></p>
                                    <p><strong>Registered since: </strong> 20.05.2015</p>
                                </div><!--/col-->
                                <div class="col-xs-12 col-sm-4 text-center">
                                    <?php if(!empty($avatar)){?>
                                        <img src="<?php echo $avatar;?>"
                                    <?php } else {?>
                                        <img src="https://www.knowland.com/sites/all/themes/Knowland_Refresh/images/profile.png"
                                    <?php }?>
                                    class="center-block img-circle img-responsive img">
                                    <p></P>
                                    <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p50x50/9988_10151403325753553_1486509350_n.png?oh=ee682c171a872e6db9e97f208a4c9060&oe=561C8BEA&__gda__=1444785240_dc5d35d6ba9e5212efa2213d7d727a72" class="center-block img-circle img-responsive">
                                </div><!--/col-->
                            </div><!--/col-->
                        </div><!--/row-->
                    </div>
                    <div class="row">
                        <p>Your activity this month:</p>
                        <canvas id="lineChart" height="100"></canvas>
                    </div>
                </div><!--/panel-->
                <div class="col-md-6">
                    <h3>Your overall stats</h3>
                    <canvas id="polarChart"></canvas>
                </div>
            </div>
        </div>
    </body>
</html><?php }
