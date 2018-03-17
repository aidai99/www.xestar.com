var supporttouch = "ontouchend" in document;
!supporttouch && (window.location.href = 'forum.php?mobile=1');

var platform = navigator.platform;
var ua = navigator.userAgent;
var ios = /iPhone|iPad|iPod/.test(platform) && ua.indexOf( "AppleWebKit" ) > -1;
var andriod = ua.indexOf( "Android" ) > -1;
var lastaction='';


(function($, window, document, undefined) {
	var dataPropertyName = "virtualMouseBindings",
		touchTargetPropertyName = "virtualTouchID",
		virtualEventNames = "vmouseover vmousedown vmousemove vmouseup vclick vmouseout vmousecancel".split(" "),
		touchEventProps = "clientX clientY pageX pageY screenX screenY".split( " " ),
		mouseHookProps = $.event.mouseHooks ? $.event.mouseHooks.props : [],
		mouseEventProps = $.event.props.concat( mouseHookProps ),
		activeDocHandlers = {},
		resetTimerID = 0,
		startX = 0,
		startY = 0,
		didScroll = false,
		clickBlockList = [],
		blockMouseTriggers = false,
		blockTouchTriggers = false,
		eventCaptureSupported = "addEventListener" in document,
		$document = $(document),
		nextTouchID = 1,
		lastTouchID = 0, threshold;
	$.vmouse = {
		moveDistanceThreshold: 10,
		clickDistanceThreshold: 10,
		resetTimerDuration: 1500
	};
	function getNativeEvent(event) {
		while( event && typeof event.originalEvent !== "undefined" ) {
			event = event.originalEvent;
		}
		return event;
	}
	function createVirtualEvent(event, eventType) {
		var t = event.type, oe, props, ne, prop, ct, touch, i, j, len;
		event = $.Event(event);
		event.type = eventType;
		oe = event.originalEvent;
		props = $.event.props;
		if(t.search(/^(mouse|click)/) > -1 ) {
			props = mouseEventProps;
		}
		if(oe) {
			for(i = props.length, prop; i;) {
				prop = props[ --i ];
				event[ prop ] = oe[ prop ];
			}
		}
		if(t.search(/mouse(down|up)|click/) > -1 && !event.which) {
			event.which = 1;
		}
		if(t.search(/^touch/) !== -1) {
			ne = getNativeEvent(oe);
			t = ne.touches;
			ct = ne.changedTouches;
			touch = (t && t.length) ? t[0] : (( ct && ct.length) ? ct[0] : undefined);
			if(touch) {
				for(j = 0, len = touchEventProps.length; j < len; j++) {
					prop = touchEventProps[j];
					event[prop] = touch[prop];
				}
			}
		}
		return event;
	}
	function getVirtualBindingFlags(element) {
		var flags = {},
			b, k;
		while(element) {
			b = $.data(element, dataPropertyName);
			for(k in b) {
				if(b[k]) {
					flags[k] = flags.hasVirtualBinding = true;
				}
			}
			element = element.parentNode;
		}
		return flags;
	}
	function getClosestElementWithVirtualBinding(element, eventType) {
		var b;
		while(element) {
			b = $.data( element, dataPropertyName );
			if(b && (!eventType || b[eventType])) {
				return element;
			}
			element = element.parentNode;
		}
		return null;
	}
	function enableTouchBindings() {
		blockTouchTriggers = false;
	}
	function disableTouchBindings() {
		blockTouchTriggers = true;
	}
	function enableMouseBindings() {
		lastTouchID = 0;
		clickBlockList.length = 0;
		blockMouseTriggers = false;
		disableTouchBindings();
	}
	function disableMouseBindings() {
		enableTouchBindings();
	}
	function startResetTimer() {
		clearResetTimer();
		resetTimerID = setTimeout(function() {
			resetTimerID = 0;
			enableMouseBindings();
		}, $.vmouse.resetTimerDuration);
	}
	function clearResetTimer() {
		if(resetTimerID ) {
			clearTimeout(resetTimerID);
			resetTimerID = 0;
		}
	}
	function triggerVirtualEvent(eventType, event, flags) {
		var ve;
		if((flags && flags[eventType]) ||
					(!flags && getClosestElementWithVirtualBinding(event.target, eventType))) {
			ve = createVirtualEvent(event, eventType);
			$(event.target).trigger(ve);
		}
		return ve;
	}
	function mouseEventCallback(event) {
		var touchID = $.data(event.target, touchTargetPropertyName);
		if(!blockMouseTriggers && (!lastTouchID || lastTouchID !== touchID)) {
			var ve = triggerVirtualEvent("v" + event.type, event);
			if(ve) {
				if(ve.isDefaultPrevented()) {
					event.preventDefault();
				}
				if(ve.isPropagationStopped()) {
					event.stopPropagation();
				}
				if(ve.isImmediatePropagationStopped()) {
					event.stopImmediatePropagation();
				}
			}
		}
	}
	function handleTouchStart(event) {
		var touches = getNativeEvent(event).touches,
			target, flags;
		if(touches && touches.length === 1) {
			target = event.target;
			flags = getVirtualBindingFlags(target);
			if(flags.hasVirtualBinding) {
				lastTouchID = nextTouchID++;
				$.data(target, touchTargetPropertyName, lastTouchID);
				clearResetTimer();
				disableMouseBindings();
				didScroll = false;
				var t = getNativeEvent(event).touches[0];
				startX = t.pageX;
				startY = t.pageY;
				triggerVirtualEvent("vmouseover", event, flags);
				triggerVirtualEvent("vmousedown", event, flags);
			}
		}
	}
	function handleScroll(event) {
		if(blockTouchTriggers) {
			return;
		}
		if(!didScroll) {
			triggerVirtualEvent("vmousecancel", event, getVirtualBindingFlags(event.target));
		}
		didScroll = true;
		startResetTimer();
	}
	function handleTouchMove(event) {
		if(blockTouchTriggers) {
			return;
		}
		var t = getNativeEvent(event).touches[0],
			didCancel = didScroll,
			moveThreshold = $.vmouse.moveDistanceThreshold,
			flags = getVirtualBindingFlags(event.target);
			didScroll = didScroll ||
				(Math.abs(t.pageX - startX) > moveThreshold ||
					Math.abs(t.pageY - startY) > moveThreshold);
		if(didScroll && !didCancel) {
			triggerVirtualEvent("vmousecancel", event, flags);
		}
		triggerVirtualEvent("vmousemove", event, flags);
		startResetTimer();
	}
	function handleTouchEnd(event) {
		if(blockTouchTriggers) {
			return;
		}
		disableTouchBindings();
		var flags = getVirtualBindingFlags(event.target), t;
		triggerVirtualEvent("vmouseup", event, flags);
		if(!didScroll) {
			var ve = triggerVirtualEvent("vclick", event, flags);
			if(ve && ve.isDefaultPrevented()) {
				t = getNativeEvent(event).changedTouches[0];
				clickBlockList.push({
					touchID: lastTouchID,
					x: t.clientX,
					y: t.clientY
				});
				blockMouseTriggers = true;
			}
		}
		triggerVirtualEvent("vmouseout", event, flags);
		didScroll = false;
		startResetTimer();
	}
	function hasVirtualBindings(ele) {
		var bindings = $.data( ele, dataPropertyName ), k;
		if(bindings) {
			for(k in bindings) {
				if(bindings[k]) {
					return true;
				}
			}
		}
		return false;
	}
	function dummyMouseHandler() {}

	function getSpecialEventObject(eventType) {
		var realType = eventType.substr(1);
		return {
			setup: function(data, namespace) {
				if(!hasVirtualBindings(this)) {
					$.data(this, dataPropertyName, {});
				}
				var bindings = $.data(this, dataPropertyName);
				bindings[eventType] = true;
				activeDocHandlers[eventType] = (activeDocHandlers[eventType] || 0) + 1;
				if(activeDocHandlers[eventType] === 1) {
					$document.bind(realType, mouseEventCallback);
				}
				$(this).bind(realType, dummyMouseHandler);
				if(eventCaptureSupported) {
					activeDocHandlers["touchstart"] = (activeDocHandlers["touchstart"] || 0) + 1;
					if(activeDocHandlers["touchstart"] === 1) {
						$document.bind("touchstart", handleTouchStart)
							.bind("touchend", handleTouchEnd)
							.bind("touchmove", handleTouchMove)
							.bind("scroll", handleScroll);
					}
				}
			},
			teardown: function(data, namespace) {
				--activeDocHandlers[eventType];
				if(!activeDocHandlers[eventType]) {
					$document.unbind(realType, mouseEventCallback);
				}
				if(eventCaptureSupported) {
					--activeDocHandlers["touchstart"];
					if(!activeDocHandlers["touchstart"]) {
						$document.unbind("touchstart", handleTouchStart)
							.unbind("touchmove", handleTouchMove)
							.unbind("touchend", handleTouchEnd)
							.unbind("scroll", handleScroll);
					}
				}
				var $this = $(this),
					bindings = $.data(this, dataPropertyName);
				if(bindings) {
					bindings[eventType] = false;
				}
				$this.unbind(realType, dummyMouseHandler);
				if(!hasVirtualBindings(this)) {
					$this.removeData(dataPropertyName);
				}
			}
		};
	}
	for(var i = 0; i < virtualEventNames.length; i++) {
		$.event.special[virtualEventNames[i]] = getSpecialEventObject(virtualEventNames[i]);
	}
	if(eventCaptureSupported) {
		document.addEventListener("click", function(e) {
			var cnt = clickBlockList.length,
				target = e.target,
				x, y, ele, i, o, touchID;
			if(cnt) {
				x = e.clientX;
				y = e.clientY;
				threshold = $.vmouse.clickDistanceThreshold;
				ele = target;
				while(ele) {
					for(i = 0; i < cnt; i++) {
						o = clickBlockList[i];
						touchID = 0;
						if((ele === target && Math.abs(o.x - x) < threshold && Math.abs(o.y - y) < threshold) ||
									$.data(ele, touchTargetPropertyName) === o.touchID) {
							e.preventDefault();
							e.stopPropagation();
							return;
						}
					}
					ele = ele.parentNode;
				}
			}
		}, true);
	}
})(jQuery, window, document);

