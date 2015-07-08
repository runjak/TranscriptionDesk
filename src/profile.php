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
        <title>Profile of <?php echo $name;?></title>
    </head>
    <body><?php include_once('navbar.php'); ?>
        <h3>Profile page for <?php echo $name;?></h3>
        <div class="media">
            <div class="media-left">
                <?php if(!empty($avatar)){?>
                <img class="media-object" src="<?php echo $avatar;?>">
                <?php } ?>
            </div>
            <div class="media-body">
            <dl>
                <dt>Last seen:</dt>
                <dd><?php echo $last;?></dd>
                <dt>Tasks completed:</dt>
                <dd><?php echo $tasks;?></dd>
            </dl>
            </div>
        </div>
    </body>
</html><?php }
