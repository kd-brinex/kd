var my_tree_closed;

var nn6 = document.documentElement;
if(document.all){nn6 = false;}
var ie4 = (document.all && !document.getElementById);
var ie5 = (document.all && document.getElementById);

function my_tree_click(el, f){//f: 1 - open, 2 - close, false - default
	el.className=(f==1?'':(f==2?'close':(el.className?'':'close')));
	if(el.getElementsByTagName('UL')[0])
		el.getElementsByTagName('UL')[0].className=(f==1?'':(f==2?'close':(!el.className?'':'close')));
	if((ie4 || ie5) && window.event && window.event.srcElement.type!='checkbox'){
		window.event.cancelBubble=true;
		window.event.returnValue=false;
	}
	return false;
}

function my_tree_all(my_tree_id, f){//f: 1 - open, 2 - close
	if(f==2) my_tree_id.className='my_tree my_tree_close';
	for(i=0;i<my_tree_id.getElementsByTagName('LI').length;i++){
		var li=my_tree_id.getElementsByTagName('LI')[i];
		if(li.className!='leaf') my_tree_click(li, f)
	};
	my_tree_id.className='my_tree';
}

function my_tree_init(my_tree_id){
	my_tree_closed=(my_tree_id.className.indexOf('close')>-1)
	for(i=0;i<my_tree_id.getElementsByTagName('LI').length;i++){
		var li=my_tree_id.getElementsByTagName('LI')[i];
		if(ie4 || ie5) li.onclick=new Function("window.event.cancelBubble=true");
		if(!li.getElementsByTagName('UL').length || li.className=='leaf') li.className='leaf';
		else if((tmp=li.getElementsByTagName('A')[0]) && tmp.parentNode==li){
			li.getElementsByTagName('A')[0].onclick=new Function("my_tree_click(this.parentNode)");
			li.getElementsByTagName('A')[0].title='Раскрыть/Закрыть ветку';
			if(ie4 || ie5){
				li.style.cursor='hand';
				li.onclick=new Function("my_tree_click(this)");
			};
			if(my_tree_closed) li.getElementsByTagName('A')[0].onclick();
		}else{
			li.onclick=new Function("my_tree_click(this)");
			li.style.cursor='hand';
			if(my_tree_closed) li.onclick();
		}
	}
	my_tree_id.className='my_tree';
}
