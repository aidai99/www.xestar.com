


/*--------------------------------------------------
gettab

--------------------------------------------------*/
$(document).on('click', '.gettab', function() {
	loading();
	var obj = $(this);
	var tabid= obj.attr('tab');
	var geturl= obj.attr('href');
  var top= obj.attr('top')?obj.attr('top'):'original';
	var his= obj.attr('his')?obj.attr('his'):'yes';
	
	if($('#'+tabid).length){
		var gettype= 'update';
	}else{
		var gettype= 'create';
	}
	var value = {
		url: geturl,
		type: gettype,
		tabid: tabid,
		top: top,
		his: his,
		html: ''
	};
	loadtab('ajax',value);
	return false;
});

/*loadtab*/
function loadtab(readtype,value){
	unsetaction();
	if(readtype=='ajax'){
		if(value.url.indexOf('mobile=2')<0){
			value.url= value.url + '&mobile=2';
		}		
		if(value.url.indexOf('?')>0){
			var geturl= value.url + '&getpage=1';
		}else{
			var geturl= value.url + '?getpage=1';
		}
		var tabid=value.tabid;		
		var type=value.type;
		var top=value.top;

		$.ajax({
			type : 'GET',
			url : geturl,
			dataType : 'html'
		})
    
		.success(function(s) {
			if(!s){
				popup.open('目标网页存在错误','alert');
				return false;
			}
			if(s.indexOf('<div class="tip">')!=-1){
				setaction("closepop()");
				popup.open(s,'html');
				return false;
			}
			if(s.indexOf('<div class="body_main')==-1){
				window.location.href = value.url;
				return false;
			}	
			
			if(!$(s).find('#'+tabid)){
				window.location.href = value.url;
				return false;
			}
			creattab(s,tabid,type,value.url,top);

			if(value.his=='no'){
				setaction("closetab('"+tabid+"');");
			}else{
				var hist=$(s).find('#'+tabid).attr('hist');
				if(!hist){
					unsetaction();
					var state = {
						url: value.url,
						tabid: tabid,
						html: s
					};					
					history.pushState(state,null,value.url);
							
				}else{
					setaction("closetab('"+tabid+"');");
				}	
			}
		})
		.error(function() {
			window.location.href = value.url;
			popup.close();
		});
		
	}else{
		var tabid=value.tabid;
		var s=value.html;
		creattab(s,tabid,'destroy');
	}
}

/*creattab*/
function creattab(s,tabid,type,url,top){
	$('html').removeClass('closeside');
	
	var hist = $('.body_main').attr('hist');
	if(hist){
		var upid = $('.body_main').attr('form');
		if(upid && upid!=tabid){
			destab($('.body_main').attr('id'));
		}
	}else{
		var upid = $('.body_main').attr('id');
	}
	
	/*
  var bodycss = $(s).find('#'+tabid).attr('class');
	var thisscroll = $(s).find('#'+tabid).attr('onscroll')?'onscroll="'+$(s).find('#'+tabid).attr('onscroll')+'"':'';
	if(!bodycss){
		var bodycss='body_main';
	}
	*/
	var title = $(s).find('.title_name').text();
	var css = $(s).find('.resetcolor').text();

	var header = $(s).find('.headerarea').html();
	var mainarea = $(s).find('.mainarea').html();
	var main = $(s).find('.body_main').html();
	var footer = $(s).find('.footerarea').html();


	sessionStorage.setItem(tabid, JSON.stringify({hd:header,ft:footer,url:url}));
	$('.headerarea').html(header);
	$('.footerarea').html(footer);
	$('title').text(title);
	$('#resetcolor').text(css);
	
	if(type=='update'){
    var thisscroll = $(s).find('#'+tabid).attr('onscroll')?$(s).find('#'+tabid).attr('onscroll'):'';
		
    $('#'+tabid).attr('onscroll',thisscroll);
		$('#'+tabid).html(main);
		if(upid!=tabid){
			$('#'+tabid).attr('form',upid);
		}
		$('.body_main').addClass('main_hide').removeClass('body_main cut_in');
		
		$('#'+tabid).addClass('body_main cut_in').removeClass('main_hide');
    if(top=='top'){
			$('.body_main').scrollTop('0', '1');
		}
	}else if(type=='create'){
		$('.body_main').addClass('main_hide').removeClass('body_main cut_in');
		$('.mainarea').append(mainarea);
		$('.body_main').addClass('cut_in');
		$('#'+tabid).attr('form',upid);
	}else if(type=='destroy'){
		if($('#'+tabid).length){
			if(upid!=tabid){
				$('#'+upid).css({'position':'absolute', 'z-index':'10'});
				$('#'+tabid).css({'position':'absolute', 'z-index':'1'});
				
				$('#'+upid).removeClass('cut_in').addClass('cut_out');
				$('#'+tabid).removeClass('main_hide').addClass('body_main cut_side');
				
        var t=setTimeout("destab('"+upid+"')",400)
							
			}else{
				creattab(s,tabid,'update',url);
				/*
				return false;
				*/
			}
		}else{
			$('.body_main').addClass('main_hide').removeClass('body_main cut_in');
			$('.mainarea').append(mainarea);
			$('.body_main').addClass('cut_in');
		}
	}
  
	popup.close();
	if($(s).find('#'+tabid).attr('class')){
		evalscript(s);
	}
	
}

