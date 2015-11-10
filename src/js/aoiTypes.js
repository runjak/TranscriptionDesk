"use strict";
/**
    This module delivers data about currently known AOI Types
    by parsing data found in a '#aoiTypes' in the page.
    This is the case with singleFile.php
*/
define(['jquery','bootbox.min'], function($, bootbox){
    /** Parsed data obtained from the page. */
    var aoiTypes = $.parseJSON($('#aoiTypes').text());
    /** Helper function to produce a selection from aoiTypes. */
    var mkSelect = function(id){
        //Sanitize id:
        if(typeof(id) === 'string' && id !== ''){
            id = ' id="'+id+'"';
        }else{
            id = '';
        }
        //Default option to display:
        var defOpt = '<option value="Nope" disabled selected>'
                   + 'Please select.'
                   + '</option>';
        //Generating options:
        var opts = [defOpt];
        Object.keys(aoiTypes).forEach(function(type){
            var label = aoiTypes[type].label;
            opts.push('<option value="'+type+'">'+label+'</option>');
        });
        //Compose and return:
        return '<select'+id+' required>'
             + opts.join()
             + '</select>';
    };
    /** Helper function to produce a form for aoiTypes. */
    var mkForm = function(id){
        //Sanitize id:
        if(typeof(id) === 'string' && id !== ''){
            id = ' id="'+id+'"';
        }else{
            id = '';
        }
        //Compose and return:
        return '<div'+id+' class="input-group">'
             + mkSelect('aoiTypeSelect')
             + '<input type="text" class="form-control hide" id="aoiTypeText" value="" placeholder="Describe your selection.">'
             + '<input type="button" class="form-control hide btn btn-success" id="aoiTypeSave" value="Save">'
             + '</div>';
    };
    /**
        @param func Function(typeEnum, typeText)
        @return dialog bootbox.dialog
        Helper function to produce a bootbox dialog for aoiTypes.
        Takes a func argument with a callback Function that will get the selected typeEnum and typeText once the user finishes the selection.
    */
    var mkDialog = function(func){
        //Dialog to bind events against:
        var dialog = bootbox.dialog({
            title: "Are you done with your selection?",
            message: mkForm('aoiTypeForm'),
            buttons: {},
            show: false
        });
        //Event handling:
        var typeSelect = $('#aoiTypeSelect')
          , typeText   = $('#aoiTypeText')
          , typeSave   = $('#aoiTypeSave');
        //Selection of aoiType should reflect in visibility of typeText:
        typeSelect.change(function(){
            var hasText = aoiTypes[this.value].hasText;
            if(hasText){
                typeText.removeClass('hide');
            }else{
                typeText.addClass('hide');
                typeText.val('');
            }
        });
        //Validate input on change to see if save button should be enabled:
        var validate = function(){
            //Values to work with:
            var sVal = typeSelect.val()
              , tVal = typeText.val();
            //Helper on invalid case:
            var invalid = function(){
                typeSave.addClass('hide');
            };
            //Checking selection of type:
            if(sVal === 'Nope'){
                return invalid();
            }
            //Checking existence of text:
            if(aoiTypes[sVal].hasText){
                if(tVal === ''){
                    return invalid();
                }
            }
            typeSave.removeClass('hide');
        };
        typeText.change(validate);
        typeSelect.change(validate);
        //Trigger callback on aoiTypeSave:
        typeSave.click(function(){
            //Fetching values:
            var sVal = typeSelect.val()
              , tVal = typeText.val();
            //Resetting dialog to be clear again:
            typeSelect.val('Nope');
            typeText.val('');
            //Hiding dialog:
            dialog.modal({show: false});
            //Triggering callback:
            func(parseInt(sVal, 10), tVal);
        });
        //Return dialog for fun and profit:
        return dialog;
    };
    //Compose and return exports:
    return {
        data: aoiTypes,
        mkSelect: mkSelect,
        mkForm: mkForm,
        mkDialog: mkDialog
    };
});
