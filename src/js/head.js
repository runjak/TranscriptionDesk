//head.js may be executed twice.
requirejs.config({
    baseUrl: 'js'
,   paths: {
        'jquery': 'jquery.min'
    ,   'bootstrap': 'bootstrap.min'
    }
,   shim: {
        'ace': {
            deps: ['jquery','ace/ace']
        }
    ,   'bootstrap': {
            deps: ['jquery']
        }
    ,   'jquery': {
            exports: '$'
        }
    ,   'ace/ace': {
            exports: 'ace'
        }
    }
});
require(['jquery', 'bootstrap', 'login'], function(){
    //head.js loaded completly.
});
