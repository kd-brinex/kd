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


$(document).ready(function() {

    $('#w0').submit(function(){

        if ($('#uploadform-file').val())
        {
            $('#parent_popup').css('display','block');
        }


    });
});