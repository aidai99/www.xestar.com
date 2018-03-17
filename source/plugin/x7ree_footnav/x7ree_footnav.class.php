<?php

/*
	(C)2006-2016 [www.7ree.com]
	This is NOT a freeware, use is subject to license terms
	update: 2017/1/3 17:37
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_x7ree_footnav { 
	function global_footer_mobile(){
				global $_G;
				$return = "";
				$vars_7ree = $_G['cache']['plugin']['x7ree_footnav'];
				$modarray_7ree = $vars_7ree['mod_7ree'] ? unserialize($vars_7ree['mod_7ree']) : array();
				if($vars_7ree['action_7ree']){
						$action_7ree = explode(',', $vars_7ree['action_7ree']);
						if(in_array($_GET['action'],$action_7ree)){
							return $return;
						}
				}

				
				
				if($vars_7ree['agreement_7ree'] && in_array(CURSCRIPT,$modarray_7ree)){
					
						$name_7ree =  str_replace("\n","|||",trim($vars_7ree['name_7ree']));
						$name_array =  explode('|||', $name_7ree);
						$icon_7ree =  str_replace("\n","|||",trim($vars_7ree['icon_7ree']));
						$icon_array =  explode('|||', $icon_7ree);
						$url_7ree =  str_replace("\n","|||",trim($vars_7ree['url_7ree']));
						$url_array =  explode('|||', $url_7ree);
						$count_7ree = MIN(5,COUNT($name_array));
						$width_7ree =floor(80/$count_7ree)."%";
						include template('x7ree_footnav:x7ree_footnav');
			 			return $return;
				}



	}
}


class mobileplugin_x7ree_footnav_forum extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_group extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_home extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_member extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_portal extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_search extends mobileplugin_x7ree_footnav {
}

class mobileplugin_x7ree_footnav_plugin extends mobileplugin_x7ree_footnav {
}

?>