(function($, window, undefined) {
	function triggercustomevent(obj, eventtype, event) {
		var origtype = event.type;
		event.type = eventtype;
		$.event.handle.call(obj, event);
		event.type = origtype;
	}

	$.event.special.tap = {
		setup : function() {
			var thisobj = this;
			var obj = $(thisobj);
			obj.on('vmousedown', function(e) {
				if(e.which && e.which !== 1) {
					return false;
				}
				var origtarget = e.target;
				var origevent = e.originalEvent;
				var timer;

				function cleartaptimer() {
					clearTimeout(timer);
				}
				function cleartaphandlers() {
					cleartaptimer();
					obj.off('vclick', clickhandler)
						.off('vmouseup', cleartaptimer);
					$(document).off('vmousecancel', cleartaphandlers);
				}

				function clickhandler(e) {
					cleartaphandlers();
					if(origtarget === e.target) {
						triggercustomevent(thisobj, 'tap', e);
					}
					return false;
				}

				obj.on('vmouseup', cleartaptimer)
					.on('vclick', clickhandler)
				$(document).on('touchcancel', cleartaphandlers);

				timer = setTimeout(function() {
					triggercustomevent(thisobj, 'taphold', $.Event('taphold', {target:origtarget}));
				}, 750);
				return false;
			});
		}
	};
	$.each(('tap').split(' '), function(index, name) {
		$.fn[name] = function(fn) {
			return this.on(name, fn);
		};
	});

})(jQuery, this);


