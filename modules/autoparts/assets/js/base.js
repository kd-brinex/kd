function loadOrderData(obj){
    var order_id = $(obj).parents('tr').data('key'),
        loader = '<div class="loader"></div>',
        modalContent = $('#order-modal').find('.modal-body');
    modalContent.html(loader);
    $.ajax({
        url : '/autoparts/orders/managerorder',
        type : 'POST',
        data : {'id' : order_id},
        'success' : function(data){
            modalContent.html(data);
        }
    });
}

function updatePaidStatus(obj){
    var position_id = $(obj).val();
    $.ajax({
        url : '/autoparts/orders/ordersupdate',
        type : 'POST',
        data : {'id' : position_id}
    });
}

function updateStatus(obj){
    var status = $(obj).val(),
        position_id = $(obj).parents('tr').data('key');
    $.ajax({
        url : '/autoparts/orders/ordersstateupdate',
        type : 'POST',
        data : {
            'status' : status, 'id' : position_id
        },
        dataType : 'JSON',
        success : function(data){
            if('status' in data && 'id' in data){
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

function pricing(obj){
    var content = $('.modal-body'),
        header = $('.modal-header');
        articles = $('.part_article').text();
    content.attr('id','modal-body-1').css('display','none');
    header.after('<div class="loader"></div>');

    var s = articles.split(' ');
    alert(s[1]);
    //$.ajax({
    //    url : "/autoparts/orders/pricing",
    //    type : "POST",
    //    //data : {'articles' : },
    //    success : function(data){
    //        $('#tableToggler').show();
    //        $('.loader').remove();
    //        header.after('<div class="modal-body" id="modal-body-2"></div>');
    //        $('#modal-body-2').html(data);
    //    }
    //});
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