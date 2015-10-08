function loadOrderData(obj){
    var order_id = $(obj).parents('tr').data('key'),
        loader = '<div class="loader"></div>',
        modalContent = $('#order-modal').find('.modal-body');
    modalContent.html(loader);
    $.ajax({
        url : '/autoparts/orders/manager-order',
        type : 'GET',
        data : {'id' : order_id},
        'success' : function(data){
            modalContent.html(data);
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
                $('#manager-order-grid-container').find('table').find('tr[data-key='+data.rel_det+']').find('td.detailStatus').text(data.state_text);

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

    content.attr('id','modal-body-1').css('display','none');
    header.after('<div class="loader"></div>');

    $.ajax({
        url : "/autoparts/orders/pricing",
        type : "POST",
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
            $('#modal-body-2').html(data);
            $(but).addClass('disabled');

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
        success : function(data){
            button.removeClass('btn-warning').addClass('btn-success');
            var row = '<tr class="warning" data-key="'+data.id+'"><td>'+data.product_article+'</td>' +
                '<td data-col-seq="1">'+(data.manufacture == '' ? '<span class="not-set">(не задано)</span>' : data.manufacture) +'</td>' +
                '<td data-col-seq="2">'+data.part_name+'</td>' +
                '<td data-col-seq="3">'+data.part_price+'</td>' +
                '<td data-col-seq="4">'+data.quantity+'</td>' +
                '<td data-col-seq="5">'+(data.quantity*data.part_price)+'</td>' +
                '<td data-col-seq="6" class="skip-export kv-align-center kv-align-top kv-row-select" style="width:50px;">' +
                '<input type="checkbox" name="selection[]" value="'+data.id+'" onclick="updatePaidStatus(this)">' +
                '</td>' +
                '<td data-col-seq="7">'+data.provider_id+'</td>' +
                '<td data-col-seq="8">'+data.delivery_days+'</td>' +
                '<td data-col-seq="9">' +
                '<select id="orderssearch-status" class="form-control" name="OrdersSearch[status]" style="min-width:125px" onchange="updateStatus(this)">' +
                '<option value="0" selected="">В ОБРАБОТКЕ</option>' +
                '<option value="1">ПРИНЯТ</option>' +
                '<option value="2">ЗАКАЗАН</option>' +
                '<option value="3">ДОСТАВЛЕН НА СКЛАД</option>' +
                '<option value="4">ДОСТАВЛЕН В МАГАЗИН</option>' +
                '<option value="5">ВЫПОЛНЕН</option>' +
                '<option value="6">АННУЛИРОВАН</option>' +
                '</select></select></td>' +
                '<td data-col-seq="10"><span class="not-set">(не задано)</span></td>' +
                '<td class="btn-group-sm skip-export kv-align-center kv-align-middle" style="width:80px;" data-col-seq="11"><button type="button" class="btn btn-danger" onclick="deleteDetail(\'/autoparts/orders/delete?id='+data.id+'\')"><span class="glyphicon glyphicon-remove"></span></button></td>'
            '</tr>';
            button.html('<span class="glyphicon glyphicon-ok"></span> ДОБАВЛЕН');
            var table = $('#manager-order-grid-container').find('table');
            table.append(row);
        }
    });
}
function deleteDetail(url){
    $.ajax({
        url : url,
        dataType : 'JSON',
        success : function(data){
            var table = $('#manager-order-grid-container').find('table');
            table.find('tr[data-key='+parseInt(data.id)+']').remove();
            table.find('tr[data-key='+parseInt(data.rel_det)+']').find('td.detailStatus').text(data.status_text);

        }
    });
}
function goTo(idx){
    $('.modal-body').hide();
    $('#modal-body-'+idx).show();
}

$(document).ready(function() {

    $('#w0').submit(function(){

        if ($('#uploadform-file').val())
        {
            $('#parent_popup').css('display','block');
        }


    });
});