var atap = {
	init : function() {
		$('.atap').on('tap', function() {
			var obj = $(this);
			obj.css({'background':'#6FACD5', 'color':'#FFFFFF', 'font-weight':'bold', 'text-decoration':'none', 'text-shadow':'0 1px 1px #3373A5'});
			return false;
		});
		$('.atap a').off('click');
	}
};


var POPMENU = new Object;
var popup = {
	init : function() {
		var $this = this;
		$('.popup').each(function(index, obj) {
			obj = $(obj);
			var pop = $(obj.attr('href'));
			if(pop && pop.attr('popup')) {
				pop.css({'display':'none'});
				obj.on('click', function(e) {
					$this.open(pop,'html');
				});
			}
		});
		this.maskinit();
	},
	maskinit : function() {
		var $this = this;
		$('#dark').off().on('click', function() {$this.close();});
		$('#light').off().on('click', function() {$this.close();});
	},

	open : function(pop, type, url) {
		this.close();
		this.maskinit();
		if(typeof pop == 'string') {
			$('#ntcmsg').remove();
			if(type == 'alert') {
				pop = '<div class="tip"><dt class="tc">'+ pop +'</dt><dd class="flex_box bo_t"><a href="javascript:;" class="cc flex" onclick="popup.close();">确定</a></dd></div>';
				var popwidth=window.innerWidth*0.8;
			} else if(type == 'confirm') {
				pop = '<div class="tip"><dt class="tc">'+ pop +'</dt><dd class="flex_box bo_t"><a class="cc flex bo_r" href="'+ url +'">确定</a><a href="javascript:;" class="flex" onclick="popup.close();">取消</a></dd></div>'
				var popwidth=window.innerWidth*0.8;
			} else if(type == 'loading') {
				
			}
			$('body').append('<div id="ntcmsg" style="display:none;">'+ pop +'</div>');
			pop = $('#ntcmsg');
		}
		if(type=='html'){
		  var popwidth=window.innerWidth*0.8;
		}
		if(!popwidth){
			var popwidth=pop.width();
		}
		/*
		if(POPMENU[pop.attr('id')]) {
			$('#' + pop.attr('id') + '_popmenu').html(pop.html()).css({'height':pop.height()+'px', 'width':popwidth+'px'});
		} else {
			pop.parent().append('<div class="dialogbox" id="'+ pop.attr('id') +'_popmenu" style="height:'+ pop.height() +'px;width:'+ popwidth +'px;">'+ pop.html() +'</div>');
		}
		*/
		pop.parent().append('<div class="dialogbox" id="'+ pop.attr('id') +'_popmenu" style="height:'+ pop.height() +'px;width:'+ popwidth +'px;">'+ pop.html() +'</div>');
		var popupobj = $('#' + pop.attr('id') + '_popmenu');
		var left = (window.innerWidth - popwidth) / 2;
		var top = (document.documentElement.clientHeight - popupobj.height()) / 2;
		popupobj.css({'display':'block','position':'fixed','left':left,'top':top,'z-index':1010,'opacity':1});
		if(type == 'loading'){
			$('#loading').css('display', 'block');
		}else{
			$('#dark').css('display', 'block');
			$('#light').css({'display':'block','height':'50px'});			
		}
		POPMENU[pop.attr('id')] = pop;
	},
	close : function() {
		$('#loading').css('display', 'none');
		$('#dark').css('display', 'none');
		$('#light').css({'display':'none','height':''});
		$.each(POPMENU, function(index, obj) {
			$('#' + index + '_popmenu').remove();
			$('#ntcmsg').remove();
		});
	}
};

var dialog = {
	init : function() {
		$(document).on('click', '.dialog', function() {
			var obj = $(this);
			loading();
			$.ajax({
				type : 'GET',
				url : obj.attr('href') + '&inajax=1',
				dataType : 'xml'
			})

			.success(function(s) {
				popup.open(s.lastChild.firstChild.nodeValue,'html');
				evalscript(s.lastChild.firstChild.nodeValue);
			})
			.error(function() {
				window.location.href = obj.attr('href');
				popup.close();
			});
			return false;
		});
	},

};



var formdialog = {
	init : function() {
		$(document).on('click', '.formdialog', function() {
			loading();
			var obj = $(this);
			var formobj = $(this.form);

			$.ajax({
				type:'POST',
				url:formobj.attr('action') + '&handlekey='+ formobj.attr('id') +'&inajax=1',
				data:formobj.serialize(),
				dataType:'xml'
			})
			.success(function(s) {
				if(s.lastChild.firstChild.nodeValue.indexOf('<div class="tip">')!=-1){
					popup.open(s.lastChild.firstChild.nodeValue,'html');
					evalscript(s.lastChild.firstChild.nodeValue);								
				}else{
		      popup.open('操作成功', 'alert');
				}
			})
			.error(function() {
				window.location.href = obj.attr('href');
				popup.close();
			});
			return false;
		});
	}
};

var redirect = {
	init : function() {
		$(document).on('click', '.redirect', function() {
			var obj = $(this);
			popup.close();
			window.location.href = obj.attr('href');
		});
	}
};

var DISMENU = new Object;
var display = {
	init : function() {
		var $this = this;
		$('.display').each(function(index, obj) {
			obj = $(obj);
			var dis = $(obj.attr('href'));
			if(dis && dis.attr('display')) {
				dis.css({'display':'none'});
				dis.css({'z-index':'102'});
				DISMENU[dis.attr('id')] = dis;
				obj.on('click', function(e) {
					if(in_array(e.target.tagName, ['A', 'IMG', 'INPUT'])) return;
					$this.maskinit();
					if(dis.attr('display') == 'true') {
						dis.css('display', 'block');
						dis.attr('display', 'false');
						$('#dark').css('display', 'block');
					}
					return false;
				});
			}
		});
	},
	maskinit : function() {
		var $this = this;
		$('#dark').off().on('touchstart', function() {
			$this.hide();
		});
	},
	hide : function() {
		$('#dark').css('display', 'none');
		$.each(DISMENU, function(index, obj) {
			obj.css('display', 'none');
			obj.attr('display', 'true');
		});
	}
};

