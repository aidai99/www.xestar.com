<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/3 16:18
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

		if($viewextname_7ree && $jingcai_7ree_var['qingkongcost_7ree'] && $jingcai_7ree_var['qingkongday_7ree']){
				if(DB::result_first("SELECT {$viewextname_7ree} FROM ".DB::table('common_member_count')." WHERE uid='{$_G[uid]}'") < $jingcai_7ree_var['qingkongcost_7ree']) showmessage('jingcai_7ree:php_lang_jifenbuzu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
				DB::query("UPDATE ".DB::table('common_member_count')." SET {$viewextname_7ree} = {$viewextname_7ree} - {$jingcai_7ree_var[qingkongcost_7ree]} WHERE uid='{$_G[uid]}' LIMIT 1");
				DB::query("DELETE FROM ".DB::table('jingcai_log_7ree')." WHERE log_time_7ree > $_G[timestamp] - 31*$jingcai_7ree_var[qingkongday_7ree] AND uid_7ree = '{$_G[uid]}'");
				memberinfo_count_7ree();
				showmessage('jingcai_7ree:php_lang_qingkongok_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
		}else{
				showmessage('jingcai_7ree:php_lang_qingkongerr_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
		}


?>