<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/3 1:24
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function analysis_7ree($url_7ree,$height_7ree){
		global $_G;
		$return_7ree = '';
		$vars_7ree = $_G['cache']['plugin']['x7ree_v'];
		$from_7ree =  str_replace("\n","|||",$vars_7ree['from_7ree']);
		$from_array =  explode('|||', $from_7ree);
		
		foreach($from_array as $from_value){
				$from_array2 = explode(':',trim($from_value));
				$from_add_7ree[$from_array2[0]]=$from_array2[1];
		}

		$iframe_onoff=0;
		$vars_7ree['iframe_7ree'] =  str_replace("\n","|||",$vars_7ree['iframe_7ree']);
		$iframe_7ree = explode('|||',trim($vars_7ree['iframe_7ree']));
		foreach($iframe_7ree as $iframe_array){
			if(stristr($url_7ree,$iframe_array)){
				$iframe_onoff=1;
				break;
			}
		}

		if($iframe_onoff){
				if(!$url_7ree) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$url_7ree."' frameborder=0 'allowfullscreen'></iframe>";
		}elseif(stristr($url_7ree,'.mp4')){

		if($vars_7ree['mp4player_7ree']==1){
			$return_7ree="<video height='".$height_7ree."' width='100%' controls autobuffer><source src='".$url_7ree."' type='video/mp4' />Your browser does not support the video tag.</video>";
		}elseif($vars_7ree['mp4player_7ree']==2){
			$return_7ree=<<<EOF
				<div id="a1"></div>
				<script type="text/javascript" src="./source/plugin/x7ree_v/template/ckplayer/ckplayer.js" charset="utf-8"></script>
				<script type="text/javascript">
					var flashvars={
					    f:'$url_7ree',
					    c:0
					};
					var video=['$url_7ree'];
					CKobject.embed('./source/plugin/x7ree_v/template/ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','$height_7ree',false,flashvars,video);
				</script>
EOF;
		}

		}elseif(strstr($url_7ree,'youku.com')){
			

				if(stristr($url_7ree,'/embed/')){
					$return_7ree = str_replace("http://","https://",$url_7ree);
					if($from_add_7ree['youku.com']){
							$return_7ree = $return_7ree . $from_add_7ree['youku.com'];
							
					}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";
				}else{
					//str_replace("==","",$url_7ree);
					preg_match("#id_(.*?).html#",$url_7ree,$url_7ree);
					if(!$url_7ree[1]) showmessage('x7ree_v:php_lang_err_badurl_7ree');
					//$return_7ree = 'https://player.youku.com/embed/'.$url_7ree[1].'==';
					$return_7ree=<<<EOF
					<div id="youkuplayer" style="width:100%;height:$height_7ree"></div>
					<script type="text/javascript" src="//player.youku.com/jsapi"></script>
					<script type="text/javascript">
					var player = new YKU.Player('youkuplayer',{
					styleid: '0',
					client_id: 'YOUR YOUKUOPENAPI CLIENT_ID',
					vid: '$url_7ree[1]',
					newPlayer: true
					});
					</script>
EOF;
				}


		}elseif(strstr($url_7ree,'qiyi.com')){
				if(!$url_7ree) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = $url_7ree;
				if($from_add_7ree['iqiyi.com']){
					$return_7ree = $return_7ree . $from_add_7ree['iqiyi.com'];
				}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";

		}elseif(strstr($url_7ree,'qq.com')){
				preg_match("#https://v.qq.com/x/(.*?)/(.*?).html#",$url_7ree,$url2_7ree);
				if(!$url2_7ree[2]) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = 'https://v.qq.com/iframe/player.html?vid='.$url2_7ree[2].'&tiny=0&auto=0';
				if($from_add_7ree['qq.com']){
					$return_7ree = $return_7ree . $from_add_7ree['qq.com'];
				}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";

		}elseif(strstr($url_7ree,'sohu.com')){
				if(!$url_7ree) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = $url_7ree;
				$return_7ree = $url_7ree;
				if($from_add_7ree['sohu.com']){
					$return_7ree = $return_7ree . $from_add_7ree['sohu.com'];
				}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";

		}elseif(strstr($url_7ree,'ifeng.com')){
				if(!$url_7ree) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = $url_7ree;
				$return_7ree = $url_7ree;
				if($from_add_7ree['ifeng.com']){
					$return_7ree = $return_7ree . $from_add_7ree['ifeng.com'];
				}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";

		}elseif(strstr($url_7ree,'56.com')){
				if(!$url_7ree) showmessage('x7ree_v:php_lang_err_badurl_7ree');
				$return_7ree = $url_7ree;
				$return_7ree = $url_7ree;
				if($from_add_7ree['56.com']){
					$return_7ree = $return_7ree . $from_add_7ree['56.com'];
				}
				$return_7ree = "<iframe height='".$height_7ree."' width='100%' src='".$return_7ree."' frameborder=0 'allowfullscreen'></iframe>";

		}else{
				showmessage('x7ree_v:php_lang_err_badurl_7ree');
		}
		return $return_7ree;

}

?>