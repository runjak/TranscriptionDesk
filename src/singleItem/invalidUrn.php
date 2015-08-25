<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Invalid URN given.</title>
        <?php require_once 'head.php';?>
    </head>
    <body>
        <?php require_once('navbar.php'); ?>
        <div class="container">
            <h1>Sorry, this shouldn't have happend.</h1>
            <div class="well">
                The passed URN <code><?php echo $_GET['urn'];?></code>
                doesn't appear to belong to any currently available item.
            </div>
        </div>
    </body
</html>
