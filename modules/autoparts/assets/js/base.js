function loadOrderData(obj){
    var order_id = $(obj).parents('tr').data('key'),
        loader = '<div class="loader"></div>',
        modalContent = $('#order-modal').find('.modal-body');
    modalContent.html(loader);
    $.ajax({
        url : '/autoparts/orders/manager-order?sort=-order_provider_id',
        type : 'GET',
        data : {'id' : order_id},
        'success' : function(data){
            modalContent.html(data);
            //$('table th a.desc').click();
            history.pushState(null, null, '/autoparts/orders/manager-order?id='+order_id);
            //$.pjax.reload({container:'#manager-order-grid-pjax-container', push: false, replace: false,  url:'/autoparts/orders/manager-order'});
        }
    });
}

function updatePaidStatus(obj){
    var position_id = $(obj).val();
    $.ajax({
        url : '/autoparts/orders/orders-update',
        type : 'POST',
        data : {'id' : position_id}
    });
}

function updateStatus(obj){
    var status = $(obj).val(),
        position_id = $(obj).parents('tr').data('key');
    $.ajax({
        url : '/autoparts/orders/orders-state-update',
        type : 'POST',
        data : {
            'status' : status, 'id' : position_id
        },
        dataType : 'JSON',
        success : function(data){
            if('status' in data && 'id' in data){
                $('#manager-order-grid-container').find('table').find('tr[data-key='+data.rel_det+']').find('td.detailStatus > select').val(data.min_state);

                if((data.status <= 1  && data.old_status <= 1) || (data.status > 1  && data.old_status > 1))
                    return;

                var progress_bar = $('#orders-manage-grid').find('tr[data-key=' + data.id + ']').find('.progress-bar'),
                    orders = $('#manager-order-grid').find('tr').length - 1,
                    step = Math.floor(100 / orders),
                    now_value = parseInt(progress_bar.attr('aria-valuenow')),
                    plus_value = now_value+step,
                    minus_value = now_value-step;
                if(data.status > 1) {
                    progress_bar.attr('aria-valuenow', plus_value).css('width', plus_value+'%').text(plus_value+'%');
                } else if(data.status <= 1){
                    progress_bar.attr('aria-valuenow', minus_value).css('width', minus_value+'%').text(minus_value+'%');
                }
            }
        }
    });
}

function sendTo1C(id, but){
    var button = $(but);
    $.ajax({
        url: "/autoparts/orders/send-to1c",
        data: {'id': id},
        error: function(){
            button.removeAttr('style').addClass('btn-danger').html('<span class="glyphicon glyphicon-alert"></span> ОШИБКА');
        },
        success : function(data){
            button.removeAttr('style').addClass('btn-default disabled');
        }
    });
}

function pricing(id, but){

    var content = $('.modal-body'),
        header = $('.modal-header');

    if($('#modal-body-2').length > 0) {
        $('#modal-body-2').remove();
    }

    if($('#modal-body-1').length == 0){
        content.attr('id','modal-body-1').css('display','none');
    } else {
        $('#modal-body-1').hide();
    }

    header.after('<div class="loader"></div>');
    $.ajax({
        url : "/autoparts/orders/pricing",
        type : "POST",
        dataType : "JSON",
        data : {'order' : id},
        error: function(XHR, status, errorThrown){
            var error_text = 'Ошибка сервера('+status+'): <strong style="color:#ff5244">'+errorThrown+'.</strong>';
            $('.loader').remove();
            header.after('<div class="modal-body" id="modal-body-2"></div>');
            $('#modal-body-2').html(error_text);
        },

        success : function(data){
            $('#tableToggler').show();
            $('.loader').remove();
            header.after('<div class="modal-body" id="modal-body-2"></div>');
            $('#modal-body-2').html(data.table);
        }
    });
}
function inOrder(obj, url, data){
    var button = $(obj),
        quantity = parseInt(button.parent().prevAll('.quantity').find('input').val()),
        detailQuantity = parseInt(data.quantity);

    if(quantity <= 0 || quantity > detailQuantity) {
        notify('Количество данной детали должно быть больше нуля и не может превышать ' + detailQuantity + ' шт.');
        return false
    } else data.quantity = quantity;

    $.ajax({
        url : url,
        type : 'POST',
        dataType : 'JSON',
        data : data,
        error: function(){
            button.removeClass('btn-primary').addClass('btn-danger');
            button.html('<span class="glyphicon glyphicon-alert"></span> ОШИБКА');
        },
        beforeSend: function(){
            button.prop('disabled', true);
            button.removeClass('btn-primary').addClass('btn-warning');
            button.html('<span class="glyphicon glyphicon-time"></span> ЖДУ ОТВЕТА...');
        },
        success : function(){
            button.removeClass('btn-warning').addClass('btn-success');
            $.pjax.reload({container:'#manager-order-grid-pjax-container'});
            button.html('<span class="glyphicon glyphicon-ok"></span> ДОБАВЛЕН');
        }
    });
}
function deleteDetail(url){
    if(confirm('Вы уверены что хотите удалить деталь из списка заказов?'))
        $.ajax({
            url : url,
            type: 'POST',
            success : function(){
                $.pjax.reload({container:'#manager-order-grid-pjax-container'});
            }
        });
}

function deleteMainDetail(url){
    if(confirm('Вы уверены что хотите удалить деталь из списка заказов?'))
        $.ajax({
            url : url,
            success : function(){
                $.pjax.reload({container:'#manager-order-grid-pjax-container'});
            }
        });
}

function goTo(idx){
    $('.modal-body').hide();
    $('#modal-body-'+idx).show();
}

function addPosition(order, obj){
    $(obj).empty();
}
$(document).ready(function() {

    $('#order-modal').on('hide.bs.modal', function(){
        $('#modal-body-2').remove();
        $('#modal-body-1').removeAttr('id').empty().show();
        history.pushState(null, null, '/autoparts/orders');
    });

    $('#w0').submit(function(){

        if ($('#uploadform-file').val())
        {
            $('#parent_popup').css('display','block');
        }


    });
});


function setOrderProviderState(id, obj){
    $.ajax({
        url : '/autoparts/orders/provider-order-state-update',
        type : 'POST',
        data : {'id' : id, 'Orders' : { 'order_provider_status' : obj.value }},
        success : function(data){
            if(data != ''){
                $('#order-status-'+id+'-field').val(data).change();
            }
        }
    });
}