var geo = {
	latitude : null,
	longitude : null,
	loc : null,
	errmsg : null,
	timeout : 5000,
	getcurrentposition : function() {
		if(!!navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(this.locationsuccess, this.locationerror, {
				enableHighAcuracy : true,
				timeout : this.timeout,
				maximumAge : 3000
			});
		}
	},
	locationerror : function(error) {
		geo.errmsg = 'error';
		switch(error.code) {
			case error.TIMEOUT:
				geo.errmsg = "获取位置超时，请重试";
				break;
			case error.POSITION_UNAVAILABLE:
				geo.errmsg = '无法检测到您的当前位置';
			    break;
		    case error.PERMISSION_DENIED:
		        geo.errmsg = '请允许能够正常访问您的当前位置';
		        break;
		    case error.UNKNOWN_ERROR:
		        geo.errmsg = '发生未知错误';
		        break;
		}
	},
	locationsuccess : function(position) {
		geo.latitude = position.coords.latitude;
		geo.longitude = position.coords.longitude;
		geo.errmsg = '';
		$.ajax({
			type:'POST',
			url:'http://maps.google.com/maps/api/geocode/json?latlng=' + geo.latitude + ',' + geo.longitude + '&language=zh-CN&sensor=true',
			dataType:'json'
		})
		.success(function(s) {
			if(s.status == 'OK') {
				geo.loc = s.results[0].formatted_address;
			}
		})
		.error(function() {
			geo.loc = null;
		});
	}
};

var pullrefresh = {
	init : function() {
		var pos = {};
		var status = false;
		var divobj = null;
		var contentobj = null;
		var reloadflag = false;
		$('body').on('touchstart', function(e) {
			e = mygetnativeevent(e);
			pos.startx = e.touches[0].pageX;
			pos.starty = e.touches[0].pageY;
		})
		.on('touchmove', function(e) {
			e = mygetnativeevent(e);
			pos.curposx = e.touches[0].pageX;
			pos.curposy = e.touches[0].pageY;
			if(pos.curposy - pos.starty < 0 && !status) {
				return;
			}
			if(!status && $('.body_main').scrollTop() <= 0) {
				status = true;
				divobj = document.createElement('div');
				divobj = $(divobj);
				divobj.css({'position':'relative', 'margin-left':'-85px'});
				$('body').prepend(divobj);
				contentobj = document.createElement('div');
				contentobj = $(contentobj);
				contentobj.css({'position':'absolute', 'height':'30px', 'top': '-30px', 'left':'50%'});
				contentobj.html('<img src="source/plugin/cis_weixin/core/ui/arrow.png" style="vertical-align:middle; width:16px; margin-right:5px;-moz-transform:rotate(180deg);-webkit-transform:rotate(180deg);-o-transform:rotate(180deg);transform:rotate(180deg);"><span id="refreshtxt">下拉可以刷新</span>');
				contentobj.find('img').css({'-webkit-transition':'all 0.5s ease-in-out'});
				divobj.prepend(contentobj);
				pos.topx = pos.curposx;
				pos.topy = pos.curposy;
			}
			if(!status) {
				return;
			}
			if(status == true) {
				var pullheight = pos.curposy - pos.topy;
				if(pullheight >= 0 && pullheight < 150) {
					divobj.css({'height': pullheight/2 + 'px'});
					contentobj.css({'top': (-30 + pullheight/2) + 'px'});
					if(reloadflag) {
						contentobj.find('img').css({'-webkit-transform':'rotate(180deg)', '-moz-transform':'rotate(180deg)', '-o-transform':'rotate(180deg)', 'transform':'rotate(180deg)'});
						contentobj.find('#refreshtxt').html('下拉可以刷新');
					}
					reloadflag = false;
				} else if(pullheight >= 150) {
					divobj.css({'height':pullheight/2 + 'px'});
					contentobj.css({'top': (-10 + pullheight/2) + 'px'});
					if(!reloadflag) {
						contentobj.find('img').css({'-webkit-transform':'rotate(360deg)', '-moz-transform':'rotate(360deg)', '-o-transform':'rotate(360deg)', 'transform':'rotate(360deg)'});
						contentobj.find('#refreshtxt').html('松开可以刷新');
					}
					reloadflag = true;
				}
			}
			e.preventDefault();
		})
		.on('touchend', function(e) {
			if(status == true) {
				if(reloadflag) {
					contentobj.html('<img src="source/plugin/cis_weixin/core/ui/icon_load.gif" style="vertical-align:middle;margin-right:5px;">正在加载...');
					contentobj.animate({'top': (-30 + 75) + 'px'}, 618, 'linear');
					divobj.animate({'height': '75px'}, 618, 'linear', function() {
						window.location.reload();
					});
					return;
				}
			}
			divobj.remove();
			divobj = null;
			status = false;
			pos = {};
		});
	}
};

function mygetnativeevent(event) {
	while(event && typeof event.originalEvent !== "undefined") {
		event = event.originalEvent;
	}
	return event;
}

function evalscript(s,type) {
	if(s.indexOf('<script') == -1) return s;
	var p = /<script[^\>]*?>([^\x00]*?)<\/script>/ig;
	var arr = [];
	while(arr = p.exec(s)) {
    
		var p1 = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:id=\"([\w\-]+?)\")?><\/script>/i;
		var arr1 = [];
		arr1 = p1.exec(arr[0]);
		if(arr1) {
			reload = arr1[2] ? true : false;
			appendscript(arr1[1], '', reload, type, arr1[3]);
		} else {
			p1 = /<script[^\>]*?id=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?>([^\x00]+?)<\/script>/i;
			arr1 = p1.exec(arr[0]);
			if(arr1){
				var reload = arr1[2] ? true : false;
				appendscript('', arr1[3], reload,type,arr1[1]);				
			}else{
			  p1 = /<script(.*?)>([^\x00]+?)<\/script>/i;
			  arr1 = p1.exec(arr[0]);
			  appendscript('', arr1[2], arr1[1].indexOf('reload=') != -1);
			}
		}
	}
	return s;
}

