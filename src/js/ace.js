$(document).ready(function() {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/github");
    editor.getSession().setMode("ace/mode/markup");
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
    editor.setShowPrintMargin(false);
    editor.setDisplayIndentGuides(false);
    editor.setHighlightActiveLine(false);
    editor.setAutoScrollEditorIntoView(true);
    editor.setOption("maxLines", 40);
    editor.setOption("minLines", 10);
});