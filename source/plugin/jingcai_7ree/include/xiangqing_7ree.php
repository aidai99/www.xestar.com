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

    $main_id_7ree = intval($_GET['main_id_7ree']);
    if(!$main_id_7ree) showmessage("ERROR## Miss mid @ 7ree");
    
    $race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
    if(!$race_7ree) showmessage("Bad MainID #7ree_error.");
	$race_7ree['time_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time_7ree'] + $_G['setting']['timeoffset'] * 3600);

    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
	$query = DB::query("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' GROUP BY uid_7ree");
    $querynum = DB::result($query,0);
	$query = DB::query("SELECT l.*,i.* FROM ".DB::table('jingcai_log_7ree')." l 
						LEFT JOIN ".DB::table('jingcai_member_7ree')." i ON l.uid_7ree = i.uid_7ree
						WHERE l.main_id_7ree ='$main_id_7ree' AND l.uid_7ree>0 AND i.uid_7ree >0 AND i.type_7ree='{$race_7ree[fenlei2_7ree]}'
						GROUP BY l.uid_7ree 
						ORDER BY i.a_mingzhong_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['log_time_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$result['avatar_7ree'] = avatar($result['uid_7ree'], big, TRUE, FALSE, TRUE);
		if($result['uid_7ree']) $result['tuijian_7ree'] = DB::result_first("SELECT point_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree ='$main_id_7ree' AND point_7ree > 0 AND uid_7ree = ".$result['uid_7ree']);
		$newjingcailist_7ree[] = $result;
	}
	    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=14&main_id_7ree=".$main_id_7ree );
	

?>