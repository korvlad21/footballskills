window.addEventListener('load', ( ev ) => {

    function createFormData( element ) {
        let id;
        let formData = new FormData();

        switch ( element.dataset.action ) {
            case '/icms/order/exec-order':
                id =  element.dataset.key;
                formData.append( yii.getCsrfParam(), yii.getCsrfToken() );
                formData.append( 'Order[id]', id );

                return formData;
            break;

            case '/icms/order/set-status-pay':
                id =  element.dataset.key;
                formData.append( yii.getCsrfParam(), yii.getCsrfToken() );
                formData.append( 'Order[id]', id );
                let selectorr = document.getElementById(`Order-order-status-pay-${id}`);
                formData.append( 'Order[order_status_pay]', selectorr.value );
                return formData;
            break;

            case '/icms/order/set-status':
                id =  element.dataset.key;
                let selector = document.getElementById(`Order-order-status-${id}`);

                //console.log( selector );
                //console.log( selector.value );

                formData.append( yii.getCsrfParam(), yii.getCsrfToken() );
                formData.append( 'Order[id]', id );
                formData.append( 'Order[order_status]', selector.value );

                return formData;
            break;
        }
    };

    const goodsContainer = document.querySelector('div.box-goods');
    goodsContainer.addEventListener( 'click', function ( e ) {
        const target = e.target;
        const action = target.dataset.action;
        if ( action != undefined ) {
            const describe = target.closest('[data-order-describe]');
            if ( describe != null ) {
                const formData = createFormData( target );

                let promise = new Promise( ( resolve, reject ) => {
                    $.ajax({
                        url: action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: resolve,
                        error: reject
                    });
                });

                //console.log( promise );

                promise.then(
                    ( response ) => {
                        //console.log( response );
                        if ( response.status == true ) {
                            if ( response.action == 'exec-order' ) {
                                const orderExecParent = target.closest('[data-order-exec]');
                                const execEl = orderExecParent.querySelector('span');
                                execEl.innnerText = 'Да';
                                target.remove();
                            } else if ( response.action == 'set-status' ) {

                            }
                            
                            location.reload();
                        }

                    }, 
                    ( error ) => {
                        //console.log( error );
                    } 
                );
                
            }
        }
    });

    


});