////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function expand(plus){

    var plusIn = plus.id,
        strid  = plusIn.replace(/\D/g, '');

    if( $('#plash_plus'+strid).html()!='+' ){
        $('#plash_plus'+strid).html('+');
        $('#plash_li'+strid).removeClass().addClass('plashka anime');
        $('#sp'+strid).attr('style','border-radius:5px').nextAll('ul').first().hide();
    }
    else{
        $('#plash_plus'+strid).html('&ndash;');
        $('#plash_li'+strid).removeClass().addClass('plashka2 anime');
        $('#sp'+strid).attr('style','border-radius:5px 5px 0 0').nextAll('ul').first().show();
    }


}
function expandLi(obj){
    $(obj).nextAll('ul').first().toggle();
}



