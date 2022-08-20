/* global myVar, myModal */

$(document).ready(function () {
    $(document).on('click', '[data-dropdown]', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-dropdown');
        myVar.ajaxA(this, function (data) {
            if(!data.status){
                myModal.error.show(data.text, data.title, 3000);
            }else{
               $("[data-upgrade-form='"+id+"']").html(data.text);
            }
        });
        return false;
    });
    
    $(document).on('submit', '[data-updateform-table]', function(e){
        e.preventDefault();
        myVar.ajaxForm(this, function(data){
            if(!data.status){
                myModal.error.show(data.text, data.title, 3000);
            }else{
                $("[data-upgrade-form='"+data.id+"']").html(data.text);
            }
        });
        return false;
    });
    
    
});