function destab(upid){
	$('#'+upid).remove();
	$('.body_main').removeClass('cut_side').css({'position':'', 'z-index':''});
}
function closetab(id){
	unsetaction();
  var tabid=$('#'+id).attr('form');
	var upvar=sessionStorage.getItem(tabid);
	
	if(upvar){
		var upvar = JSON.parse(upvar);
		$('.headerarea').html(upvar.hd);
		$('.footerarea').html(upvar.ft);
		$('#'+id).remove();
		$('#'+tabid).addClass('body_main').removeClass('main_hide');		
	}else{
	}
}

/*--------------------------------------------------
getpage

--------------------------------------------------*/
$(document).on('click', '.getpage', function() {
	
	loading();
	var obj = $(this);
	var gettype= obj.attr('gettype');
	var getid= obj.attr('getid');
	var geturl= obj.attr('href');
		
	
	
	var value = {
		url: geturl,
		type: gettype,
		id: getid,
		html: ''
	};
	loadpage('ajax',value);
	popup.close();
	return false;

});

/*loadpage*/
function loadpage(readtype,value){
	
	if(value.url.indexOf('?')>0){
		var geturl= value.url + '&getpage=1';
	}else{
		var geturl= value.url + '?getpage=1';
	}
	var gettype=value.type;
	var getid=value.id;			
	if(readtype=='ajax'){
		
		$.ajax({
			type : 'GET',
			url : geturl,
			dataType : 'html'
		})
		.success(function(s) {
	    creatpage(s,gettype,getid);
			var state = {
				geturl:value.url,
				gettype: gettype,
				getid: getid,
				html: s
			};
			history.pushState(state,null,value.url);
		})
		.error(function() {
			window.location.href = geturl;
		});
	}else if(readtype=='history'){
		var s=value.html;
		creatpage(s,gettype,getid);
	}
}

/*creatpage*/
function creatpage(s,gettype,getid){
	$('html').removeClass('closeside');
	if(gettype=='one'){
		var content = $(s).find('.'+getid).html();
		$('.'+getid).html(content);					
	}else if(gettype=='dispersed'){
		
		var header = $(s).find('.headerarea').html();
		var main = $(s).find('.mainarea').html();
		var footer = $(s).find('.footerarea').html();
		
		$('.headerarea').html(header);
		$('.mainarea').html(main);
		
		$('.footerarea').html(footer);
	}else{
		$('div.wrap').html($(s).find('.wrap').html());
	}
}


		
/*--------------------------------------------------
backbtn

--------------------------------------------------*/
$(window).on("load", function(event) {
	var tabid=$('.body_main').attr('id');

	var header = $('.headerarea').html();
	var footer = $('.footerarea').html();
	sessionStorage.setItem(tabid, JSON.stringify({hd:header,ft:footer,url:window.location.href}));
	
	var state = {
		url: window.location.href,
		tabid: tabid,
		html: $('body').html(),
	};
	history.replaceState(state,null,window.location.href);
});

$(window).on("popstate", function(event) {
	var state = event.originalEvent.state;
	if(!state){
	}else{
		var tabid='';
		var upvar={};
		if(lastaction){
			eval(lastaction);
			var tabid=$('.body_main').attr('id');
			if(tabid){
				var upvar=sessionStorage.getItem(tabid);
				if(upvar){
					var upvar = JSON.parse(upvar);
					var state = {
						url: upvar.url,
						tabid: tabid,
						html: $('body').html(),
					};
					history.pushState(state,null,upvar.url);					
				}
			}

		}else{
			if(state.html){
				if(state.tabid){
					loadtab('history',state);
				}else{
					getpage('history',state);
				}
			}else{
				goto(state.url,state.tabid);
			}			
		}
	}
});