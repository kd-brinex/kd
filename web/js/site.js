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
function put(id){
    $.ajax({
        type: "POST",
        url: "/basket/default/put",
        data:{'id':id},
        success: function(text){
            console.log(text);
        }
    });
}
//window.onload = function () {
//$('#city_list').click('load_city_list()');
//    //load_city_list();
//
//}