var safescripts = {}, evalscripts = [];
function appendscript(src, text, reload, type, id) {
	var JSLOADED = [];
  if(!id){
		var id = hash(src + text);
	}
	if(!reload && in_array(id, evalscripts)) return;
	if(reload && $('#' + id)[0]) {
		$('#' + id)[0].parentNode.removeChild($('#' + id)[0]);
	}else{
		evalscripts.push(id);
	}

	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
  scriptNode.id = id;
	scriptNode.charset = !document.charset ? document.characterSet : document.charset;
	
	try {
		if(src) {
			scriptNode.src = src;
			scriptNode.onloadDone = false;
			scriptNode.onload = function () {
				scriptNode.onloadDone = true;
				JSLOADED[src] = 1;
			};
			scriptNode.onreadystatechange = function () {
				if((scriptNode.readyState == 'loaded' || scriptNode.readyState == 'complete') && !scriptNode.onloadDone) {
					scriptNode.onloadDone = true;
					JSLOADED[src] = 1;
				}
			};
		} else if(text){
			scriptNode.text = text;
		}
		document.getElementById('modscript').appendChild(scriptNode);
	} catch(e) {}
}

function hash(string, length) {
	var length = length ? length : 32;
	var start = 0;
	var i = 0;
	var result = '';
	filllen = length - string.length % length;
	for(i = 0; i < filllen; i++){
		string += "0";
	}
	while(start < string.length) {
		result = stringxor(result, string.substr(start, length));
		start += length;
	}
	return result;
}

function stringxor(s1, s2) {
	var s = '';
	var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max = Math.max(s1.length, s2.length);
	for(var i=0; i<max; i++) {
		var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s += hash.charAt(k % 52);
	}
	return s;
}

function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
					return true;
			}
		}
	}
	return false;
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}


function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	if(cookieValue == '' || seconds < 0) {
		cookieValue = '';
		seconds = -2592000;
	}
	if(seconds) {
		var expires = new Date();
		expires.setTime(expires.getTime() + seconds * 1000);
	}
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
	name = cookiepre + name;
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	if(cookie_start == -1) {
		return '';
	} else {
		var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
		return !nounescape ? unescape(v) : v;
	}
}

$(document).ready(function() {

	if($('.popup').length > 0) {
		popup.init();
	}
	if($('.display').length > 0) {
		display.init();
	}
	if($('.atap').length > 0) {
		atap.init();
	}
	if($('.pullrefresh').length > 0) {
		pullrefresh.init();
	}

	dialog.init();
	formdialog.init();
	redirect.init();
});

/*--------------------------------------------------
showpage

--------------------------------------------------*/

$(document).on('click', '.showpage', function() {
	loading();
	$('html').removeClass();
	var obj = $(this);
	var type = obj.attr('type');
	var id = obj.attr('id');
  var url=obj.attr('href') + '&spa=1';
	if(type=='getpage'){
		var url=obj.attr('href') + '&getpage=1';
		var dataType='html';
	}else{
		var url=obj.attr('href') + '&inajax=1';
		var dataType='xml';
	}
	if(url.indexOf('mobile=2')<0){
		var url= url + '&mobile=2';
	}
	
	$.ajax({
		type : 'GET',
		url : url,
		dataType : dataType
	})
	
	.success(function(s) {
		if(!s){
			popup.open('目标网页存在错误','alert');
			return false;
		}
		if(type=='getpage'){
			var html=s;
		}else{
			var html=s.lastChild.firstChild.nodeValue;
		}
		
		if(html.indexOf('<div class="tip">')!=-1){
			setaction("closepop()");
			//var main = $(html).find('.tip').html();
			
			popup.open(html,'html');
			evalscript(html);
		}else{
			setaction("closepage('#"+id+"_area')");
			var main = $(html).find('.wrap').html();
			$('body').append('<div id="'+id+'_area" class="showpagearea showpage_show"><div class="boxarea"><div class="wrap">'+ main +'</div></div></div>');
			evalscript(html);
			$(document).on('click', '.showpagearea a.go', function() {
				closepage('#'+id+'_area');
			});			
		}
	})
	.error(function() {
		//popup.close();
		window.location.href = obj.attr('href');
	});
	return false;
});


/*--------------------add--------------------*/

$(document).on('click', '.scrolltop', function() {
	$('.body_main').scrollTop('0', '1');
});
		
$(document).on('input propertychange focus', '.autoheight', function() {
	this.style.height=this.scrollHeight + 'px';
})
$(document).on('click', '#autopbn', function() {
	var btn=$(this);
	var obj={};
	obj.id=btn.attr('tab');
	autopage(obj,'click');
	return false;
});

$(document).on('focus', '.imui_search_input', function() {
	$('.imui_search_text').hide();
});
$(document).on('blur', '.imui_search_input', function() {
	$('.imui_search_text').show();
});


function selectquestion(value){
	if(value){
		$('#answer').css('display', 'block');
	}else{
		$('#answer').css('display', 'none');
	}
}
		
function goto(url,tabid,his){
	loading();

	if(url.indexOf('mobile=2')<0){
		url= url + '&mobile=2';
	}
	if($('#'+tabid).length){
		var gettype= 'update';
	}else{
		var gettype= 'create';
	}
	var value = {
		url: url,
		type: gettype,
		tabid: tabid,
		his: his,
		html: ''
	};
	if($.isFunction(window.loadtab)){
		loadtab('ajax',value);
	}else{
		window.location.href = url;
	}
	return false;
}

