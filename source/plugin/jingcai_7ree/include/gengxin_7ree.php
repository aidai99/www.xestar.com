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

		DB::query("DELETE FROM ".DB::table('jingcai_member_7ree')." WHERE a_changci_7ree = 0");
		$query = DB::query("SELECT uid_7ree FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree >0 ORDER BY a_mingzhong_7ree DESC LIMIT 100");
			while($result = DB::fetch($query)) {
			memberinfo_count_7ree($result['uid_7ree']);
		}
		showmessage('jingcai_7ree:php_lang_gengxinok_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=tj");


?>