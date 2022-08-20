/* global myModal, myVar, PubSub */

$(document).ready(function () {

    $(document).on('click', '[data-click-button-add-manager]', function (e) {
        e.preventDefault();
        myVar.ajaxA(this, function (data) {
            myModal.panel.close();
            myModal.panel.text(data.text);
        });
        return false;
    });

    $(document).on('submit', '#form-manager', function (e) {
        e.preventDefault();
        myVar.ajaxForm(this, function (data) {
            if (data.status) {
                myModal.panel.close();
                $('#data-manager').html(data.text);
            } else {
                myModal.error.show(data.text, 'Ошибка');
            }
        });
        return false;
    });
    $(document).on('click', '[data-update-manager]', function (e) {
        e.preventDefault();
        myVar.ajaxA(this, function (data) {
            myModal.panel.close();
            myModal.panel.text(data.text);
        });
        return false;
    });

});



