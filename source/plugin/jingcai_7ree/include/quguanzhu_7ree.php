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

		if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
        $uid_7ree=intval($_GET['uid_7ree']);
        if(!$uid_7ree) showmessage("ERROR## Miss uid @ 7ree");
	    DB::query("DELETE FROM ".DB::table('jingcai_guanzhu_7ree')." WHERE uid_7ree = $_G[uid] AND touid_7ree = '{$uid_7ree}'");
		showmessage('jingcai_7ree:php_lang_quxiaook_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=7");


?>