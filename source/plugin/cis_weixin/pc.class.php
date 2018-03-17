<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_cis_weixin {

	function global_usernav_extra2() {
		global $_G;
		if($_G['uid'] && $_G['member']['adminid']==1){
			return '<span class="pipe">|</span><a href="plugin.php?id=cis_weixin&mod=admin">IMMWA</a>';
		}
		
	}	
	
}


?>