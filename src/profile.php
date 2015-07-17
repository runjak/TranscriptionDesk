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
        <link id="themecss" rel="stylesheet" type="text/css" href="//www.shieldui.com/shared/components/latest/css/light/all.min.css" />
        <script type="text/javascript" src="//www.shieldui.com/shared/components/latest/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="//www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
        <script src="js/profile.js"></script>
        <title>Profile of <?php echo $name;?></title>
    </head>
    <body><?php include_once('navbar.php'); ?>
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
                                    class="center-block img-circle img-responsive">
                                    <p></P>
                                    <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p50x50/9988_10151403325753553_1486509350_n.png?oh=ee682c171a872e6db9e97f208a4c9060&oe=561C8BEA&__gda__=1444785240_dc5d35d6ba9e5212efa2213d7d727a72" class="center-block img-circle img-responsive">
                                </div><!--/col-->
                            </div><!--/col-->
                        </div><!--/row-->
                    </div>
                    <div id="splineChart"></div>
                </div><!--/panel-->
                <div class="col-md-6">
                    <div id="polarChart"></div>
                </div>
            </div>
        </div>
    </body>
</html><?php }
