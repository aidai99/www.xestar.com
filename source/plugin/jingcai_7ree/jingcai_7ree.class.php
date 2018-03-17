<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2015 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 10:54 2015/4/3
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/		
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
	
class plugin_jingcai_7ree_member{
	function register_jingcai_7ree_output(){	
		global $_G;
		$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
		if(!$jingcai_7ree_var['agreement_7ree']) return;  
		$promotion_7ree = $_G['cookie']['promotion'] ? intval($_G['cookie']['promotion']) : 0;
		if($promotion_7ree){
			DB::query("INSERT INTO ".DB::table('jingcai_tmp_7ree')." (fromuid_7ree,ip_7ree) VALUES ('$promotion_7ree','$_G[clientip]')");
		}else{
			if($_G['uid']){
					$iplimit_7ree = $jingcai_7ree_var['tuiguangip_7ree'] ? $jingcai_7ree_var['tuiguangip_7ree']*3600 : 0;
					$fid_7ree = DB::result_first("SELECT fromuid_7ree FROM ".DB::table('jingcai_tmp_7ree')." WHERE ip_7ree = '{$_G[clientip]}'");
					if($fid_7ree){
						if($iplimit_7ree){
							$ipnum_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_tuiguang_7ree')." WHERE ip_7ree = '{$_G[clientip]}' AND fromuid_7ree = '{$fid_7ree}' AND time_7ree > $_G[timestamp] - $iplimit_7ree");
						}else{
							$ipnum_7ree = 0;
						}/**/
						if(!$ipnum_7ree){
						DB::query("INSERT INTO ".DB::table('jingcai_tuiguang_7ree')." (fromuid_7ree,uid_7ree,ip_7ree,time_7ree) VALUES ('$fid_7ree','$_G[uid]','$_G[clientip]','$_G[timestamp]')");
						}
						DB::query("DELETE FROM ".DB::table('jingcai_tmp_7ree')." WHERE ip_7ree = '{$_G[clientip]}' AND fromuid_7ree = '{$fid_7ree}'");
					}
			}
		}
    }
}

?>