//getnextpage
var nextstar=2;
function getnextpage(url,id){
	$.ajax({
		type : 'GET',
		url : url+'&page='+nextstar,
		dataType : 'xml'
	})
	.success(function(s){
		if(s.lastChild.firstChild.nodeValue){
			$('#'+id).append(s.lastChild.firstChild.nodeValue);
			nextstar++;			
		}else{
			$('#ajaxnext').css('display','none');
		}
	})
	.error(function() {
		return false;
	});
}

/*formsubmit*/
function formsubmit(id,url,tab){
	var formobj = $('#'+id+'_form');
	loading();
	$.ajax({
		type:'POST',
		url:formobj.attr('action') + '&inajax=1&check=1',
		data:formobj.serialize(),
		dataType:'xml'
	})
	.success(function(s) {
		if(s.lastChild.firstChild.nodeValue!='ok'){
			popup.open(s.lastChild.firstChild.nodeValue,'html');
		}else{
			if(url && tab){
				goto(url,tab,'no');
			}else{
				popup.open('操作成功', 'alert');
			}
		}
	})
	.error(function() {
		popup.open('Ajax请求失败','alert');
	});
	closepage('#'+id+'_area');
	return false;
}
					
/*apple
if(("standalone" in window.navigator) && window.navigator.standalone){
  var noddy, remotes = false;
  document.addEventListener('click', function(event) {
		noddy = event.target;
		while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
			noddy = noddy.parentNode;
		}
		if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)){
			if($(noddy).attr('class').indexOf('dialog') !== -1 && $(noddy).attr('class').indexOf('showpage') !== -1 && $(noddy).attr('class').indexOf('gettab') !== -1 && $(noddy).attr('class').indexOf('getpage') !== -1 && $(noddy).attr('class').indexOf('redirect') !== -1){
				event.preventDefault();
				document.location.href = noddy.href;						
			}
		}
  },false);
}
*/

/*settime*/
function settime(value,id){
	if(value){
		var val=value.replace("T"," ");
		$('#'+id).val(val); 
	}
}

//showbox
function showbox(id,type){
  if($('#'+id).css('display')=='none'){
    $('#'+id).fadeIn(300);
		if(type=='wrap'){
			$('#light').css({'display':'block','height':'100%'});
			$('#light').off().on('click', function() {showbox(id);});
		}else{
			$('#light').css({'display':'block','height':'50px'});
			$('#dark').css('display', 'block');
			
			$('#light').off().on('click', function() {showbox(id);});
			$('#dark').off().on('click', function() {showbox(id);});			
		}
		
	}else{
		$('#'+id).fadeOut(300);
		if(type=='wrap'){
			$('#light').css({'display':'none','height':''});
		}else{
		  $('#light').css({'display':'none','height':''});
		  $('#dark').css('display', 'none');			
		}
	}
}

function showmore(id){
	if($('#'+id).css('display')=='none'){
		$('#'+id).fadeIn(300);
	}else{
		$('#'+id).fadeOut(300);
	}
}
/*side*/
function openside(){
	if($('html').hasClass("openside")){
		$('html').removeClass('openside').addClass('closeside');
	}else{
		$('html').removeClass('closeside').addClass('openside');
	}
}
/*sheet*/
function opensheet(id){
	if($(id).hasClass("showsheet")){
		$(id).removeClass('showsheet').addClass('hidesheet');
		$('#light').css({'display':'none','height':''});
		$('#dark').css('display', 'none');
	}else{
		$(id).removeClass('hidesheet').addClass('showsheet');
		$(id).css('display', 'block');
		$('#light').css({'display':'block','height':'50px'});
		$('#dark').css('display', 'block');
		
		$('#light').off().on('click', function() {opensheet(id);});
		$('#dark').off().on('click', function() {opensheet(id);});

		$(document).on('click', '.imui_sheet a.bo_bl', function() {
			$(id).removeClass('showsheet').addClass('hidesheet');
			$('#light').css({'display':'none','height':''});
			$('#dark').css('display', 'none');
		});
	}
}

			
/*closepage*/
function closepage(id){
	$(id).remove();
	popup.close();
}

function setaction(fun){
	lastaction=fun+";";
}

function unsetaction(){
	lastaction="";
}

/*preview*/
function preview(id){
	var file=$('#'+id).val();
	var arr=file.split('\\');
	var name=arr[arr.length-1];
	$('#'+id+'_name').html(name);
}
						
function show(id){
	if($('#'+id).css('display')=='none'){
		$('#'+id).css('display', '');
	}else{
		$('#'+id).css('display', 'none');
	}
}
	
//addsmile
function addsmile(smile,id){
  if(id){
		var old=$('#'+id+'_area #postmessage')[0].value;
		$('#'+id+'_area #postmessage').val(old+smile);		
	}else{
		var old=$(".body_main #postmessage")[0].value;
		$(".body_main #postmessage").val(old+smile);		
	}

}

//aituser
function aituser(id){
	if(id){
		var ait=$('#'+id+'_area #aitarea')[0].value;
		var old=$('#'+id+'_area #postmessage')[0].value;
		if(ait){
			$('#'+id+'_area #postmessage').val(old+' @'+ait+' ');
			$('#'+id+'_area #aitarea').val('');
		}
	}else{
		var ait=$("#aitarea")[0].value;
		var old=$("#postmessage")[0].value;
		if(ait){
			$("#postmessage").val(old+' @'+ait+' ');
			$("#aitarea").val('');
		}
	}
}

//addmusic
function addmusic(id){
	if(id){
		var music=$('#'+id+'_area #musicarea')[0].value;
		var old=$('#'+id+'_area #postmessage')[0].value;
		if(music){
			$('#'+id+'_area #postmessage').val(old+'[audio]'+music+'[/audio]');
			$('#'+id+'_area #musicarea').val('');
		}
	}else{
		var music=$("#musicarea")[0].value;
		var old=$("#postmessage")[0].value;
		if(music){
			$("#postmessage").val(old+'[audio]'+music+'[/audio]');
			$("#musicarea").val('');
		}		
	}

}

