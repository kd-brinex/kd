function setCookies(name,value)
{
    $.cookie(name, value, {path: '/'});
    window.location.reload();
    console.log(name,value);
}
