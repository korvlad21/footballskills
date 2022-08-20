/* global myModal, myVar, PubSub, ymaps */

$(document).ready(function () {
    $(document).on('click', '[data-manager-form]', function (e) {
        e.preventDefault();
        myVar.ajaxA(this, function (data) {
            myModal.panel.close();
            myModal.panel.text(data.text);
        });
        return false;
    });

    $(document).on('submit', '#manager-has-shops', function (e) {
        e.preventDefault();
        myVar.ajaxForm(this, function (data) {
            if (data.status) {
                myModal.panel.close();
//                $('#data-manager').html(data.text);
            } else {
                myModal.error.show(data.text, 'Ошибка');
            }
        });
        return false;
    });
});



