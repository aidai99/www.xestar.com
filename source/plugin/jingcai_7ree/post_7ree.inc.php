<?php

/*
	(C)2006-2016 [www.7ree.com]
	Update: 2016/7/1 18:39
	This is NOT a freeware, use is subject to license terms
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));

}

$tid = intval($_GET['tid']);
$main_id_7ree = intval($_GET['main_id_7ree']);
if(!$tid) showmessage("ERR#7REE: MISS TID.");
if(!submitcheck("submit_7ree")) showmessage("ERR#7REE:Access Denied.");
if($tid && submitcheck("submit_7ree")){
		//数据过滤
		$message_7ree = dhtmlspecialchars(trim($_GET['message_7ree']));
		if(!$message_7ree){
				showmessage("jingcai_7ree:php_lang_chaterr_msg_7ree","plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=".$main_id_7ree);
		}
		$fid = DB::result_first("SELECT fid FROM ".DB::table('forum_thread')." WHERE tid='{$tid}'");
		//帖子数据入库
			require_once DISCUZ_ROOT.'./source/function/function_forum.php'; 
			require_once DISCUZ_ROOT.'./source/function/function_post.php';
			$pid = insertpost(array(
							'tid' => $tid,
							'fid' => $fid,
							'authorid' => $_G['uid'],
							'author' => $_G['username'],
							'message' => $message_7ree,
							'dateline' => $_G['timestamp'],
							'useip' => $_G['clientip'],
							));

		//更新版块、主题动态
		DB::query("UPDATE ".DB::table('forum_thread')." SET lastposter='$_G[username]', lastpost='$_G[timestamp]', replies=replies+1 WHERE tid='{$tid}'");
		DB::query("UPDATE ".DB::table('forum_forum')." SET threads=threads+1, posts=posts+1, todayposts=todayposts+1 WHERE fid='{$fid_7ree}'");

		showmessage("jingcai_7ree:php_lang_chatok_post_7ree","plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=".$main_id_7ree);
}



?>