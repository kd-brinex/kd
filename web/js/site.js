function setCookies(name,value)
{
    $.cookie(name, value, {  path: '/' });
    window.location.reload(true);
    //console.log(value);
}
