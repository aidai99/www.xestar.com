<?php
/**
 *	[飞鸟视频播放器(fn_play.{modulename})] (C)2016-2099 Powered by 飞鸟工作室.
 *	Version: 1.0
 *	Date: 2016-8-22 10:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(strpos($_GET[id],'aitoupiao') !== false){$AitouPiao = true;}
if(!$AitouPiao){require_once libfile('class/replace_play','plugin/fn_play');}
class plugin_fn_play {
	function discuzcode($param) {
		global $_G;
		
		$pluginvar=$_G['cache']['plugin']['fn_play'];
		if($param['caller'] == 'discuzcode' && in_array($_G['fid'], unserialize($pluginvar['section']))){
			if (strstr($_G['discuzcodemessage'],'[/media]')){
				$_G['discuzcodemessage'] = preg_replace_callback("/\[media(.*?)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/i","replace_play",$_G['discuzcodemessage']);
			}
		}
	}
}
class mobileplugin_fn_play {
	function discuzcode($param) {
		global $_G;
		$pluginvar=$_G['cache']['plugin']['fn_play'];
		if($param['caller'] == 'discuzcode' && in_array($_G['fid'], unserialize($pluginvar['section']))){
			if (strstr($_G['discuzcodemessage'],'[/media]')){
				$_G['discuzcodemessage'] = preg_replace_callback("/\[media(.*?)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/i","replace_play",$_G['discuzcodemessage']);
			}
		}
	}
}
//支持门户
class plugin_fn_play_portal{
	function view_fn_output() {
        global $content;
		if(strstr($content[content],'</object>')){
			$content[content] = str_replace(array("\r\n","\r","\n"),array('','',''),$content[content]);
			$content[content] = preg_replace_callback("/<object classid=\"(.*?)name=\"url\" value=\"(.*?)\">(.*?)<\/object>/i","replace_play",$content[content]);
		}
    }
}
//支持门户
class mobileplugin_fn_play_portal{
	function view_fn_output() {
        global $content;
		if(strstr($content[content],'</object>')){
			$content[content] = str_replace(array("\r\n","\r","\n"),array('','',''),$content[content]);
			$content[content] = preg_replace_callback("/<object classid=\"(.*?)name=\"url\" value=\"(.*?)\">(.*?)<\/object>/i","replace_play",$content[content]);
		}
    }
}
?>