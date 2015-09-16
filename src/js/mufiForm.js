mufiSymbols = getmufi();
function showMufiInput(searchFormId,selectorFormId){ 
    var searchFormString = '<form>\n<select name="Mufi Category" id="category">\n';
    $.each(mufiSymbols,function(key,value){
        searchFormString += '<option value="'+key+'">'+key+'</option>\n';
    });
    searchFormString += '</select>\n</form>\n';
    $(searchFormId).html(searchFormString);
    showMufiSelector(selectorFormId,$('#category').val(),mufiSymbols);
    $('#category').change(function (event){
        showMufiSelector(selectorFormId,$('#category').val(),mufiSymbols);
    });
}
function showMufiSelector(selectorFormId,selectedCategory,mufiSymbols){
    var selectorFormString = '<form id = "selectorForm">\n<select name="Mufi Symbol" id="symbol">\n';
    $.each(mufiSymbols[selectedCategory],function(key,description){
        var character = (String.fromCodePoint(parseInt('0x'+key)));
        selectorFormString += '<option value="'+character+'">'+character+' '+description.toLowerCase()+'</option>\n';
    });
    selectorFormString += '</select>\n'
            +   '<input type="submit" value="accept">\n'
            +   '</form>\n';
    $(selectorFormId).html(selectorFormString);
    $('#selectorForm').submit(function(event) {
        var mufiSymbol = $('#symbol').val();
        //TODO define this function!
        //addToTranscriptionField(mufiSymbol);
        console.log('user chose '+mufiSymbol);
        event.preventDefault();
    });
}

