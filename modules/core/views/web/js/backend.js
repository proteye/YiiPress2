/* Sluggable behavior when the slug field focused */
var slugTarget = $('input[name*=slug]');

slugTarget.focus(function(){
    if(slugTarget.val() == '') {
        var slugSource1 = $('input[name*=title]');
        var slugSource2 = $('input[name*=name]');
        var data = slugSource1.val() ? slugSource1.val() : slugSource2.val();
        $.post(
            '/backend/core/core-backend/ajax-slug',
            { data: data },
            function(data){
                slugTarget.val(data);
            });
    }
});
