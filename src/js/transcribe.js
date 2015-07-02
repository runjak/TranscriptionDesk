$(window).bind("resize", function(event){
    if (this == event.target) {
        $('.sp').removeAttr('style');
    }
});

$('.editor').resizable({
    handles: 'e, s',
    minWidth:100,
    maxWidth:900,
    resize:function(event,ui){
        var x=ui.element.outerWidth();
        var y=ui.element.outerHeight();
        var ele=ui.element;
        var factor = $(this).parent().width()-x;
        var f2 = $(this).parent().width() * .02999;
        console.log(f2);
        $.each(ele.siblings(),function(idx,item){

            ele.siblings().eq(idx).css('height',y+'px');
            //ele.siblings().eq(idx).css('width',(factor-41)+'px');
            ele.siblings().eq(idx).width((factor-f2)+'px');

        });
    }
});

$('.sp:not(.editor)').resizable({
    handles: 's',
    resize:function(event,ui){

        var y=ui.element.outerHeight();
        var ele=ui.element;

        $.each(ele.siblings(),function(idx,item){
            ele.siblings().eq(idx).css('height',y+'px');
        });
    }
});