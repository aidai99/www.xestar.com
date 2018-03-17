<?php
/**
 *	[飞鸟视频播放器(fn_play.{modulename})] (C)2016-2099 Powered by 飞鸟工作室.
 *	Version: 1.0
 *	Date: 2016-8-22 10:27
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function replace_play($matches) {
	global $_G;
	$url = $matches[2];
	$pluginvar = $_G['cache']['plugin']['fn_play'];
	$fn_cache_dir = DISCUZ_ROOT.'source/plugin/fn_play/cache/';
	if(empty($_G[mobile])){
		$height = $pluginvar['height']?$pluginvar['height']:'275';
		$width = $pluginvar['width']?$pluginvar['width']:'500';
	}else{
		$height = $pluginvar['mobileheight']?$pluginvar['mobileheight']:'200';
		$width = $pluginvar['mobilewidth']?$pluginvar['mobilewidth']:'100%';
	}
	$url_array = array_filter(explode('/',$url));
	if(Is_Https()){
		$http = 'https://';
	}else{
		$http = 'http://';
	}
	if (in_array('youku', unserialize($pluginvar['video'])) && strpos($url, 'youku.com') !== FALSE) {//优酷
		if(strpos($url_array[count($url_array)],'.html') !== false){
			$video_src = $http."player.youku.com/embed/".str_replace('id_','',str_replace('.html','',$url_array[count($url_array)]));
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}else if(strpos($url,'.swf') !== false){
			$String = end(explode('/',current(array_filter(explode('/v.swf',$url)))));
			$video_src = $http."player.youku.com/embed/".$String;
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}else if(strpos($url,'player.youku.com') !== false){
			$video_src = $url;
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}
	}
	if (in_array('tudou', unserialize($pluginvar['video'])) && strpos($url, 'tudou.com') !== FALSE) {//土豆
			$video_src = $http."www.tudou.com/programs/view/html5embed.action?code=".str_replace('.html','',$url_array[count($url_array)])."&lcode=".$url_array[(count($url_array)-1)];
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
	}
	if (in_array('tencent', unserialize($pluginvar['video'])) && strpos($url, 'qq.com') !== FALSE) {//腾讯
		if(strpos($url_array[count($url_array)],'.html') !== false){
			$video_src = $http."v.qq.com/iframe/player.html?vid=".str_replace('.html','',$url_array[count($url_array)])."&auto=0";
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}else if(strpos($url,'.swf') !== false){
			$String = current(explode('&',end(array_filter(explode('vid=',$url)))));
			$video_src = $http."v.qq.com/iframe/player.html?vid=".$String."&auto=0";
			return '<iframe class="fn_play" width="'.$width.'" height="'.$height.'" src="'.$video_src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}
	}
	if (in_array('ifeng', unserialize($pluginvar['video'])) && strpos($url, 'v.ifeng.com') !== FALSE) {//凤凰视频
		if(strpos($url_array[count($url_array)],'.shtml') !== false){
			$myfile = $fn_cache_dir.str_replace('.shtml','',$url_array[count($url_array)]).'.txt';
			if(file_exists($myfile) && cache_time(filemtime($myfile)) > time()){
				$url = file_get_contents($myfile);
			}else{
				if(strpos($url_array[count($url_array)],'video_') !== false){
					$centert = get_curl_init($url);
					if(preg_match("#\"vid\": \"(.*?)\"#i",$centert,$matchs)){
						$playermsg = get_curl_init('http://dyn.v.ifeng.com/cmpp/wideo_msg_news.js?guid='.$matchs[1]);
						if(preg_match("#\"videoPlayUrl\":\"(.*?)\"#i",$playermsg,$matchs)){
							if(strpos($matchs[1],'mp4') > 0){
								$url = $matchs[1];
								file_put_contents($myfile,$url);
							}
						}
					}
				}else{
					$playermsg = get_curl_init('http://dyn.v.ifeng.com/cmpp/wideo_msg_news.js?guid='.str_replace('.shtml','',$url_array[count($url_array)]));
					if(preg_match("#\"videoPlayUrl\":\"(.*?)\"#i",$playermsg,$matchs)){
						if(strpos($matchs[1],'mp4') > 0){
							$url = $matchs[1];
							file_put_contents($myfile,$url);
						}
					}
				}
			}
			$height = $height.'px';
			if(empty($_G[mobile])){
				$width = $width.'px';
			}
			$Retrieve = Retrieve($url);
			include template('fn_play:mp4');
			return $return;
		}else if(strpos($url,'.swf') !== false){
			$String = current(explode('&',end(array_filter(explode('guid=',$url)))));
			$myfile = $String.'.txt';
			if(file_exists($myfile) && cache_time(filemtime($myfile)) > time()){
				$url = file_get_contents($myfile);
			}else{
				$playermsg = get_curl_init('http://dyn.v.ifeng.com/cmpp/wideo_msg_news.js?guid='.$String);
				if(preg_match("#\"videoPlayUrl\":\"(.*?)\"#i",$playermsg,$matchs)){
					if(strpos($matchs[1],'mp4') > 0){
						$url = $matchs[1];
						file_put_contents($myfile,$url);
					}
				}
			}
			$height = $height.'px';
			if(empty($_G[mobile])){
				$width = $width.'px';
			}
			$Retrieve = Retrieve($url);
			include template('fn_play:mp4');
			return $return;
		}
	}
	if (in_array('56', unserialize($pluginvar['video'])) && strpos($url, 'www.56.com') !== FALSE) {//56视频
		if(strpos($url_array[count($url_array)],'.html') !== false){

			if(strpos($url_array[count($url_array)],'play_album') !== false || strpos($url_array[count($url_array)],'v_') !== false){
				$myfile = $fn_cache_dir.str_replace('.html','',$url_array[count($url_array)]).'.txt';
				if(file_exists($myfile) && cache_time(filemtime($myfile)) > time()){
					$video_src = file_get_contents($myfile);
				}else{
					$centert = get_curl_init($url);
					if(preg_match("#vid: '(.*?)'#i",$centert,$matchs)){
						$vid = 	$matchs[1];
					}
					if(preg_match("#pid: '(.*?)'#i",$centert,$matchs)){
						$pid = 	$matchs[1];
					}
					if(preg_match("#cid: '(.*?)'#i",$centert,$matchs)){
						$cid = 	$matchs[1];
					}
					$video_src = $http."tv.sohu.com/upload/static/share/share_play.html#".$vid."_".$pid."_0_".$cid."_0";
					file_put_contents($myfile,$video_src);
				}
			}else{
				$video_src = 'http://www.56.com/iframe/'.str_replace('v_','',str_replace('.html','',$url_array[count($url_array)]));
			}
			return '<iframe src="'.$video_src.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowfullscreen=""></iframe>';
		}
	}
	if (in_array('ku6', unserialize($pluginvar['video'])) && strpos($url, 'v.ku6.com') !== FALSE) {//酷六
		if(strpos($url_array[count($url_array)],'.html') !== false){
			return '<script data-vid="'.str_replace('.html','',$url_array[count($url_array)]).'" src="//player.ku6.com/out/v.js" data-width="'.$width.'" data-height="'.$height.'"></script>';
		}
	}
	if (in_array('youtube', unserialize($pluginvar['video'])) && strpos($url, 'youtube') !== FALSE) {//youtube
		if(strpos($url_array[count($url_array)],'v=') !== false){
			$youtube_url = explode('v=',$url_array[count($url_array)]);
			$video_src = 'https://www.youtube.com/embed/'.$youtube_url[1];
			return '<iframe src="'.$video_src.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowfullscreen=""></iframe>';
		}
	}
	if (in_array('mp4', unserialize($pluginvar['video'])) && strpos($url, 'mp4') !== FALSE) {//mp4
		$height = $height.'px';
		if(empty($_G[mobile])){
			$width = $width.'px';
		}
		$Retrieve = Retrieve($url);
		include template('fn_play:mp4');
		return $return;
	}
	
	if(strpos($url,'yuntv.letv.com') !== false){
		$video_src = $url.'&width='.$width.'&height='.$height;
		return '<iframe src="'.$video_src.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowfullscreen=""></iframe>';
	}

	if (in_array('mp3', unserialize($pluginvar['audio'])) && strpos($url, 'mp3') !== FALSE) {//mp3
		if(empty($_G[mobile])){
			$width = $width.'px';
		}
		include template('fn_play:audio');
		return $return;
	}
	if (in_array('ogg', unserialize($pluginvar['audio'])) && strpos($url, 'ogg') !== FALSE) {//ogg
		if(empty($_G[mobile])){
			$width = $width.'px';
		}
		include template('fn_play:audio');
		return $return;
	}
}
//取文件名
function Retrieve($url) { 
	$result = explode('.',end(explode('/',$url)));
	return $result[0];
}
//获取获取时间
function cache_time($time){
	global $_G;
	$pluginvar = $_G['cache']['plugin']['fn_play'];
	$time = date('Y-m-d H:i:s',$time);
	return strtotime("$time +".$pluginvar[cache_time]." hours");
}
//模拟访问
function get_curl_init($url){
	$ip = rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255)."";
	$header = array();
	$header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
	$header[] = 'Accept-Encoding: gzip, deflate, sdch';
	$header[] = 'Accept-Language: zh-CN,zh;q=0.8';
	$header[] = 'Cache-Control: max-age=0';
	$header[] = 'Connection: keep-alive';
	$header[] = 'Connection: keep-alive';
	$header[] = 'X-FORWARDED-FOR:'.$ip;
	$header[] = 'CLIENT-IP:'.$ip;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com");   //构造来路
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36');// 保存到字符串而不是输出
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
//检测是否https
function Is_Https(){  
    if(!isset($_SERVER['HTTPS']))  return FALSE;  
    if($_SERVER['HTTPS'] === 1){  //Apache  
        return TRUE;  
    }elseif($_SERVER['HTTPS'] === 'on'){ //IIS  
        return TRUE;  
    }elseif($_SERVER['SERVER_PORT'] == 443){ //其他  
        return TRUE;  
    }  
    return FALSE;  
}  
?>