//addvideo
function addvideo(id){
	if(id){
		var video=$('#'+id+'_area #videoarea')[0].value;
		var old=$('#'+id+'_area #postmessage')[0].value;
		if(video){
			$('#'+id+'_area #postmessage').val(old+'[media=mp3,500,375]'+video+'[/media]');
			$('#'+id+'_area #videoarea').val('');
		}
	}else{
		var video=$("#videoarea")[0].value;
		var old=$("#postmessage")[0].value;
		if(video){
			$("#postmessage").val(old+'[media=mp3,500,375]'+video+'[/media]');
			$("#videoarea").val('');
		}		
	}
}
				
// showhiden
function showhiden(id,area){
	
	if(!area){
		var mainarea='.body_main';
	}else{
		var mainarea='#'+area+'_area';
	}
	if($(mainarea+' #'+id+'_area').css('display')=='none'){
		$(mainarea+' #hidenarea').children().css('display', 'none');
		$(mainarea+' #'+id+'_area').fadeIn(300);
	}else{
		$(mainarea+' #'+id+'_area').fadeOut(300);
	}
}

/*linkpage*/
function linkage(value,id,level,content){
	if(level=='second'){
		var nextlevel='third';
		var uplevel='first';
	}else if(level=='third'){
		var nextlevel='null';
		var uplevel='second';
	}
	if(level!='null'){
		var v=value.replace('.', '_');
		var lis = $('#dom_' + id +'_'+ level + '_' + v +' li');
		if(lis.length>0){
			var s='<div class="imui_blocks b_f imui_blocks_radio size_16">';
			for(i = 0;i < lis.length;i++) {
				var optionid = lis[i].getAttribute('optionid');
				s += '<label class="imui_block imui_check_label" for="laber_'+id+'_'+optionid+'_'+level+'"><div class="imui_block_bd flex"><p>'+ lis[i].innerHTML +'</p></div><div class="imui_block_ft"><input type="radio" class="imui_check" name="false_'+id+'_'+level+'" id="laber_'+id+'_'+optionid+'_'+level+'" value="'+optionid+'" onclick="linkage(this.value,\''+id+'\',\''+nextlevel+'\',\''+lis[i].innerHTML+'\')"/><span class="imui_icon_checked"></span></div></label>';
			}
			s += '</div>';
			$('#select_'+id+'_'+level+'_area').html(s);	
			$('#select_'+id+'_'+level+'_area').css('display', 'block');
			$('#select_'+id+'_'+uplevel+'_area').css('display', 'none');			
		}else{
			calllinkpage(id);
		}
	}else{
		calllinkpage(id);
	}
	if($('#'+id+'_back').css('display')=='none'){
		$('#'+id+'_back').css('display', 'block');
	}
	if(level=='second'){
		$('#'+id+'_var_1').html(content);
		$('#'+id+'_var_2').html('');
		$('#'+id+'_var_3').html('');
	}else if(level=='third'){
		$('#'+id+'_var_2').html(' - '+content);
		$('#'+id+'_var_3').html('');
	}else{
		$('#'+id+'_var_3').html(' - '+content);
	}
	
	$('#'+id+'_var_title').html('');	
  $('#typeoption_'+id).val(value); 
	$('#alert_'+id).removeClass('cc');
}

function calllinkpage(id){
	
	if($('#select_'+id+'_area').length){
		unsetaction();
		var main = $('#select_'+id+'_area').html();
		$('#select_'+id+'_area').remove();
		$('#select_'+id).html(main);
	}else{
		setaction("calllinkpage('"+id+"')");
		var main = $('#select_'+id).html();
		$('#select_'+id).empty();
		$('#windowarea').append('<div id="select_'+id+'_area" class="showpagearea showpage_in">'+ main +'</div>');
	}
}

function backlinkpage(id){
	
	if($('#select_'+id+'_third_area').html()!='' && $('#select_'+id+'_third_area').css('display')!='none'){
		$('#select_'+id+'_third_area').css('display', 'none');
		$('#select_'+id+'_second_area').css('display', 'block');
	}else{
		if($('#select_'+id+'_second_area').html()!='' && $('#select_'+id+'_second_area').css('display')!='none'){
		  $('#select_'+id+'_second_area').css('display', 'none');
		  $('#select_'+id+'_first_area').css('display', 'block');
		}
	}
	if($('#select_'+id+'_second_area').css('display')=='none' && $('#select_'+id+'_third_area').css('display')=='none'){
		$('#'+id+'_back').css('display', 'none');
	}
}

