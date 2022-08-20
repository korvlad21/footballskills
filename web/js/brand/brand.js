/* global myModal, myVar, PubSub */

$(document).ready(function () {

    $("input[name=search]").keyup(function (e) {
        var n,
                tree = $.ui.fancytree.getTree(),
                match = $(this).val(),
                filterFunc = tree.filterBranches;
        filterFunc.call(tree, match);

        if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
            $("button#btnResetSearch").click();
            return;
        }
    }).focus();


    $(document).bind('keydown', 'ctrl+s', function () {
        $("#form-add-brand").trigger('submit');
        return false;
    });

    $(document).on('click', '[data-button-tree]', function (e) {
        e.preventDefault();
        let t = $(this).attr('data-button-tree');
        if (myVar.idTree === 0 && t !== 'create') {
            myModal.info.show('Выберите продукцию');
        } else {
            switch (t) {
                case 'down':
                case 'up':
                case 'right':
                case 'left':
                    goBrand(this);
                    break;
                case 'create':
                    createBrand(this);
                    break;
                case 'update':
                    updateBrand(this);
                    break;
                case 'delete':
                    if (confirm('Удалить категорию?')) {
                        goBrand(this);
                        myVar.idTree = 0;
                        OnOffButton(false);
                    }
                    break;
            }
        }
        return false;
    });

    function goBrand(element, f = true) {
        let href = element.href;
        myVar.ajax(function (data) {
            if (data.status) {
                if (f) {
                    indexHtmlBrand(data, true, false);
                } else {
                    indexHtmlBrand(data);
                }

            } else {
                myModal.info.show(data.text, 'Предупреждение', 3000);
            }
        }, href + "?id=" + myVar.idTree);
    }

    function createBrand(element) {

        let href = element.href;
        myVar.ajax(function (data) {
            if (data.status) {
                myModal.panel.close();
                myModal.panel.text(data.text);
                myVar.ligh();
            }
        }, href);
    }

    function updateBrand(element) {
        let href = element.href;

        myVar.ajax(function (data) {
            if (data.status) {
                myModal.panel.close();
                myModal.panel.text(data.text);
                myVar.ligh();
            }
        }, href + "?id=" + myVar.idTree);
    }

    /* Отправка форм для сохрания нового и отредактированного*/
    $(document).on('submit', '#form-add-brand, #form-update-brand', function (e) {
        e.preventDefault();
        myVar.ajaxForm(this, function (data) {
            if (data.status) {
                indexHtmlBrand(data);
                myModal.panel.close();
                myVar.ligh();
            } else {
                myModal.info.show(data.text, 'Ошибка сохранения');
            }
        });
        return false;
    });

    function indexHtmlBrand(data, d = true, f = true) {
        if (d) {
            $('#box-body-tree').html(data.text[0].tree);
        }
        if (f) {
            $('#data-modal').html(data.text[0].brand);
            $('#data-modal-good').html(data.text[0].goods);
    }
    }

    PubSub.subscribe('activateTree', function (data) {
        OnOffButton();
        let brand = $('[data-href-id-packet-add]');
        let id =  data.id;
        $(brand).attr('href', ($(brand).attr('data-href') + '?id='+id));
        myVar.ajax(function (data) {
            indexHtmlBrand(data, false)
        }, data.urlBrand);
    });

    function OnOffButton(d = true) {
        $('[data-button-no-active-pr]').each(function (i, e) {
            $(e).attr('data-button-no-active-pr', !d);
        });
    }

    $(document).on('click', '[data-update-property]', function (e) {
        e.preventDefault();
        let href = this.href;
        myVar.ajax(function (data) {
            if (data.status) {
                myModal.panel.close();
                myModal.panel.text(data.text);
            } else {
                myModal.info.show(data.text, 'Ошибка');
            }
        }, href);
        return false;
    });

    $(document).on('click', '[data-count-tr]', function (e) {
        e.preventDefault();
        let href = this.href + "&count=" + $(this).attr('data-count-tr');
        let f = this;
        myVar.ajax(function (data) {
            if (data.status) {
                $('#tbody-property-add-tr').append(data.text);
                $(f).attr('data-count-tr', data.count);
            } else {
                myModal.info.show(data.text, 'Ошибка');
            }
        }, href);
        return false;
    });

    $(document).on('click', '[data-delete-tr-property]', function (e) {
        $(this).parent().parent().remove();
    });

    $(document).on('change', '[data-count-td-property]', function (e) {
        e.preventDefault();
        let t = this;
        myVar.ajax(function (data) {
            $("[data-count-td-value='" + $(t).attr('data-count-td-property') + "']").html(data.text);
        }, $(t).attr('data-url') + '?id=' + t.value);
        return false;
    });

    $(document).on('submit', '#form-save-property', function (e) {
        e.preventDefault();
        myVar.ajaxForm(this, function (data) {
            if (data.status) {
                myModal.panel.close();
                $('#data-modal-good').html(data.text);
            } else {
                myModal.info.show(data.text, 'Ошибка');
            }
        });
        return false;
    });

    $(document).on('click', '[data-delete-goods]', function (e) {
        e.preventDefault();
        if (confirm('Вы уверены что хотите удалить данную позицию?')) {
            myVar.ajaxA(this, function (data) {
                if (data.status) {
                    $('#data-modal-good').html(data.text);
                }
            });
        }
        return false;
    });

    $(document).on('click', '[data-add-goods]', function (e) {
        e.preventDefault();
        myVar.ajaxA(this, function (data) {
            if (data.status) {
                myModal.panel.close();
                myModal.panel.text(data.text);
            }
        })
        return false;
    });

    $(document).on('click', '[data-unite-goods]', function (e) {
        e.preventDefault();
        let dataCh = $('[data-checkbox]:checked');
        if (dataCh.length < 2) {
            myModal.info.show('Выделить больше одного товара', 'Предупреждение', 3000);
        } else {
            if (confirm('Объединить выбранные элементы?')) {
                let data = new FormData();
                let t = this;
                for (let i = 0; i < dataCh.length; i++) {
                    data.append('id[]', dataCh[i].value)
                }
                myVar.ajax(function (data) {
                    if (data.status) {
                        $('#data-modal-good').html(data.text);
                    } else {
                        myModal.info.show(data.text, 'Ошибка');
                    }

                }, t.href, data);
            }

        }
        return false;
    });

});


