/* global myVar */

$(document).ready(function(){
    $(document).on('click', '[data-add-test]', function(e){
        e.preventDefault();
        let f = Number($(this).attr('data-count'));
        let href = this.href+"?id="+f;
        f = f + 1;
        $(this).attr('data-count', f);
        myVar.ajax( function(data){
            $('[data-div-form]').append(data.text);
        }, href);
        return false;
    });
});

