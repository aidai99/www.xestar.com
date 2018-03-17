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

	$page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
	$query = DB::query("SELECT Count(*) FROM ".DB::table('jingcai_fencheng_7ree')." WHERE fromuid_7ree = '{$_G[uid]}'");
	$querynum = DB::result($query,0);
	$query = DB::query("SELECT f.*,m.username,j.main_id_7ree,j.racename_7ree,j.aname_7ree,j.bname_7ree 
						FROM ".DB::table('jingcai_fencheng_7ree')." f 
						LEFT JOIN ".DB::table('common_member')." m ON f.uid_7ree = m.uid
						LEFT JOIN ".DB::table('jingcai_main_7ree')." j ON j.main_id_7ree = f.main_id_7ree
						WHERE f.fromuid_7ree ='{$_G[uid]}'
						ORDER BY f.id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$fenchenglist_7ree[]=$result;
	}	
	$multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=17");



?>