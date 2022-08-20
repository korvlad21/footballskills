/* global myVar, myModal */

$(document).ready(function () {

    /* Добавление новостей в раздел "Блог" */
    // $(document).on('click', '[data-article-create-button]', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             myModal.panel.text(data.text);
    //             myModal.panel.close();
    //         }
    //     }, f.href);
    //     return false;
    // })

    /* Сохранение новой новости в разделе "Блог" */
    // $(document).on('submit', '#form-article-create', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     let data = new FormData(this);
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             $('#articleTable').html(data.text);
    //             myModal.panel.close();
    //         }
    //     }, f.action, data);
    //     return false;
    // });

    /* Редактирование новости в разделе "Блог" */
    // $(document).on('click', '[data-article-update-button]', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             myModal.panel.text(data.text);
    //             myModal.panel.close();
    //             myVar.ligh();
    //         }
    //     }, f.href);
    //     return false;
    // })

    /* Сохранение отредактированной новости в разделе "Блог" */
    // $(document).on('submit', '#form-article-update', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     let data = new FormData(this);
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             $('#articleTable').html(data.text);
    //             myModal.panel.close();
    //         }
    //     }, f.action, data);
    //     return false;
    // });

    /* Удаление новости в разделе "Блог" */
    // $(document).on('click', '[data-article-delete-button]', function (e) {
    //     e.preventDefault();
    //     if (confirm('Вы действительно хотите удалить эту статью?')) {
    //         let f = this;
    //         let href = f.href + '?id=' + $(f).attr('data-article-delete-button');
    //         myVar.ajax(function (data) {
    //             if (data.status) {
    //                 $('#articleTable').html(data.text);
    //             }
    //         }, href);
    //     }
    //     return false;
    // })

    /* Добавление пользователей */
    // $(document).on('click', '[data-userdata-create-button]', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             myModal.panel.text(data.textUserdata);
    //             myModal.panel.close();
    //         }
    //     }, f.href);
    //     return false;
    // });

    /* Сохранение пользователей после добавления */
    // $(document).on('submit', '#form-userdata-create', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     let data = new FormData(this);
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             $('#tableUserdata').html(data.textUserdata);
    //             myModal.panel.close();
    //         } else {
    //             myModal.error.show(data.text, 'Ошибка сохранения', 3000);
    //         }
    //     }, f.action, data);
    //     return false;
    // });

    /* Редактирование пользователей */
    // $(document).on('click', '[data-userdata-update-button]', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             myModal.panel.text(data.textUserdata);
    //             myModal.panel.close();
    //         }
    //     }, f.href);
    //     return false;
    // })

    /* Сохранение отредактированного пользователя */
    // $(document).on('submit', '#form-userdata-update', function (e) {
    //     e.preventDefault();
    //     let f = this;
    //     let data = new FormData(this);
    //     myVar.ajax(function (data) {
    //         if (data.status) {
    //             $('#tableUserdata').html(data.textUserdata);
    //             myModal.panel.close();
    //         } else {
    //             myModal.error.show(data.text, 'Ошибка сохранения');
    //         }
    //     }, f.action, data);
    //     return false;
    // });

    /* Удаление пользователя */
    // $(document).on('click', '[data-userdata-delete-button]', function(e){
    //     e.preventDefault();
    //     if(confirm('Вы действительно хотите удалить пользователя?')){
    //         let f  = this;
    //         let href = f.href+'?id='+$(f).attr('data-userdata-delete-button');
    //         myVar.ajax(function (data) {
    //             if (data.status) {
    //                 $('#tableUserdata').html(data.textUserdata);
    //                 myModal.panel.close();
    //             }
    //         },  href);
    //     }
    //     return false;
    // })


    /* лайткейс */
    myVar.ligh();

    //console.log( myVar );

    /* Редактирование пароля */
    $(document).on('click', '[data-pass]', function (e) {
        e.preventDefault();
        let f = this;
        myVar.ajax(function (data) {
            myModal.panel.text(data.textPassword);
            myModal.panel.close();
        }, f.href);
        return false;
    });

    /* Вызов модального окна для отправки отзыва разработчикам */
    $(document).on('click', '[data-review]', function (event) {
        event.preventDefault();
        $('#footerModalReview').modal('show');
    });

    /* Вызов модального окна для отправки заявки на техническую поддержку */
    $(document).on('click', '[data-tech]', function (event) {
        event.preventDefault();
        $('#footerModalTech').modal('show');
    });

    /* Отправка отзыва */
    $(document).on('submit', '#footerNewModal', function (event) {
        event.preventDefault();
        let data = new FormData(this);
        let f = this;
        myVar.ajax(function (data) {
            $('#footerModalReview').modal('hide');
            myModal.info.show(data.text, data.title);
        }, f.action, data);
        return false;
    });

    /* Отправка заявки на техническую поддержку */
    $(document).on('submit', '#footerTechModal', function (event) {
        event.preventDefault();
        let data = new FormData(this);
        let f = this;
        myVar.ajax(function (data) {
            $('#footerModalTech').modal('hide');
            myModal.info.show(data.text, data.title);
        }, f.action, data);
        return false;
    });

    $(document).on('click', '[data-href]', function (event) {
        event.preventDefault();
        let href = $(this).attr('data-href');
        $.ajax({
            url: href,
            type: 'post',
            success: function (data) {
                $("[data-block='" + data.title + "']").remove();
            }
        });
        return false;
    });
});