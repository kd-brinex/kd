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
