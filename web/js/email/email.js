/* global myVar */

$(document).ready(function(){
 
    $(document).on('click', '[data-view-base]', function (e) {
        e.preventDefault();
        myVar.ajaxForm($('[data-form-email]')[0], function (data){
            window.open('/icms/emails-buffer/view-mail', '_blank')
        });
        return false;
    });
});

