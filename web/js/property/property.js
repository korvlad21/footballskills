/* global myModal, myVar */

$(document).ready(function () {

    $(document).on('click', '[data-value]', function (e) {
        e.preventDefault();
        let href = this.href;
        myVar.ajax(function (data) {
            if (data.status) {
                $('[data-text-col-sm-6]').html(data.text);
            }
        }, href);
        return false;
    });
    $(document).on('submit', '#form-add-property-value', function (e) {
        e.preventDefault();
        let data = new FormData(this);
        let f = this;
        myVar.ajax(function (data) {
            if (data.status) {
                $('#__table_value').html(data.text);
                $('#propertyvalue-value').val('');
            }
        }, f.action, data);
        return false;
    });

    $(document).on('click', '[data-href="toggle"]', function (e) {
        e.preventDefault();
        let href = this.href;
        let t = this;
        myVar.ajax(function (data) {
            if (data.status) {
                $(t).parent().prepend(data.text);
                $(t).hide();
            }
        }, href);
        return false;
    });

    $(document).on('submit', '#form-update-property-value', function (e) {
        e.preventDefault();
        let t = this;
        let data = new FormData(this);
        myVar.ajax(function (data) {
            if (data.status) {
                $(t).parent().children('a').html(data.text).show();
                $(t).remove();
            }
        }, t.action, data);
        return false;
    });
    $(document).on('click', '[data-delete-property-value]', function (e) {
        e.preventDefault();
        let href = this.href;
        let t = this;
        console.info();
        myVar.ajax(function (data) {
            $(t).parent().parent().remove();
        }, href);
        return false;
    });

    $(document).on('click', '[data-add-new-property]', function (e) {
        e.preventDefault();
        myVar.ajaxA(this, function (data) {
            if (data.status) {
                $('[data-text-col-sm-6]').html(data.text);
            }
        });
        return false;
    });

    $(document).on('submit', '#form-create-property', function (e) {
        e.preventDefault();
        myVar.ajaxForm(this, function (data) {
            if (!data.status) {
                myModal.info.show(data.text, 'Ошибка');
            }
        });
        return false;
    });

    $(document).on('click', '[data-close-form]', function (e) {
        $(this).parent().parent().children('a').show();
        $(this).parent().remove();
    });
});


