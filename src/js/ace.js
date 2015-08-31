require(['jquery', 'ace/ace'], function($){
    $(document).ready(function() {
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/github");
        editor.getSession().setMode("ace/mode/markdown");
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true
        });
        editor.commands.on("afterExec", function(e){
                // activate autocomplete when paren or .(dot) is typed
                if (e.command.name == "insertstring"&&/^[\\.\(.]$/.test(e.args)) {
                    editor.execCommand("startAutocomplete")
                }
            });
        editor.setShowPrintMargin(true);
        editor.setDisplayIndentGuides(true);
        editor.setHighlightActiveLine(true);
        editor.setAutoScrollEditorIntoView(true);
        editor.setOption("maxLines", 40);
        editor.setOption("minLines", 10);
    });
});
