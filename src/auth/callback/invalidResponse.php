<html lang="en">
    <head>
        <?php require_once 'head.php';?>
        <title>Invalid auth response.</title>
    </head>
    <body>
        <?php require_once('../../navbar.php'); ?>
        <div class="alert alert-danger">
            <strong>Invalid auth response: </strong>
            <?php echo $reason;?>
        </div>
    </body>
</html>
