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
//window.onload = function () {
//$('#city_list').click('load_city_list()');
//    //load_city_list();
//
//}