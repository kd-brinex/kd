////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   JS Simple FW   ///////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Protect window.console method calls, e.g. console is not defined on IE
 * unless dev tools are open, and IE doesn't define console.debug
 */
(function() {
    if (!window.console) {
        window.console = {};
    }
    /// union of Chrome, FF, IE, and Safari console methods
    var m = [
        "log", "info", "warn", "error", "debug", "trace", "dir", "group",
        "groupCollapsed", "groupEnd", "time", "timeEnd", "profile", "profileEnd",
        "dirxml", "assert", "count", "markTimeline", "timeStamp", "clear"
    ];
    /// define undefined methods as noops to prevent errors
    for (var i = 0; i < m.length; i++) {
        if (!window.console[m[i]]) {
            window.console[m[i]] = function() {};
        }
    }
})();
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * preventDefault
 */
function preventDefault(e){
    e = e || window.event;
    if( e.preventDefault ) e.preventDefault();
    e.returnValue = false;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Scroll To Hash Tag || Element (ID|Class)
 */
function scrollToHashTag( hashTag ){
    var headerHeight = $('header').outerHeight(true);
    var $root = $('html, body');
    var ancloc = hashTag || window.location.hash;
    var $to = $(ancloc);
    if( $to.length>0 ){
        preventDefault(false);
        $root.animate({
                scrollTop: $to.offset().top - headerHeight - 30
            }, 750,
            function(){ /*window.location.hash = href;*/ }
        );
        return false;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Instance JS Object
 */
var Instance = function() {
    if( this._instance ) return this._instance;
    return this._instance = new this; ///For Function
    //return this._instance = this; ///For Object Only
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Get GET params
 * Ex: var id = GetParameter("id");
 */
function GetParameter(sParameterName){
    var scriptList = document.getElementsByTagName("script");
    var lastScript = scriptList[scriptList.length-1];
    var script_link = lastScript.src;
    var Parameters = script_link.substring(script_link.indexOf("?")+1).split("&"); // отсекаем «?» и вносим переменные и их значения в массив
    var value = "";
    for( var i = 0; i < Parameters.length; i++ ){ // пробегаем весь массив
        if( Parameters[i].split("=")[0] === sParameterName ){ // если это искомая переменная — бинго!
            if( Parameters[i].split("=").length > 1 ) value = Parameters[i].split("=")[1]; // если значение параметра задано, то возвращаем его
            return value;
        }
    }
    return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Возвращает обработчик,
 * который вызывает handler при реальном уходе с элемента,
 * не учитывая дочение элементы
 */
function handleMouseLeave(handler) {
    return function(e) {
        e = e || event; // IE
        var toElement = e.relatedTarget || e.toElement; // IE
        // проверяем, мышь ушла на элемент внутри текущего?
        while (toElement && toElement !== this) {
            toElement = toElement.parentNode;
        }
        if (toElement == this) { // да, мы всё еще внутри родителя
            return; // мы перешли с родителя на потомка, лишнее событие
        }
        return handler.call(this, e);
    };
}
/**
 * Возвращает обработчик,
 * который вызывает handler наведение на элемент,
 * не учитывая дочение элементы
 */
function handleMouseEnter(handler) {
    return function(e) {
        e = e || event; // IE
        var toElement = e.relatedTarget || e.srcElement; // IE
        // проверяем, мышь пришла с элемента внутри текущего?
        while (toElement && toElement !== this) {
            toElement = toElement.parentNode;
        }
        if (toElement == this) { // да, мышь перешла изнутри родителя
            return; // мы перешли на родителя из потомка, лишнее событие
        }
        return handler.call(this, e);
    };
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Page Scroll Enable/Disable
 */
/// left: 37, up: 38, right: 39, down: 40,
/// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
var keys = [37, 38, 39, 40];
function keydown(e) {
    for( var i = keys.length; i--; ){
        if( e.keyCode === keys[i] ){
            preventDefault(e);
            return;
        }
    }
}
function wheel(e){ preventDefault(e); }
function disableScroll(){
    if( window.addEventListener ){
        window.addEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = wheel;
    document.onkeydown = keydown;
}
function enableScroll(){
    if( window.removeEventListener ){
        window.removeEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = document.onkeydown = null;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Escape all RegEx reserved characters from string
function escRegExp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}
/// Return matched elements based on regex contents
function highlight(regex, element, child) {
    // Create a regex based on the bot match string
    var regex = new RegExp(escRegExp(regex), 'gim');
    // Generate results based on regex matches within match_parent
    var results = [];
    // Check for element
    if($(element).length) {
        // Match regex on parent element
        var match = $(element).text().match(regex);
        if(match != null) {
            // Push our matches onto results
            $(element).find(child).each(function(index, value) {
                // Push child onto to results array if it contains our regex
                if($(this).text().match(regex)) results.push($(this));
            });
        }
    }
    return results;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// ---   Core                                                                                                   --- ///
function idShow(id){ $('#'+id ).show(); }
function idHide(id){ $('#'+id ).hide(); }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   ФУНКЦИЯ ИЗМЕНЕНИЯ ЦВЕТА ССЫЛОК В МЕНЮ НА СООТВЕТСТВУЮЩЕЙ СТРАНИЦЕ   //////////////////////////////////////////////
function headNav(){
    var href  = window.location.href.split('/'),
        url   = 'http://'+document.domain+'/'+href[3],
        links = $('nav li a')
        ;
    ///console.log(links);
    $.each(links, function(i,e){
        if( e.href == url ){
            $(e).css('color', '#329ef5');
        }
    });
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   Tooltip only Text   //////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function titleTooltip(elem){
    $(elem).hover(function(){
        /// Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="jtt"></p>')
            .text(title)
            .appendTo('body')
            .fadeIn('slow');
    }, function() {
        /// Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.jtt').remove();
    }).mousemove(function(e) {
        var mousex = e.pageX + 20; /// Get X coordinates
        var mousey = e.pageY + 10; /// Get Y coordinates
        $('.jtt').css({ top: mousey, left: mousex })
    });
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
 * jQuery CORS
 */
/// Тупо длинная ф-я, тупо мешается, тупо в конец файлы без всякого смысла...
if( !jQuery.support.cors && window.XDomainRequest ){
    var httpRegEx = /^https?:\/\//i;
    var getOrPostRegEx = /^get|post$/i;
    var sameSchemeRegEx = new RegExp('^'+location.protocol, 'i');
    var xmlRegEx = /\/xml/i;

    // ajaxTransport exists in jQuery 1.5+
    jQuery.ajaxTransport('text html xml json', function(options, userOptions, jqXHR){
        // XDomainRequests must be: asynchronous, GET or POST methods, HTTP or HTTPS protocol, and same scheme as calling page
        if (options.crossDomain && options.async && getOrPostRegEx.test(options.type) && httpRegEx.test(userOptions.url) && sameSchemeRegEx.test(userOptions.url)) {
            var xdr = null;
            var userType = (userOptions.dataType||'').toLowerCase();
            return {
                send: function(headers, complete){
                    xdr = new XDomainRequest();
                    if (/^\d+$/.test(userOptions.timeout)) {
                        xdr.timeout = userOptions.timeout;
                    }
                    xdr.ontimeout = function(){
                        complete(500, 'timeout');
                    };
                    xdr.onload = function(){
                        var allResponseHeaders = 'Content-Length: ' + xdr.responseText.length + '\r\nContent-Type: ' + xdr.contentType;
                        var status = {
                            code: 200,
                            message: 'success'
                        };
                        var responses = {
                            text: xdr.responseText
                        };

                        try {
                            if (userType === 'json') {
                                try {
                                    responses.json = JSON.parse(xdr.responseText);
                                } catch(e) {
                                    status.code = 500;
                                    status.message = 'parseerror';
                                    //throw 'Invalid JSON: ' + xdr.responseText;
                                }
                            } else if ((userType === 'xml') || ((userType !== 'text') && xmlRegEx.test(xdr.contentType))) {
                                var doc = new ActiveXObject('Microsoft.XMLDOM');
                                doc.async = false;
                                try {
                                    doc.loadXML(xdr.responseText);
                                } catch(e) {
                                    doc = undefined;
                                }
                                if (!doc || !doc.documentElement || doc.getElementsByTagName('parsererror').length) {
                                    status.code = 500;
                                    status.message = 'parseerror';
                                    throw 'Invalid XML: ' + xdr.responseText;
                                }
                                responses.xml = doc;
                            }
                        } catch(parseMessage) {
                            throw parseMessage;
                        } finally {
                            complete(status.code, status.message, responses, allResponseHeaders);
                        }
                    };
                    xdr.onerror = function(){
                        complete(500, 'error', {
                            text: xdr.responseText
                        });
                    };
                    xdr.open(options.type, options.url);
                    //xdr.send(userOptions.data);
                    xdr.send();
                },
                abort: function(){
                    if (xdr) {
                        xdr.abort();
                    }
                }
            };
        }
    });
}