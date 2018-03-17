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

	if(!$jingcai_7ree_var['newrank_7ree']) showmessage("Access Denied.");
	
    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
    $querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree WHERE racename_7ree<>'' ");
	$query = DB::query("SELECT l.*, l.type_7ree AS ltype_7ree, m.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree  WHERE racename_7ree<>'' ORDER BY l.log_id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['log_time_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$loglist_7ree[] = $result;
	}
	    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=5&sp_7ree=log" );

?>