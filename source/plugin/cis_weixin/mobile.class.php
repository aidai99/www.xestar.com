<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}



class mobileplugin_cis_weixin{

  function discuzcode($param) {
		global $_G;
		
		if ($param['caller'] == 'discuzcode' ) {

			/* music */
			$_G['discuzcodemessage'] = preg_replace_callback('/\s?\[audio\](.*?)\[\/audio\]/is', 'cis_music', $_G['discuzcodemessage']);

			/* flash */
			$_G['discuzcodemessage'] = preg_replace_callback('/\s?\[flash\](.*?)\[\/flash\]/is', 'cis_video_1', $_G['discuzcodemessage']);
			$_G['discuzcodemessage'] = preg_replace_callback("/\s?\[flash=([\w,]+)\](.*?)\[\/flash\]/is", 'cis_video_2', $_G['discuzcodemessage']);
			
			/* media */
			$_G['discuzcodemessage'] = preg_replace_callback("/\s?\[media=([\w,]+)\](.*?)\[\/media\]/is", 'cis_video_2', $_G['discuzcodemessage']);
			$_G['discuzcodemessage'] = preg_replace_callback('/\s?\[media\](.*?)\[\/media\]/is', 'cis_video_1', $_G['discuzcodemessage']);

		}
	}
}



function cis_video_1($m) {
	global $_G;
	$height = '180';
	$width = '100%';
	$lowerurl = strtolower($m[1]);
  	if(dstrpos($m[1], array('.wmv', '.avi', '.rmvb', '.mov', '.flv', '.mp4'))){
		return '<video src="'.$m[1].'" controls="controls" width="100%" ></video>';
	}else{
		if(dstrpos($lowerurl, array('v.youku.com/v_show/', 'player.youku.com/player.php/'))) {
		$regexp = strexists($lowerurl, 'v.youku.com/v_show/') ? '/http:\/\/v.youku.com\/v_show\/id_([a-zA-Z0-9]+)/i' : '/http:\/\/player.youku.com\/player.php\/(?:.+\/)?sid\/([^\/]+)\/v.swf/i';
			if (preg_match($regexp, $lowerurl, $matches)) {
				$iframe = 'http://player.youku.com/embed/'.$matches[1];
			}
		}elseif(strpos($lowerurl, 'tudou.com/') !== FALSE) {
	
			$iframe = parse_tudouicode($m[1]);
			
		}elseif(strpos($lowerurl, 'qq.com/') !== FALSE) {
			if(preg_match("/vid=(.*?)auto/", $lowerurl, $matches)) {
				$iframe = 'https://v.qq.com/iframe/player.html?vid='.$matches[1].'tiny=0&auto=0';
			}
		}
		return '<iframe height="'.$height.'" width="'.$width.'" src="'.$iframe.'" frameborder=0 allowfullscreen></iframe>';		
	}

}

function cis_video_2($m) {
	global $_G;
	$height = '180';
	$width = '100%';
	$lowerurl = strtolower($m[2]);
  	if(dstrpos($url, array('.wmv', '.avi', '.rmvb', '.mov', '.flv', '.mp4'))){
		return '<video src="'.$m[2].'" controls="controls" width="100%" ></video>';
	}else{
		if(dstrpos($lowerurl, array('v.youku.com/v_show/', 'player.youku.com/player.php/'))) {
		$regexp = strexists($lowerurl, 'v.youku.com/v_show/') ? '/http:\/\/v.youku.com\/v_show\/id_([a-zA-Z0-9]+)/i' : '/http:\/\/player.youku.com\/player.php\/(?:.+\/)?sid\/([^\/]+)\/v.swf/i';
			if (preg_match($regexp, $lowerurl, $matches)) {
				$iframe = 'http://player.youku.com/embed/'.$matches[1];
			}
		}elseif(strpos($lowerurl, 'tudou.com/') !== FALSE) {
	
			$iframe = parse_tudouicode($m[2]);
			
		}elseif(strpos($lowerurl, 'qq.com/') !== FALSE) {
			if(preg_match("/vid=(.*?)auto/", $lowerurl, $matches)) {
				$iframe = 'https://v.qq.com/iframe/player.html?vid='.$matches[1].'tiny=0&auto=0';
			}
		}
		return '<iframe height="'.$height.'" width="'.$width.'" src="'.$iframe.'" frameborder=0 allowfullscreen></iframe>';		
	}

}



function cis_music($m){
	global $_G;
	$lowerurl = strtolower($m[1]);
	
	return '<audio controls="controls" style="width:100%;margin:10px 0;"><source src="'.$m[1].'" type="audio/mpeg"></audio>';
}

function parse_tudouicode($url){
	global $_G;
	$url = html_entity_decode($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$content = curl_exec($ch);
	$info = curl_getinfo($ch);
	if(!$info['url'] || $info['url'] == $url){
		preg_match('/Location: (.*?)\n/',$content,$ma);
		$info['url'] = $ma[1];
	}
	$p = '/(.*?)&code=(\w+)&(.*?)/';
	preg_match($p,$info['url'],$matches);
	if(!$matches || !$matches[2]) return false;
	return 'http://www.tudou.com/programs/view/html5embed.action?code='.$matches[2];
}



?>
