/**
 * Created by marat on 10.11.15.
 */
$(document).ready(function ()
{
    $.ajax(
        {
            type: "GET",
            url:'http://www.kolesa-darom.ru/kabinet/myautocatalog/',
            data:options
        }
    );
    //$.get('http://www.kolesa-darom.ru/kabinet/myautocatalog/', options);
});