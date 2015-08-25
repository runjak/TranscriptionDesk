<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Collections of scans</title>
        <?php require_once 'head.php';?>
    </head>
    <body>
        <?php
            require_once('navbar.php');
            //Gathering items to display:
            $items = array();
            foreach(Config::getOmeka()->getItems() as $i){
                if(!$i->shouldDisplay()){ continue; }
                if($i->getFileCount() === 0){ continue; }
                array_push($items, $i);
            }
        ?><div class="container">
            <?php
                if(count($items) === 0){?>
                <div class="well">
                    Sorry, we've got nothing to display for you here.
                    It may well be that we can display something if you decide to login.
                </div>
            <?php }else{ ?>
            <ul class="itemList"><?php
                foreach($items as $i){
                    $table = '';
                    foreach($i->getDublinCore() as $k => $v){
                        if($k === 'Identifier'){
                            $v = "<a href=\"singleItem.php?urn=$v\">$v</a>";
                        }
                        $table .= "<tr><td>$k:</td><td>$v</td></tr>";
                    }
                    $table = "<table class=\"table table-bordered\"><tbody>$table</tbody></table>";
                    echo "<li>$table</li>";
                }
            ?>
            </ul><?php } ?>
        </div>
    </body
</html>