//shar
function shar(config){
  if($('#shar_box').length){
	 unsetaction();
	 $('#light').css({'display':'none','height':''});
	 $('#dark').css('display', 'none');
	 $('#shar_box').remove();
	}else{
		setaction("shar()");
		$('#light').css({'display':'block','height':'50px'});
		$('#dark').css('display', 'block');
		$('#light').off().on('click', function() {shar();});
		$('#dark').off().on('click', function() {shar();});		 
		if(BRO=='weixin'){
			var ins='<img src="source/plugin/cis_weixin/core/ui/shar_wx.png"/>';
			var classname='wx';
		}else if(BRO=='qq' || BRO=='uc'){
		  var classname='llq';
		  var ins ='<div id="immwashar"></div>';
		}else{
		  var classname='llq';
		  var ins ='<img src="source/plugin/cis_weixin/core/ui/shar_llq.png"/>';
		}
		$('body').append('<div class="shar_'+classname+'" id="shar_box">'+ins+'</div>');
		if(BRO=='qq' || BRO=='uc'){
			$(document).on('click', '.immwashar_list li', function() {
				$('#light').css({'display':'none','height':''});
				$('#dark').css('display', 'none');
				$('#shar_box').remove();
			});			
		  var share_obj = new immwashar('immwashar',config);

		}
	}
}
function loading(){
	var loadinghtml='<div class="imui_loading_toast"><div class="imui_mask_transparent"></div> <div class="imui_toast"><div class="imui_loading"><div class="imui_loading_leaf imui_loading_leaf_0"></div><div class="imui_loading_leaf imui_loading_leaf_1"></div><div class="imui_loading_leaf imui_loading_leaf_2"></div><div class="imui_loading_leaf imui_loading_leaf_3"></div><div class="imui_loading_leaf imui_loading_leaf_4"></div><div class="imui_loading_leaf imui_loading_leaf_5"></div><div class="imui_loading_leaf imui_loading_leaf_6"></div><div class="imui_loading_leaf imui_loading_leaf_7"></div><div class="imui_loading_leaf imui_loading_leaf_8"></div><div class="imui_loading_leaf imui_loading_leaf_9"></div><div class="imui_loading_leaf imui_loading_leaf_10"></div><div class="imui_loading_leaf imui_loading_leaf_11"></div></div><p class="imui_toast_content">数据加载中</p></div></div>';
	popup.open(loadinghtml,'loading');
}

/*callpage*/
function callpage(id){
	if($('#'+id+'_area').length){
		unsetaction();
		var main = $('#'+id+'_area').html();
		$('#'+id+'_area').remove();
		$('#'+id).html(main);
	}else{
		setaction("callpage('"+id+"')");
		var main = $('#'+id).html();
		$('#'+id).empty();
		$('#windowarea').append('<div id="'+id+'_area" class="showpagearea showpage_show">'+ main +'</div>');
	}
}
/*opentop*/
function opentop(id){
	if($('#'+id+'_area').length){
		unsetaction();
		var main = $('#'+id+'_area').html();
		$('#light').css({'display':'none','height':'','opacity':''});
		$('#dark').css('display', 'none');
		$('#'+id+'_area').remove();
		$('.body_main #'+id).html(main);
	}else{
		setaction("opentop('"+id+"')");
		var main = $('.body_main #'+id).html();
		$('.body_main #'+id).empty();
		$('#windowarea').append('<div id="'+id+'_area" class="imui_toparea open">'+ main +'</div>');
		$('#light').css({'display':'block','height':'50px','opacity':'0'});
		$('#dark').css('display', 'block');
		$('#light').off().on('click', function() {opentop(id);});
		$('#dark').off().on('click', function() {opentop(id);});
		$(document).on('click', '.imui_toparea a', function() {
			unsetaction();
			var main = $('#'+id+'_area').html();
			$('#light').css({'display':'none','height':'','opacity':''});
			$('#dark').css('display', 'none');
			$('#'+id+'_area').remove();
			$('.body_main #'+id).html(main);
		});
	}
}

function showheader(){
	if($('body').attr('id')=='noheader'){
		$('body').attr("id","");
	}else{
		$('body').attr("id","noheader");
	}
}
//openflickr
function openflickr(){
	if($('#imui_flickr').length){
		unsetaction();
		var main = $('#imui_flickr').html();
		$('#imui_flickr').remove();
		$('.body_main .imui_flickr').html(main);
		$('#light').css({'display':'none','height':''});
	}else{
		setaction("openflickr()");
		var main = $('.body_main .imui_flickr').html();
		$('.body_main .imui_flickr').empty();
		$('#windowarea').append('<div id="imui_flickr" class="imui_flickr b_f size_16">'+ main +'</div>');
		$('#light').css({'display':'block','height':'100%'});
		$('#light').off().on('click', function() {openflickr();});

	  $(document).on('click', '.imui_flickr a', function() {
			var main = $('#imui_flickr').html();
			$('#imui_flickr').remove();
			$('.body_main .imui_flickr').html(main);
			$('#light').css({'display':'none','height':''});
		});
	}
}

function needlogin(){
	popup.open('<div class="tip"><dt class="tc">您需要先登录才能继续本操作</dt><dd class="flex_box bo_t"><a href="member.php?mod=logging&action=login" class="cc flex bo_r showpage" id="member" type="getpage">登录</a><a href="javascript:;" class="flex" onclick="popup.close();">关闭</a></dd></div>','html');
}
function openpop(html){
	popup.open('<div class="tip">'+html+'</div>','html');
}

function closepop(){
	popup.close();
	unsetaction();
}
function loginout(formhash){
	loading();
	$.ajax({
		type:'GET',
		url:'member.php?mod=logging&action=logout&formhash='+formhash+'&inajax=1&check=1',
		dataType:'xml',
	})
	.success(function(s) {
		
		if(s.lastChild.firstChild.nodeValue!='ok'){
      popup.open(s.lastChild.firstChild.nodeValue);
		}else{
			$('.topuser').html('<a href="member.php?mod=logging&action=login"  class="showpage imui_user" id="member" type="getpage"><img src="'+UC_API+'/avatar.php?uid=0&size=middle"></a>');
			$('.side_user').html('<img src="'+UC_API+'/avatar.php?uid=0&size=middle"><h3 class="size_16">游客</h3><p><a href="member.php?mod=logging&action=login" class="showpage" id="member" type="getpage">登录</a></p>');
      cis_setcookie('logout','1','2592000');
			popup.open('您已成功退出登录', 'alert');						
		}
	})
	.error(function() {
		window.location.href = obj.attr('href');
		popup.close();
	});	
}
/*cis_setcookie*/
function cis_setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var cp = 'immwa';
	if(cookieValue == '' || seconds < 0) {
		cookieValue = '';
		seconds = -2592000;
	}
	if(seconds) {
		var expires = new Date();
		expires.setTime(expires.getTime() + seconds * 1000);
	}
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cp + '_'+cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}


