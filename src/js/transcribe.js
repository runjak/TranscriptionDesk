$(document).ready(function(){
    var editor = $('.editor'),
        markdown = $('.markdown'),
        content = $('.cont');
    editor.resizable({
        handles: 'e',
        resize: function(e, ui){
            var w = content.width() - editor.width();
            markdown.css('width', (w-3)+'px');
            update(markdown, editor);
        }
    });
});
