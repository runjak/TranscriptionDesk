requirejs.config({
    baseUrl: 'js'
,   paths: {
        'jquery': 'jquery.min'
    ,   'bootstrap': 'bootstrap.min'
    }
,   shim: {
        'bootstrap': {
            deps: ['jquery']
        }
    ,   'jquery': {
            exports: '$'
        }
    }
});
require(['jquery', 'bootstrap', 'login'], function(){
    console.log('head.js loaded.');
});
