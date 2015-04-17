/**
 *
 * Simple Set Clipboard System
 * Author: Joseph Huckaby
 *
 * Fixed by Troshkov Roman
 * email: roman@demka.org
 */

var ZeroClipboard = {
	
	version   : "1.0.4",             // extends by borodatych
	clients   : {},                  // registered upload clients on page, indexed by id
	moviePath : 'ZeroClipboard.swf', // URL to movie
	nextId    : 1,                   // ID of next movie
    title     : 'copy',              //Hint
	
	$: function( thingy ){ //simple DOM lookup utility function
        //console.log( thingy );
		if( typeof(thingy) == 'string' ) thingy = document.getElementById(thingy);
        //else $(thingy);
		if( !thingy.addClass ){ //extend element with a few useful methods
			thingy.hide        = function(){ this.style.display = 'none'; };
			thingy.show        = function(){ this.style.display = ''; };
			thingy.addClass    = function( name ){ this.removeClass(name); this.className += ' ' + name; };
			thingy.removeClass = function( name ){
				this.className = this.className.replace( new RegExp("\\s*" + name + "\\s*"), " ").replace(/^\s+/, '').replace(/\s+$/, '' );
			};
			thingy.hasClass = function(name ){
				return !!this.className.match( new RegExp("\\s*" + name + "\\s*") );
			}
		}
		return thingy;
	},

    setMoviePath : function( path ){ this.moviePath = path; }, //set path to ZeroClipboard.swf
    setTitle     : function( title ){ this.title = title; },   //set title for ebbed elements

	dispatch: function( id, eventName, args ){ //receive event from flash movie, send to client
		var client = this.clients[id];
        //alert(id);
        //alert(eventName);
        //alert(args);
        //console.log(client.id);
		if( client ){
            //alert(client.id);
			client.receiveEvent( eventName, args );
		}
	},
	register: function( id, client ){ //register new client to receive events
		this.clients[id] = client;
	},
	
	getDOMObjectPosition: function( obj ){
		var info = { //get absolute coordinates for dom element
			left  : obj.offsetLeft,
			top   : obj.offsetTop,
			width : obj.width ? obj.width : obj.offsetWidth,
			height: obj.height ? obj.height : obj.offsetHeight
		};
        return info;
        /**
         * Не знаю какого х@#, тут дальше пошли таким путем!?!?!
         * Достаточно на родители сделать position:relative
         * И гимора меньше и c динамичным DOM как то можно работать
        */
		while (obj ){
			info.left += obj.offsetLeft;
			info.top += obj.offsetTop;
			obj = obj.offsetParent;
		}
		return info;
	},
	
	Client: function( elem ){

		this.handlers = {};                              //constructor for new simple upload client
		this.id       = ZeroClipboard.nextId++;          //unique ID
		this.movieId  = 'ZeroClipboardMovie_' + this.id; //set ID
		ZeroClipboard.register(this.id, this);           //register client with singleton to receive flash events
		if( elem ) this.glue(elem);                      //create movie
	}
};

