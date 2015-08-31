<?php
require_once('areaOfInterestUrn.php');
$urn = 'urn:cite:olg:leiden_vlf123_0001.tif'
     . '@.2,0,.53,.4'
     . '+@0,0,.1,.001'
     . '+leiden_vlf123_0002.tif'
     . '@1,1,0,0';
$map = AreaOfInterestUrn::parseUrn($urn);
if(is_string($map)){
    echo "Premature exit:\n$map\n";
}else{
    $_urn = AreaOfInterestUrn::createUrn($map);
    if($urn === $_urn){
        echo "Sucessfully handled '$urn'!\n";
    }else{
        echo "URNs turned out differently:\n'$urn'\n'$_urn'\n";
    }
}
