function setCookie (name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + value +
    ((expires) ? "; expires=" + expires : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
function setCookies(name,value,cl)
{
    var dExpire = new Date();
    dExpire.setDate( dExpire.getDate() + 21 );
    setCookie(name, value, dExpire, '/');
    if (cl)
    {window.location.reload()};
    console.log(name,value);
}
function hideButton(char,class1,class2){
    console.log(char,class1,class2);
    $('.'+class2).attr("class",class1)
    $('[char = '+char+']').attr("class", class2);
    //$('.'+class1).css('color','#ccc');
    //$('.'+class2).css('color','#000');
    $('.'+class1).css('display','none');
    $('.'+class2).css('display','');

}
function load_city_list(){
    $.ajax({
        type: "GET",
        url: "/city/city/list",
        data:"",
        success: function(text){
            $('#city_list').html(text);
            //$('#city_list1').html(text);
            $('.cities').show();
            $('.cities_select').html('<div class="div_stories_s">'+$('.stories_s').html()+'</div>'+'<div class="div_stories_all">'+$('.stories_all').html()+'</div>');
        }
    });
    //$('.modal-dialog').animate({width: "950px"} , 190);
    //$('#modal-backdrop').css('z-index',1);

}
function load_city_list_region(){
    $.ajax({
        type: "GET",
        url: "/city/city/list_region",
        data:"",
        success: function(text){
            $('#city_list').html(text);
            $('#city_list1').html(text);
            $('.cities').hide();
        }
    });

    $('.modal-dialog').animate({width: "350px"} , 190);

}
function detail_info(model,tree){
    $.ajax({
        type: "GET",
        url: "/auto/auto/detailinfo",
        data:{'model':model,
                'tree':tree},
        success: function(text){
            var _top  = (window.innerHeight)/2;
            var _left = (window.innerWidth/2)-400;
            var inf=$("#"+tree);
            inf.append(text);
            inf.css('z-index',7);
            //inf.css('top',_top);
            //inf.css('left',_left);
            inf.css('width',600);
            inf.css('height',300);
            inf.css('position','absolute');
        }
    });
    //$('#city_list').html('казань');
    //console.log('Казань');
}

function put(o){
    var id = $(o).attr('tovar_id') ;
    $.ajax({
        type: "POST",
        url: "/basket/put",
        data:{'id':id},
        success: function(result){
            $(o).html(result);
        }
    });
}
function count(o){
   var count = o.value;
   var id = o.id;
    $.ajax({
        type: "POST",
        url: "/basket/count",
        data:{'id':id,
              'tovar_count':count},
        success: function(result){
            console.log("succesful");
            $("#basket").html(result);
        }
    });
}
function del(o){
    var id = $('div',o).attr('tovar_id') ;
    $.ajax({
        type: "POST",
        url: "/basket/del",
        data:{'id':id, 'tovar_count':0},
        success: function(result){
            $("#basket").html(result);
        }
    });
}

function removeBasketItems(){
    var keys = $('#BasketGrid').yiiGridView('getSelectedRows');
    if(keys.length > 0) {
        var conf = confirm('Вы уверены что хотите удалить выбранные товары?');
        if(conf) {
            $.ajax({
                'url': '/basket/remove',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': keys},
                'success': function (d) {
                    if ('id' in d) {
                        for (var item in d.id) {
                            $('tr[data-key="' + d.id[item] + '"]').animate({opacity:0},500, function(){
                                    $(this).remove();
                                    countBasketSum();
                                    if($('tr[data-key]').length == 0){
                                        $("#basket").replaceWith('<p>Ваша корзина пуста.</p>');
                                }
                            });
                        }
                    }
                }
            });
        }
    }
}
function detailCounter(obj, position){
    if(position != null){
        var $this = $(obj);
        $.ajax({
            'type' : 'POST',
            'url' : '/basket/changeQuantity',
            'data' : {'position' : position, 'quantity' : $this.val()},
            'dataType' : 'JSON',
            'success' : function(data){
                console.log(data);
            }
        });
    }
}
function countBasketSum(){
    var mainSum = 0;
    $('#BasketGrid').find('tr').each(function(){
            var itemPrice = $(this).find('td.itemPrice').text();
            var itemCount = $(this).find('td.itemCount > input').val();
            if(itemPrice != undefined && itemCount != undefined){
                var rowSum = itemPrice*itemCount;
                mainSum += rowSum;
                $(this).find('td.itemFullPrice').text(rowSum.toFixed(2));
            }
        }
    );
    countBasketMarkedItemsSum();
    $('.basked-all-items strong').html(mainSum.toFixed(2)+' руб.');

}
function countBasketMarkedItemsSum(){
    setTimeout(function() {
            var allMarkedItems = $('#BasketGrid').find('input[type=checkbox]:checked');
            var mainPrice = 0;
            var allItemsCount = allMarkedItems.length;
            allMarkedItems.each(function () {
                if ($(this).val() != 0) {
                    var rowId = $(this).val();
                    var itemPrice = parseInt($('tr[data-key=' + rowId + '] > td.itemPrice').text());
                    var itemCount = parseInt($('tr[data-key=' + rowId + '] > td.itemCount input').val());
                    mainPrice += itemPrice * itemCount;
                }
            });
            if($('#BasketGrid').find('input.select-on-check-all').prop('checked'))
                allItemsCount -= 1;

            $('.basket-marked-items strong:first-child').text(allItemsCount);
            $('.basket-marked-items strong:last-child').text(mainPrice.toFixed(2));
        }
    ,100);
}
function basketMessage(message){
    $("#basketError").html(message);
}
function basketError(step){
    $("#step"+step).addClass('pulse').find('i').removeClass('icon-circle-success').addClass('icon-warn');
    $('body,html').animate({
        scrollTop: 0
    }, 200);
    return false;


}
function toggleTab(tabNum){
    tabNum = tabNum ? tabNum : null;
    //if(checkTab() !== true)
    //    return false;
    if(tabNum != null){
        var stepBlock = $('#step'+tabNum);
        if(stepBlock.hasClass('pulse'))
            stepBlock.removeClass('pulse').find('i').removeClass('icon-warn').addClass('icon-circle-success');
        $('#'+tabNum+'-basket-tab  a[data-toggle="tab"]').click();
        //$('.basketSteps').fadeOut(0).promise().done(function(){
        //    $('#step'+tabNum).fadeIn(300);
        //});
        return true;
    }
}
function checkTab(){
    var tab = parseInt($('#profile-form').find('ul > li.active').attr('id'));
    var keys = $('#BasketGrid').yiiGridView('getSelectedRows');
    if(!keys.length){
        toggleTab(1);
        basketError(1);
        return false;
    } else if(keys.length) {
        toggleTab(tab++);
        var errors = 0;
        $('#user .form-group').each(function () {
            if ($(this).find('input').val() == '') {
                errors++;
            }
            if ($(this).find('.help-block').html() != '') {
                errors++;
            }
        });
        if (errors) {
            toggleTab(2);
            basketError(2);
            return false;
        } else {
            toggleTab(tab++);
            var deliverySlots = $('#basketDeliveryList input[type=radio]');
            if(deliverySlots.length > 0){
                if($('#basketDeliveryList input[type=radio]:checked').length > 0)
                    return true;
                else {
                    basketError(3);
                    return false;
                }
            } else
                return true;
        }
    }
    return true;
}
function createOrder(){
        if(checkTab() !== true)
            return false;
        if(confirm('Вы уверены что хотите сделать заказ?')) {
            var toOrder = '';
            var c = 0;
            var keys = $('#BasketGrid').yiiGridView('getSelectedRows');
            for(var k in keys){
                c++;
                var itemCount = $('tr[data-key='+keys[k]+']').find('td.itemCount > input[type=number]').val();
                   toOrder += keys[k]+':'+itemCount;
                   if(c != keys.length)
                    toOrder += ';';
            }
            $.ajax({
                'url' : '/basket/order',
                'type':'POST',
                'data' : {"orderData": toOrder, "formData" : $("#user .form-group input, #basketDeliveryList input[type=radio]:checked").serialize
                ()},
                'success' : function(d){
                    $('#1-basket-tab  a[data-toggle="tab"]').click();
                    if(d){
                        $('.basketSteps').each(function(){
                           var $this = $(this);
                           if($this.hasClass('pulse')){
                               $this.removeClass('pulse')
                                    .find('i')
                                    .removeClass('icon-warn')
                                    .addClass('icon-circle-success');
                           }
                        });
                        for(var k in keys){
                            $('tr[data-key='+keys[k]+']').animate({opacity:0},500, function(){
                                $(this).remove();
                                countBasketSum();
                                if($('tr[data-key]').length == 0){
                                    $("#basket").replaceWith('<p>Ваш заказ отправлен в отдел обработки.</p>');
                                }
                            });
                        }
                    }

                }
            });
        }
}

function openOrder(obj){
    $.post('/user/settings/order', {'id' : $(obj).data('id')},
        function(data){
            $('.modal-body').html(data);
            $('#order-modal').modal();
        }
    );
}
function deliveryChoiceClicker(obj){
    var box = $(obj).parent();
    box.find('.store-check input[type=radio]').prop('checked',true);
    $('.checkedDeliveryBox').removeClass('checkedDeliveryBox');
    box.addClass('checkedDeliveryBox');
}
function editText(elem){
    var obj = $(elem);
    var area = obj.siblings('textarea');
    var areaText = area.val();
    obj.siblings('#oldText').text(areaText);
    obj.find('i').removeClass('icon-edit').addClass('icon-success').parent('a').css({backgroundColor:'#449d44'}).attr('onClick', 'successText(this)');
    obj.siblings('.grid-left-up-corner').fadeIn(300);
    area.addClass('form-control editableInput').prop('readonly', false).focus();
}
function cancelEdit(elem){
    var obj = $(elem);
    var area = obj.siblings('textarea');
    var areaText = area.text();
    area.val(obj.siblings('#oldText').text()).removeClass('form-control editableInput').prop('readOnly', true);
    obj.siblings('.grid-right-up-corner').find('i').removeClass('icon-success').addClass('icon-edit').parent('a').css({backgroundColor:'#399FEA'}).attr('onClick', 'editText(this)');
    obj.fadeOut(300);
}
function successText(elem){
    var obj = $(elem);
    var area = obj.siblings('textarea');
    var row_id = obj.parent().parent().attr('data-key');
    if(area.val() == '' || area.val() == 'Ввести описание')
        area.val('Ввести описание');
    else {
        $.ajax({
            'type' : 'POST',
            'url' : '/basket/update',
            'data' : {text: area.val(), 'row_id': row_id}
        });
    }
        //.replace(/\n/g, '')
    area.removeClass('form-control editableInput').prop('readOnly', true);
    obj.find('i').removeClass('icon-success').addClass('icon-edit').parent('a').css({backgroundColor:'#399FEA'}).attr('onClick', 'editText(this)');
    obj.siblings('.grid-left-up-corner').fadeOut(300);


}

$("#city_select").on('input', function () {
    $("ul.cities_select").removeClass("invisible");
    var count_s = 0;
    $('.cities_select li').each(function (i, elem) {
        var str = $(elem).text();
        if ((str.toUpperCase()).indexOf(($('#city_select').val()).toUpperCase()) + 1) {
            $(this).removeClass('invisible');
            $(this).addClass('visible');
            count_s = 1;
        }
        else {
            $(this).removeClass('visible');
            $(this).addClass('invisible');
        }

    });
    if (count_s < 1) {
        $('.ul_title').hide();
        $('.not_found').show();
    }
    else {
        $('.ul_title').show();
        $('.not_found').hide();
    }
    if ($('.div_stories_s>li.visible').length < 1) {
        $('.div_stories_s>p').hide();
        if (count_s < 1) {
            $('.ul_title').hide();
            $('.not_found').show();
        }
        else {

            $('.not_found').hide();
        }
    }
    if ($('.div_stories_all>li.visible').length < 1) {
        $('.div_stories_all>p').hide();

    }



});
$('#city_select').click(function(){
    $( "ul.cities_select" ).toggleClass( "invisible" );
    $( ".cities" ).toggleClass( "open" );
});

//window.onload = function () {
//$('#city_list').click('load_city_list()');
//    //load_city_list();
//
//}
function notify(message, type){
    var message = message != null ? message : 'Текст сообщения отсутствует';
    var type = type != null ? type : 'info';
    $.notify({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'СООБЩЕНИЕ',
        message: message,
       // url: 'https://',
        target: '_blank'
    },{
        element: 'body',
        position: null,
        type: type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "left"
        },
        offset: 20,
        spacing: 10,
        z_index: 100000,
        delay: 5000,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated bounceInLeft',
            exit: 'animated bounceOutLeft'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<div class="notify-message-block"> ' +
        '<p data-notify="title">{1}</p> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div></div>'
    });
}
function sendAllToProvider(){
    var keys = $('#orders-manage-grid').yiiGridView('getSelectedRows');
    if(keys.length > 0){
        $.ajax({
            'url' : '/admin/orders/send',
            'type' : 'POST',
            'data' : {'id' : keys},
            'success' : function(d){
                alert(d);
            }
        });
    } else {
        notify('Вы не выбрали ни одного заказа', 'danger');
    }
}
