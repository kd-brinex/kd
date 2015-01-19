function setCookies(name,value)
{
    $.cookie(name, value, {path: '/'git });
    window.location.reload();
    console.log(name,value);
}