ZeroClipboard.Client.prototype = {
	
	id                : 0,     // unique ID for us
	ready             : false, // whether movie is ready to receive events or not
	movie             : null,  // reference to movie object
	clipText          : 'defaultText',    // text to copy to clipboard
	handCursorEnabled : true,  // whether to show hand cursor, or default pointer cursor
	cssEffects        : true,  // enable CSS mouse effects on dom container
	handlers          : null,  // user event handlers
	
	glue: function(elem ){
		// glue to DOM element
		// elem can be ID or actual DOM element object
		this.domElement = ZeroClipboard.$(elem);
		// float just above object, or zIndex 99 if dom element isn't set
		var zIndex = 99;
		if( this.domElement.style.zIndex ){
			zIndex = parseInt(this.domElement.style.zIndex) + 1;
		}
		// find X/Y position of domElement
		var box = ZeroClipboard.getDOMObjectPosition(this.domElement);

        //*/
		// create floating DIV above element
		this.div = document.createElement('div');
		var style = this.div.style;
		style.position = 'absolute';
		style.left     = '' + box.left + 'px';
		style.top      = '' + box.top + 'px';
		style.width    = '' + box.width + 'px';
		style.height   = '' + box.height + 'px';
		style.zIndex   = zIndex;
		// style.backgroundColor = '#f00'; // debug

        //var body = document.getElementsByTagName('body')[0];
		//body.appendChild(this.div);

        this.domElement.parentNode.appendChild(this.div);
		this.div.innerHTML = this.getHTML( box.width, box.height );
        //*/

        /*/
        this.div = this.getHTML( box.width, box.height );
        var style = this.div.style;
        style.position = 'absolute';
        style.left     = '' + box.left + 'px';
        style.top      = '' + box.top + 'px';
        style.width    = '' + box.width + 'px';
        style.height   = '' + box.height + 'px';
        style.zIndex   = zIndex;
        this.domElement.parentNode.appendChild(this.div);
        //*/

	},
	getHTML: function(width,height){ // return HTML for movie
		var html = '';
		var flashvars = 'id=' + this.id + '&width=' + width + '&height=' + height;
		if( navigator.userAgent.match(/MSIE/) ){ //IE gets an OBJECT tag
			//alert(navigator.userAgent);
			var protocol = location.href.match(/^https/i) ? 'https://' : 'http://';

            html += ''+
                '<object title="'+ZeroClipboard.title+'" ' +
                        //'type="application/x-shockwave-flash" ' +
                        'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'+
                        //'codebase="'+protocol+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" ' +
                        'id="'+this.movieId+'" width="'+width+'" height="'+height+'" ' +
                        //'type="application/x-shockwave-flash" '+
                        //'bgcolor="#555555" ' +
                        //'style = "border: 1px solid #000000"' +
                '>'+

                //'   <param name="allowScriptAccess" value="always" />' +
                '   <param name="movie" value="'+ZeroClipboard.moviePath+'"/>'+
                //'   <param name="movie" value="/ths/a2d/js/clippy/clippy.swf"/>' +
                //'   <param name="allowFullScreen" value="false" />' +
                //'   <param name="scale" value="noscale">'+
                //'   <param name="quality" value="best" />' +
                //'   <param name="bgcolor" value="#ffffff" />' +
                '   <param name="FlashVars" value="'+flashvars+'">'+
                '   <param name="wmode" value="transparent">'+
                '</object>'+
            '';

            /*/
            html += '' +
                '<object id="myId" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="780" height="420">' +
                    '<param name="movie" value="myContent.swf" />' +
                    '<!--[if !IE]>-->' +
                    '<object type="application/x-shockwave-flash" data="myContent.swf" width="780" height="420">' +
                    '<!--<![endif]-->' +
                    '<p>Alternative content</p>' +
                    ' <!--[if !IE]>-->' +
                    '</object>' +
                    '<!--<![endif]-->' +
                '</object>' +
            '';
            //*/

            /*/
			html += ''+
                '<object title="'+ZeroClipboard.title+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '+
                        'codebase="'+protocol+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" ' +
                        'width="'+width+'" height="'+height+'" id="'+this.movieId+'" align="middle"' +
                '>' +
                    '<param name="allowScriptAccess" value="always" />' +
                    '<param name="movie" value="'+ZeroClipboard.moviePath+'" />' +
                    '<param name="allowFullScreen" value="false" />' +
                    //'<param name="loop" value="false" />' +
                    //'<param name="menu" value="false" />' +
                    '<param name="quality" value="best" />' +
                    '<param name="bgcolor" value="#ffffff" />' +
                    '<param name="FlashVars" value="'+flashvars+'"/>' +
                    '<param name="wmode" value="transparent"/>' +
                '</object>' +
                //'<div>'+flashvars+'</div>'+
            '';
            //*/

            /*/
            var html = '' +
                    '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ' +
                            'width="110" height="14" id="clippy"' +
                    '>' +
                        '<param name="allowScriptAccess" value="always" />' +
                        '<param name="movie" value="/flash/clippy.swf"/>' +
                        '<param name="quality" value="high" />' +
                        '<param name="scale" value="noscale" />' +
                        '<param NAME="FlashVars" value="text=#{text}">' +
                        '<param name="bgcolor" value="#{bgcolor}">' +
                        '<embed src="/flash/clippy.swf"' +
                        'width="110" height="14" name="clippy" quality="high" ' +
                        'allowScriptAccess="always" ' +
                        'type="application/x-shockwave-flash" ' +
                        'pluginspage="http://www.macromedia.com/go/getflashplayer" ' +
                        'FlashVars="text=#{text}" ' +
                        'bgcolor="#{bgcolor}"' +
                        '/>' +
                    '</object>' +
            '';
            //*/
		}
		else{                                    //all other browsers get an EMBED tag
            //alert(navigator.userAgent);
			html += '' +
                '<embed title="'+ZeroClipboard.title+'" id="'+this.movieId+'" src="'+ZeroClipboard.moviePath+'" ' +
                    'loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+width+'" height="'+height+'" name="'+this.movieId+'" align="middle" ' +
                    'allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" ' +
                    'pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+flashvars+'" wmode="transparent"' +
                '/>';
		}
		return html;
	},
	hide: function(){    //temporarily hide floater offscreen
		if( this.div ) this.div.style.left = '-2000px';
	},
	show: function(){    //show ourselves after a call to hide()
		this.reposition();
	},
	destroy: function(){ //destroy control and floater
		if( this.domElement && this.div ){
			this.hide();
			this.div.innerHTML = '';
			var body = document.getElementsByTagName('body')[0];
			try{ body.removeChild( this.div ); }catch( e ){}
			this.domElement = null;
			this.div = null;
		}
	},
	reposition: function( elem ){
		// reposition our floating div, optionally to new container
		// warning: container CANNOT change size, only position
		if( elem ){
			this.domElement = ZeroClipboard.$(elem);
			if( !this.domElement) this.hide();
		}
		if( this.domElement && this.div ){
			var box = ZeroClipboard.getDOMObjectPosition(this.domElement);
			var style = this.div.style;
			style.left = '' + box.left + 'px';
			style.top = '' + box.top + 'px';
		}
	},
	setTxt: function( newText ){ // set text to be copied to clipboard
		this.clipText = newText;
		//console.log(this.clipText);
		//console.log(this.ready);
		//console.log(this.movie);
		if( this.ready ) this.movie.setText(newText);
	},
	addEventListener: function( eventName, func ){
		// add user event listener for event
		// event types: load, queueStart, fileStart, fileComplete, queueComplete, progress, error, cancel
		eventName = eventName.toString().toLowerCase().replace(/^on/, '');
        //alert(eventName);
		if( !this.handlers[eventName] ) this.handlers[eventName] = [];
		this.handlers[eventName].push(func);

	},
	setHandCursor: function( enabled ){ //enable hand cursor (true), or default arrow cursor (false)
		this.handCursorEnabled = enabled;
		if( this.ready) this.movie.setHandCursor(enabled);
	},
	setCSSEffects: function( enabled ){ //enable or disable CSS effects on DOM container
		this.cssEffects = !!enabled;
	},
	receiveEvent: function( eventName, args ){
        //console.log(eventName);
        //console.log(args);
        //alert(eventName);
		eventName = eventName.toString().toLowerCase().replace(/^on/, ''); // receive event from flash
        //alert(eventName);
		switch( eventName ){ // special behavior for certain events
			case 'load':
				// movie claims it is ready, but in IE this isn't always the case...
				// bug fix: Cannot extend EMBED DOM elements in Firefox, must use traditional function
				//alert(this.movieId);
				this.movie = document.getElementById(this.movieId);
				//alert(this.movie);
				//this.movie = $('#'+this.movieId)[0];
				if( !this.movie ){
					//alert('EmptyMovie');
					var self = this;
					setTimeout( function( ){ self.receiveEvent('load', null); }, 1 );
					return;
				}
				// firefox on pc needs a "kick" in order to set these in certain cases
				if( !this.ready && navigator.userAgent.match(/Firefox/) && navigator.userAgent.match(/Windows/) ){
					var self = this;
					setTimeout( function( ){ self.receiveEvent('load', null); }, 100 );
					this.ready = true;
					return;
				}
				//alert('ExistMove');
				this.ready = true;
				//alert(this.clipText);
				//console.log(this.clipText);

				//this.movie.setText( this.clipText );
				//this.movie.setHandCursor( this.handCursorEnabled );
				break;
			case 'mouseover':
				if( this.domElement && this.cssEffects ){
					this.domElement.addClass('hover');
					if( this.recoverActive) this.domElement.addClass('active');
				}
				break;
			case 'mouseout':
				if( this.domElement && this.cssEffects ){
					this.recoverActive = false;
					if( this.domElement.hasClass('active') ){
						this.domElement.removeClass('active');
						this.recoverActive = true;
					}
					this.domElement.removeClass('hover');
				}
				break;
			case 'mousedown':
				if( this.domElement && this.cssEffects ){
					this.domElement.addClass('active');
				}
				break;
			case 'mouseup':
				if( this.domElement && this.cssEffects ){
					this.domElement.removeClass('active');
					this.recoverActive = false;
				}
				break;
		} // switch eventName
		if( this.handlers[eventName] ){
			for( var idx = 0, len = this.handlers[eventName].length; idx < len; idx++ ){
				var func = this.handlers[eventName][idx];
				if( typeof(func) == 'function' ){                            //actual function reference
					func(this, args);
				}
				else if( (typeof(func) == 'object') && (func.length == 2) ){ //PHP style object + method, i.e. [myObject, 'myMethod']
					func[0][ func[1] ](this, args);
				}
				else if( typeof(func) == 'string' ){                         //name of function
					window[func](this, args);
				}
			} //foreach event handler defined
		} //user defined handler for event
	}
};