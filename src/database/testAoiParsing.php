<?php
require_once('areaOfInterest.php');
$urns = array(
  //Valid one:
    'urn:cite:olg:leiden_vlf123_0001.tif'
  . '@0.2,0,0.53,.4'
  . '+@0,0,.1,.001'
  . '+leiden_vlf123_0002.tif'
  . '@1,1,0,0'
  //Invalid doubles:
,   'urn:cite:olg:leiden_vlf123_0001.tif'
  . '@1,2,0.53,.4'
  . '+@0,0,7.1,.001'
  . '+leiden_vlf123_0002.tif'
  . '@1,5,1,1'
  //Slightly invalid:
,   'urn:cite:olg:leiden_vlf123_0001.tif'
  . '@@1,0,0.53,.4'
  . '++@0,0,.1,.001'
  . '+leiden_vlf123_0002.tif'
  . '@1,1,1,1'
  //Blatantly invalid:
,   '¬URN'
);
echo "Testing some URN cases:\n";
foreach($urns as $k => $urn){
    $x = AreaOfInterest::parseUrn($urn);
    echo "$urn =>\n\t".json_encode($x)."\n";
}
