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
        @return $url String
        Returns the URL of the OmekaItem that this file belongs to.
    */
    public function getItemUrl(){
        return $this->data['item']['url'];
    }
    /**
        @return $item OmekaItem || null
        Tries to return the OmekaItem that this file belongs to.
        Obeys Omeka->$dbUsage.
    */
    public function getItem(){
        return OmekaItem::getItemFromUrl($this->getItemUrl());
    }
    /**
        @param $item OmekaItem
        @return $urn String || null
        Returns the urn for a file provided the item that it belongs to.
        Will return null iff the last part of the OmekaItem->getUrn that belongs to this file
        is not a valid prefix of $this->getOriginalFilename().
        See [#15] for further info.
        [#15]: https://github.com/runjak/TranscriptionDesk/issues/15
    */
    public function getUrn(){
        //Loading from database injects urn:
        if(array_key_exists('urn',$this->data)){
            return $this->data['urn'];
        }
        $item = $this->getItem();
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
    */
    public function store(){
        $error = null;
        $itemUrn = $this->getItem()->getUrn();
        $urn = $this->getUrn();
        if($urn === null){
            $fName = $this->getOriginalFilename();
            $error = "'$itemUrn' doesn't yield URN for file '$fName'";
        }else{
            //Gathering additional data:
            $url = $this->getUrl();
            $data = json_encode($this->getStoreData());
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
    /**
        @param $stmt mysqli_stmt
        @return $files [urn => OmekaFile]
        Helper method for self::getFile{,s}FromDb
        Closes $stmt.
    */
    private static function fileFromDbData($stmt){
        $files = array();
        $stmt->execute();
        $stmt->bind_result($urn, $url, $scanDataJSON);
        while($stmt->fetch()){
            $data = json_decode($scanDataJSON, true);
            $data['url'] = $url;
            $data['urn'] = $urn;
            $file = new OmekaFile($data);
            $files[$urn] = $file;
        }
        $stmt->close();
        return $files;
    }
    /**
        @param $urn String
        @return $file OmekaFile || null
        Tries to fetch an OmekaFile from the scans table given its URN.
    */
    public static function getFileFromDb($urn){
        $q = 'SELECT urn, omekaUrl, scanDataJSON FROM scans WHERE urn = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $urn);
        $file = self::fileFromDbData($stmt);
        if(count($file) === 1){
            return current($file);
        }
        return null;
    }
    /**
        @return $files [urn => OmekaFile]
        Tries to fetch all OmekaFiles from the scans table.
    */
    public static function getFilesFromDb(){
        $q = 'SELECT urn, omekaUrl, scanDataJSON FROM scans';
        $stmt = Config::getDB()->prepare($q);
        return self::fileFromDbData($stmt);
    }
    /**
        @param $item OmekaItem
        @return $files []
    */
    public static function getFilesFromDbByItem($item){
        $q = 'SELECT urn, omekaUrl, scanDataJSON FROM scans '
           . 'WHERE omekaItem = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $item->getUrn());
        return self::fileFromDbData($stmt);
    }
    /**
        @param $comparator '<' || '>'
        @return $f OmekaFile || null
        Helper function for get{Next,Prev}().
    */
    private function getNeighbour($comparator){
        //Sanitizing $comparator:
        if($comparator !== '>' && $comparator !== '<'){ return null; }
        $order = ($comparator === '>') ? 'ASC' : 'DESC';
        //Current $urn:
        $urn = $this->getUrn();
        //Discovering $prefix for files that belong to the same item:
        $parts = explode('_', $urn);
        array_pop($parts);//Remove last part of urn
        $prefix = implode('_', $parts);
        //Query to use:
        $q = "SELECT urn, omekaUrl, scanDataJSON FROM scans WHERE urn LIKE ? AND urn $comparator ? ORDER BY $order LIMIT 1";
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('ss', $prefix, $urn, $order);
        $f = OmekaFile::fileFromDbData($stmt);
        if(count($f) === 1){
            return current($if);
        }
        return null;
    }
    /**
        @return $f OmekaFile || null
        Returns the file that comes previous to the current one and belongs to the same item.
    */
    public function getPrev(){
        return $this->getNeighbour('<');
    }
    /**
        @return $f OmekaFile || null
        Returns the file that comes next to the current one and belongs to the same item.
    */
    public function getNext(){
        return $this->getNeighbour('>');
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
