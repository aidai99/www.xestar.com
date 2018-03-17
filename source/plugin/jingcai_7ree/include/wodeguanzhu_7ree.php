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

		$query = DB::query("SELECT * FROM ".DB::table('jingcai_guanzhu_7ree')." WHERE uid_7ree = '{$_G[uid]}' LIMIT 200");
		while($result = DB::fetch($query)) {
			$result['count_7ree'] = DB::result_first("SELECT a_changci_7ree FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$result[touid_7ree]}' ");
			$myguanzhulist_7ree[] = $result;
		}
?>