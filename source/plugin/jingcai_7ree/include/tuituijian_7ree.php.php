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
$lid_7ree=intval($_GET['lid_7ree']); 
if(!$lid_7ree) showmessage("ERROR## Miss lid @ 7ree");
if($jingcai_7ree_var[pointnum_7ree]){
		$blimittime_7ree=strtotime(date("Y-m-d"));
		//根据分类计算推荐次数
		$thismainid_7ree = DB::result_first("SELECT main_id_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE log_id_7ree='{$lid_7ree}' ");
		$thisfenlei2_7ree = DB::result_first("SELECT fenlei2_7ree FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree ='{$thismainid_7ree}' ");
		$thispointnum_7ree = DB::result_first("SELECT COUNT(log_id_7ree) FROM ".DB::table('jingcai_log_7ree')." l 
											LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree
											WHERE m.fenlei2_7ree='{$thisfenlei2_7ree}' AND l.uid_7ree='{$_G[uid]}' AND l.point_7ree > $blimittime_7ree");
		if($thispointnum_7ree>0){
							if($thispointnum_7ree>= $jingcai_7ree_var[pointnum_7ree]) showmessage('jingcai_7ree:php_lang_error_muchtuijian_7ree');
		}
}
DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET point_7ree = $_G[timestamp] WHERE uid_7ree='{$_G[uid]}' AND log_id_7ree = $lid_7ree LIMIT 1");
showmessage('jingcai_7ree:php_lang_tuijianok_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");


?>