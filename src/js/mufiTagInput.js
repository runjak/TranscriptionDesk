"use strict";
/**
    FIXME DESCRIBE
    FIXME PUT style in css file
*/
define([
    'jquery',
    'bootbox.min',
    'mufiSymbols',
    'mufiTags',
    'insertAtCaret'
    ], function($, bootbox, symbols, tags, insertAtCaret){
    /**
        @param $tgt JQuery Object selecting input element.
        Function presents a bootbox dialog enabling mufi input via tags.
    */
    return function($tgt){
        if(!($tgt instanceof $)){
            throw 'Unexpected $tgt parameter!';
        }
        //Form to create dialog with:
        var form = '<div class="input-group" id="tagSearchInputGroup">'
                 + '<input type="text" id="tagSearch" class="form-control" placeholder="Search text" aria-describedby="basic-addon1">'
                 + '</div>'
                 + '<h6>Tags matching search:</h6>'
                 + '<ul id="tagList" style="padding-left: 0px; max-height: 125px; overflow-y: scroll;"></ul>'
                 + '<h6>Matching symbols:</h6>'
                 + '<ul id="symbolList" style="padding-left: 0px; max-height: 125px; overflow-y: scroll;"></ul>';
        //Dialog to bind events against:
        var dialog = bootbox.dialog({
            title: 'Search and insert special symbols.',
            message: form,
            buttons: {},
            show: false
        });
        //#tagSearchInputGroup div:
        var inputGroup = $('#tagSearchInputGroup');
        /**
            @return tags [String]
            Produces an array of strings for the currently selected tags.
        */
        var getSelectedTags = function(){
            var tags = [];
            inputGroup.find('.tag').each(function(){
                tags.push($(this).data('tag'));
            });
            return tags;
        };
        /**
            @param $tree jquery object in which to find .addTag elements.
            Inside #tagList there are .addTag elements that can be clicked.
            The semantic of such a click is to add the text of that element as a tag to filter by,
            and afterwards remove the clicked element.
        */
        var bindAddTag = function($tree){
            $tree.find('.addTag').click(function(){
                //Thing we operate on/with:
                var $t = $(this);
                //Constructing tag to add to filter:
                var tag = '<span class="tag input-group-addon" data-tag="'+$t.val()+'">'
                        + $t.val()
                        + '<a href="#" class="removeTag btn btn-xs">&times;</a>'
                        + '</span>';
                //Updating filter:
                inputGroup.prepend(tag);
                //Handler for .removeTag:
                inputGroup.find('.removeTag:first').click(function(e){
                    $(this).parent().remove();
                    updateTagList();
                    e.preventDefault();
                });
                //Traverse to parent to remove <li>.
                $t.parent().remove();
                //Updating tag list:
                updateTagList();
                //Updating symbol list:
                updateSymbolList();
            });
        };
        /**
            @param tags [String]
            Function to update tagList
        */
        var updateTagList = (function(tagList){
            return function(putTags){
                putTags = putTags || tags.remainingTags(getSelectedTags());
                var items = '';
                putTags.forEach(function(tag){
                    items += '<li style="display: inline;">'
                           + '<input type="button" class="btn btn-xs addTag" value="'+tag+'">'
                           + '</li>';
                });
                tagList.html(items);
                bindAddTag(tagList);
            };
        })($('#tagList'));
        //Initial tags:
        updateTagList();
        //Filtering tags on tagSearch keyup:
        (function(tagSearch){
            tagSearch.keyup(function(){
                var input = tagSearch.val().toLowerCase();
                var filteredTags = [];
                var possibleTags = tags.remainingTags(getSelectedTags());
                possibleTags.forEach(function(tag){
                    if(tag.toLowerCase().search(input) >= 0){
                        filteredTags.push(tag);
                    }
                });
                updateTagList(filteredTags);
            });
        })($('#tagSearch'));
        //Function to update symbol list:
        var updateSymbolList = (function(symbolList){
            return function(){
                var symbols = tags.intersectTags(getSelectedTags());
                var items = '';
                symbols.forEach(function(symbol){
                    var val = String.fromCodePoint(parseInt(symbol, 16));
                    items += '<li style="display: inline;">'
                           + '<input type="button" class="btn btn-xs addSymbol" value="'+val+'">'
                           + '</li>';
                });
                symbolList.html(items);
                symbolList.find('.addSymbol').click(function(){
                   var symbol = $(this).val();
                   insertAtCaret($tgt, symbol);
                });
            };
        })($('#symbolList'));
        //Initial symbols:
        updateSymbolList();
        //Displaying dialog:
        dialog.modal({show: true});
    };
});
