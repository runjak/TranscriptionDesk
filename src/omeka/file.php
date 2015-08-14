<?php
require_once 'timestamped.php';
/**
  Describes an Omeka file as returned by
  http://<host>/api/files?item=5&key=…&pretty_print
*/
class OmekaFile extends OmekaTimestamped {
  /**
    @return mimeType String
  */
  public function getMimeType(){
    return $this->data['metadata']['mime_type'];
  }
  /**
    @return $site Int
    Returns the number of bytes that Omeka claims to be used by the file.
  */
  public function getSize(){
    return $this->data['size'];
  }
  /**
    Helper method for get{…}FileUrl methods.
  */
  private function getFileUrl($field){
    return $this->data['file_urls'][$field];
  }
  /**
    @return url String/URL
    Retruns the URL to fetch the original image for a file from.
  */
  public function getOriginalFileUrl(){
    return $this->getFileUrl('original');
  }
  /**
    @return url String/URL
    Retruns the URL to fetch the fullsize image for a file from.
  */
  public function getFullsizeFileUrl(){
    return $this->getFileUrl('fullsize');
  }
  /**
    @return url String/URL
    Retruns the URL to fetch the Thumbnail for a file from.
  */
  public function getThumbnailFileUrl(){
    return $this->getFileUrl('thumbnail');
  }
  /**
    @return url String/URL
    Retruns the URL to fetch the SquareThumbnail for a file from.
  */
  public function getSquareThumbnailFileUrl(){
    return $this->getFileUrl('square_thumbnail');
  }
  /**
    @return filename String
    Returns the filename that the file currently is stored with in Omeka.
  */
  public function getFilename(){
    return $this->data['filename'];
  }
  /**
    @return filename String
    Returns the original filename that the file had before storing it in Omeka.
  */
  public function getOriginalFilename(){
    return $this->data['original_filename'];
  }
  /**
    @param $item OmekaItem
    @return $urn String || null
    Returns the urn for a file provided the item that it belongs to.
    Will return null iff the last part of the OmekaItem->getUrn that belongs to this file
    is not a valid prefix of $this->getOriginalFilename().
    See [#15] for further info.
    [#15]: https://github.com/runjak/TranscriptionDesk/issues/15
    FIXME remove $item parameter
  */
  public function getUrn($item){
    //We dissect the items URN by its delimeter ':':
    $itemUrnParts = explode(':',$item->getUrn());
    //Getting rid of the last part of $itemUrnParts:
    $fNamePrefix = array_pop($itemUrnParts);
    //The $urnPrefix is composed by glueing the remaining $itemUrnParts together:
    $urnPrefix = implode(':',$itemUrnParts);
    //We can now append the original_filename of this file:
    $fName = str_replace(':','_',$this->getOriginalFilename());
    if(substr($fName, 0, strlen($fNamePrefix)) === $fNamePrefix){
        return $urnPrefix.':'.$fName;
    }
    return null;
  }
  /**
    @return $data array(String => *)
    Produces an array that contains all the fields that
    shall be stored in the scanDataJSON column of the scans table.
    That column was introduced with f656bc1c7a0f988235e6e349b0522c9101050766.
  */
  public function getStoreData(){
      $data = $this->data;
      //We store all data except for the url, which has its own column.
      unset($data['url']);
      return $data;
  }
  /**
    @return $error String || null
    Saves an OmekaFile instance to the database.
    If a file with the same URN already exists,
    that file will be updated.
    Otherwise a new entry will be created.
    Returns null if storing went without problems.
    FIXME remove $item parameter
  */
  public function store($item){
    $error = null;
    $itemUrn = $item->getUrn();
    $urn = $this->getUrn($item);
    if($urn === null){
        $fName = $this->getOriginalFilename();
        $error = "'$itemUrn' doesn't yield URN for file '$fName'";
    }else{
        //Gathering additional data:
        $url = $this->getUrl();
        $data = json_encode($this->getStoreData());
        //FIXME add necessary additional data!
        //INSERT/UPDATE:
        $q = 'INSERT INTO scans (urn, omekaUrl, omekaItem, scanDataJSON)'
           . ' VALUES (?,?,?,?) '
           . 'ON DUPLICATE KEY UPDATE urn = ?, omekaUrl = ?, omekaItem = ?, scanDataJSON = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('ssssssss'
            , $urn, $url, $itemUrn, $data
            , $urn, $url, $itemUrn, $data);
        if(!$stmt->execute()){
            $error = 'SQL error in OmekaFile->store()';
        }
        $stmt->close();
    }
    return $error;
  }
}
/*
Example data seen in the wild:

[
  {
    "id":31,
    "url":"http:\/\/<host>\/api\/files\/31",
    "file_urls":{
      "original":"http:\/\/<host>\/files\/original\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpeg",
      "fullsize":"http:\/\/<host>\/files\/fullsize\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg",
      "thumbnail":"http:\/\/<host>\/files\/thumbnails\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg",
      "square_thumbnail":"http:\/\/<host>\/files\/square_thumbnails\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg"
    },
    "added":"2015-06-19T16:06:59+00:00",
    "modified":"2015-06-22T10:45:38+00:00",
    "filename":"DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpeg",
    "authentication":"0f7be474264c8f6dab5501666418ee31",
    "has_derivative_image":true,
    "mime_type":"image\/jpeg",
    "order":null,
    "original_filename":"bnf_lat7989_195.jpeg",
    "size":1708132,
    "stored":true,
    "type_os":"JPEG image data, JFIF standard 1.01, resolution (DPI), density 72x72, segment length 16, Exif Standard: [TIFF image data, big-endian, direntries=4, xresolution=62, yresolution=70, resolutionunit=2], baseline, precision 8, 2844x3855, frames 3",
    "metadata":{
      "mime_type":"image\/jpeg",
      "video":{
        "dataformat":"jpg",
        "lossless":false,
        "bits_per_sample":24,
        "pixel_aspect_ratio":1
      }
    }, …
]
*/
