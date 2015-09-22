/**
    Inserting text at caret location in a text input or a textarea
    in JavaScript is more complicated than one would expect.
    Therefore we have this short module to aid that case.
    Code in parts taken from https://stackoverflow.com/a/15976659/448591
*/
define(['jquery'], function($){
    /**
        @param $tgt JQuery object selecting input element.
        @param text Text to write into selected $tgt.
        Function inserts given text into selection specified by $tgt.
        Throws if parameters violate types.
    */
    return function($tgt, text){
        //Sanity checks:
        if(!($tgt instanceof $)){
            throw('Given $tgt is no element of $.');
        }
        if(typeof(text) !== 'string'){
            throw('text is expected to be of type string.');
        }
        //Insert magic:
        $tgt.each(function(){
            if(document.selection){
                //For browsers like Internet Explorer
                this.focus();
                sel = document.selection.createRange();
                sel.text = text;
                this.focus();
            }else if(this.selectionStart || this.selectionStart == '0'){
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos)+text+this.value.substring(endPos,this.value.length);
                this.focus();
                this.selectionStart = startPos + text.length;
                this.selectionEnd = startPos + text.length;
                this.scrollTop = scrollTop;
            }else{
                this.value += text;
                this.focus();
            }
        });
    };
});
