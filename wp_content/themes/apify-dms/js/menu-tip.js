KISSY.ready(function(S) {
	var S = KISSY, DOM = S.DOM, Event = S.Event;
	
	/**
	 *api_detail目录Hover动态TIP
	 *
	 **/
	var linkDiv = DOM.get('div.APIgory'), sublink = DOM.query('a.APIgoryItem', linkDiv), mask = DOM.query('s', linkDiv), menuTip = DOM.create('<div class="pop-tip hidden"></div>');
	document.body.appendChild(menuTip);
	if(!linkDiv){
		return;
	}
	mask && S.each(mask, function(item){
		Event.on(item, 'mouseover', function(e){
			var parent = DOM.prev(item, '.APIgory-content'), 
				text1 = DOM.text(DOM.get('a', parent)),
				text2 = DOM.text(DOM.get('p', parent));
			e.halt();
			var tar = e.target ;
		//	DOM.text(menuTip, text);
			menuTip.innerHTML = text1 + "<br/>" + text2;
			offset = DOM.offset(tar);
			DOM.css(menuTip, "left", offset.left+ DOM.width(tar)-70+"px");
			DOM.css(menuTip, "top", offset.top+44+"px");
			DOM.removeClass(menuTip, 'hidden');
		});
		Event.on(item, 'mouseout' , function(e) {
			DOM.addClass(menuTip, 'hidden');
		});
	});

	/**
	 *api_detail目录链接跳转
	 *
	 **/
	var APIgory = DOM.query('div.APIgory-list');
	if(!APIgory){ return ;}
	S.each(APIgory, function(el) {
		if(DOM.get('s', el)){
			var url = DOM.attr(DOM.get('a', el), 'href');
			Event.on(el, 'click', function(){
				window.location = url;
			});
		}
	});

	/**
	 * 跳转回顶部
	 *
	 **/
	var back = DOM.get("#J_Ruturn");
	Event.on(back,"click", function(){
		window.scrollTo(0, 0);
	});
	
});
