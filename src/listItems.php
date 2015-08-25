<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Collections of scans</title>
        <?php require_once 'head.php';?>
    </head>
    <body>
        <?php require_once('navbar.php') ?>
        <div class="container">
            <ul class="itemList"><?php
                $items = Config::getOmeka()->getItems();
                foreach($items as $i){
                    if(!$i->shouldDisplay()){ continue; }
                    $table = '';
                    foreach($i->getDublinCore() as $k => $v){
                        $table .= "<tr><td>$k:</td><td>$v</td></tr>";
                    }
                    $table = "<table class=\"table table-bordered\"><tbody>$table</tbody></table>";
                    echo "<li>$table</li>";
                }
            ?>
            </ul>
        </div>
    </body
</html>
