<?php
require_once 'resource.php';
/**
  Describes an Omeka resource as returned by
  http://<host>/api/resources?pretty_print
*/
class OmekaResourceInfo extends OmekaResource {
    /**
        @return $actions [String]
        Returns a list of strings describing actions for a resource.
    */
    public function getActions(){
        return $this->data['actions'];
    }
    /**
        @return $recordType String
        A String describing the type of content
        described by the OmekaResource.
    */
    public function getRecordType(){
        return $this->data['record_type'];
    }
    /**
        @return indesParams [String]||null
        Returns a list of strings describing the index parameters for a resource.
    */
    public function getIndexParams(){
        if(!array_key_exists('index_params', $this->data)){
            return null;
        }
        return $this->data['index_params'];
    }
}
