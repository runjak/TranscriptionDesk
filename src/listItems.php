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
            <div class="row">
                <?php
                    if(count($items) === 0){?>
                    <div class="well">
                        Sorry, we've got nothing to display for you here.
                        It may well be that we can display something if you decide to login.
                    </div>
                <?php }else{ ?>
                <div class="panel-body">
                    <div class="panel panel-defaut">
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Identifier</th>
                                        <th>Creator</th>
                                        <th>Rights</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                    foreach($items as $i){
                        $table = '-';
                        $title = '-';
                        $id = '-';
                        $creator = '-';
                        $rights = '-';
                        $description = '-';
                        foreach($i->getDublinCore() as $k => $v){
                            if($k === 'Identifier'){
                                $id = "<a href=\"singleItem.php?urn=$v\">$v</a>";
                            }
                            if($k === 'Title'){
                                $title = $v;
                            }
                            if($k === 'Creator'){
                                $creator = $v;
                            }
                            if($k === 'Rights'){
                                $rights = $v;
                            }
                            if($k === 'Description'){
                                $description = $v;
                            }
                        }
                        $table = "<tr><td>$title</td><td>$id</td><td>$creator</td><td>$rights</td><td>$description</td></tr>";
                        echo $table;
                    }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><?php } ?>
            </div>
        </div>
    </body
</html>
