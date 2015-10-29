"use strict";
/**
    This module is concerned with presenting a single point to access data about scans and their AOIs
    as it is presented by singleFile.php
*/
define(['jquery'], function($){
    /*
        scanData has 'current' field for sure,
        and may also have 'prev', next fields.
    */
    var scanData = $.parseJSON($('#scanData').text());
    /*
        We add scanData.fields, which will contain an array of ordered
        Fields included in scanData:
    */
    scanData.fields = [];
    ['prev','current','next'].forEach(function(f){
        if(f in scanData){
            scanData.fields.push(f);
        }
    });
    /*
        We always preload images:
    */
    var proms = [];
    scanData.fields.forEach(function(key){
        var scan = scanData[key]
          , def  = $.Deferred()
          , img  = new Image();
        //Handling promise for this scan:
        img.onload = function(){ def.resolve(); };
        proms.push(def.promise());
        //We exchange scan.img from String to Image:
        img.src = scan.img;
        scan.img = img;
    });
    /* Adding scanData.whenLoaded: */
    var def = $.Deferred();
    $.when.apply($, proms).done(function(){
        def.resolve();
    });
    scanData.whenLoaded = function(func){
        def.done(func);
    };
    /*
        scanData now has a structure like this:
        {
            ['prev','current','next']: {
                    urn: String,
                    img: Image,
                    aois: [
                        scanRectangleMap: {<scanUrn>: {
                                    x: Double,
                                    y: Double,
                                    width: Double,
                                    height: Double
                                }
                            }
                        urn: String, URN
                        userId: userId
                        timestamp: Timestamp
                        type: Type Enum
                        typeText: String, TypeText
                    ]
                },
            fields: [String], ordered subset of {'prev','current','next'},
            whenLoaded: function(callback) //Adds a callback to be called when images are loaded.
        }
    */
    return scanData;
});
