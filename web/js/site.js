function setCookie (name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + value +
    ((expires) ? "; expires=" + expires : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
function setCookies(name,value)
{
    var dExpire = new Date();
    dExpire.setDate( dExpire.getDate() + 21 );
    setCookie(name, value, dExpire, '/');
    window.location.reload();
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
        }
    });
    //$('#city_list').html('казань');
    //console.log('Казань');
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
    //$("#"+id+"_summa").text( $("#"+id+"_price").text()*count);
    //($("#"+id+"_price").value*count);
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
    //console.log(id,
    //$('#'+id+'_offer').attr('id'));
    //$('#'+id+'_offer').css("display","none");
    $.ajax({
        type: "POST",
        url: "/basket/del",
        data:{'id':id,
                'tovar_count':0},
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
                if ($(this).val() != 1) {
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
}
function toggleTab(tabNum){
    tabNum = tabNum ? tabNum : null;
    if(tabNum != null){
        $('#step'+tabNum).removeClass('pulse').find('i').removeClass('icon-warn').addClass('icon-circle-success');
        $('#'+tabNum+'-basket-tab  a[data-toggle="tab"]').click();
        $('.basketSteps').fadeOut(100).promise().done(function(){
            $('#step'+tabNum).fadeIn(300);
        });
        //window.scrollTo(0, 0);
        return true;
    }
}
function checkTab(){
    var keys = $('#BasketGrid').yiiGridView('getSelectedRows');
    if(!keys.length){

        toggleTab(1);
        basketError(1).scrollTop(0,0);
        return false;
    } else if(keys.length) {
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

//window.onload = function () {
//$('#city_list').click('load_city_list()');
//    //load_city_list();
//
//}