<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/25 18:01
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$scid_7ree = intval($_GET['scid_7ree']);
$main_id_7ree = intval($_GET['main_id_7ree']);
$log_id_7ree = intval($_GET['log_id_7ree']);
	
if(!$_GET['sp_7ree']){
}elseif($_GET['sp_7ree'] == "add"){
	$race_7ree['time_7ree']=gmdate("Y-m-d H:i", $_G['timestamp'] + $_G['setting']['timeoffset'] * 3600);
	
	
	$query = DB::query("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." WHERE time2_7ree>$_G[timestamp]-2592000 ORDER BY scid_7ree DESC LIMIT 100 ");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$racelist_7ree[] = $result;
	}

	
		$_G['setting']['editoroptions'] = str_pad(decbin($_G['setting']['editoroptions']), 2, 0, STR_PAD_LEFT);
		$editormode = $_G['setting']['editoroptions']{0};
		$allowswitcheditor = $_G['setting']['editoroptions']{1}; 
		$editorid = 'racemessage';
		$editor = array(
			'editormode' => $editormode,
			'allowswitcheditor' => $allowswitcheditor,
			'allowhtml' => 1,
			'allowhtml' => 1,
			'allowsmilies' => 1,
			'allowbbcode' => 1,
			'allowimgcode' => 1,
			'allowcustombbcode' => 0,
			'allowresize' => 1,
			'textarea' => 'racemessage_7ree',
			'simplemode' => !isset($_G['cookie']['editormode_'.$editorid]) ? 1 : $_G['cookie']['editormode_'.$editorid],
		);		
		loadcache('bbcodes_display');

}elseif($_GET['sp_7ree'] == "add1"){
	$race_7ree['time_7ree']=gmdate("Y-m-d H:i", $_G['timestamp'] +86400+ $_G['setting']['timeoffset'] * 3600);
	$race_7ree['time2_7ree']=gmdate("Y-m-d H:i", $_G['timestamp'] +97200+ $_G['setting']['timeoffset'] * 3600);
	
}elseif($_GET['sp_7ree'] == "op"){
	
    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
    $querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_main_7ree'));
	$query = DB::query("SELECT * FROM ".DB::table('jingcai_main_7ree')." ORDER BY main_id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$result['scname_7ree']=DB::result_first("SELECT scname_7ree FROM ".DB::table('jingcai_saicheng_7ree')." WHERE scid_7ree='{$result[scid_7ree]}'");
        if($result['win_7ree']){
        	$result['jiesuan_7ree']=DB::result_first("SELECT log_id_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE log_reward_7ree = 0 AND main_id_7ree = ".$result['main_id_7ree']);
        }else{
        	$result['jiesuan_7ree']=0;
        }
		$racelist_7ree[] = $result;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op" );
	$mymultipage_7ree = $multipage;

}elseif($_GET['sp_7ree'] == "op1"){
    $page = max(1, intval($_GET['page']));
		$startpage = ($page - 1) * 20;
		$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_saicheng_7ree'));
		$query = DB::query("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." ORDER BY scid_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);

		$racelist_7ree[] = $result;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op1" );
	$mymultipage_7ree = $multipage;

}elseif($_GET['sp_7ree'] == "edit"){
		$race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
		
	
	$query = DB::query("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." WHERE time2_7ree>$_G[timestamp]-2592000 ORDER BY scid_7ree DESC LIMIT 100 ");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$racelist_7ree[] = $result;
	}

		
		$race_7ree['time_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
	
		$_G['setting']['editoroptions'] = str_pad(decbin($_G['setting']['editoroptions']), 2, 0, STR_PAD_LEFT);
		$editormode = $_G['setting']['editoroptions']{0};
		$allowswitcheditor = $_G['setting']['editoroptions']{1}; 
		$editorid = 'racemessage';
		$editor = array(
			'editormode' => $editormode,
			'allowswitcheditor' => $allowswitcheditor,
			'allowhtml' => 1,
			'allowhtml' => 1,
			'allowsmilies' => 1,
			'allowbbcode' => 1,
			'allowimgcode' => 1,
			'allowcustombbcode' => 0,
			'allowresize' => 1,
			'textarea' => 'racemessage_7ree',
			'simplemode' => !isset($_G['cookie']['editormode_'.$editorid]) ? 1 : $_G['cookie']['editormode_'.$editorid],
		);		
		loadcache('bbcodes_display');


}elseif($_GET['sp_7ree'] == "edit1"){
		$race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." WHERE scid_7ree = '$scid_7ree'");
		$race_7ree['time_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$race_7ree['time2_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time2_7ree'] + $_G['setting']['timeoffset'] * 3600);
		

}elseif($_GET['sp_7ree'] == "fengpan"){
		if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
		DB::query("UPDATE ".DB::table('jingcai_main_7ree')." SET fengpan_7ree = 1 WHERE main_id_7ree = $main_id_7ree LIMIT 1");
		showmessage("封盘操作成功，现在返回。","plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");

}elseif($_GET['sp_7ree'] == "jiefeng"){
		if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
		DB::query("UPDATE ".DB::table('jingcai_main_7ree')." SET fengpan_7ree = 0 WHERE main_id_7ree = $main_id_7ree LIMIT 1");
		showmessage("解封操作成功，现在返回。","plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");

}elseif($_GET['sp_7ree'] == "del"){

	if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
	//如有会员参加，则无法删除赛事
	$canjia_7ree = DB::result_first("SELECT log_id_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
	if($canjia_7ree) showmessage("jingcai_7ree:php_lang_shanchuerror_7ree");
	DB::query("DELETE FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
	showmessage('jingcai_7ree:php_lang_chenggongshanchu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");


}elseif($_GET['sp_7ree'] == "del1"){
	
	if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
	DB::query("DELETE FROM ".DB::table('jingcai_saicheng_7ree')." WHERE scid_7ree = '$scid_7ree'");
	showmessage('jingcai_7ree:php_lang_chenggongshanchu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op1");
	
	
}elseif($_GET['sp_7ree'] == "dellog"){

	if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
	DB::query("DELETE FROM ".DB::table('jingcai_log_7ree')." WHERE log_id_7ree = '$log_id_7ree'");
	showmessage('jingcai_7ree:php_lang_chenggongshanchulog_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=log");
	
}elseif($_GET['sp_7ree'] == "dells"){

	if(!submitcheck('submit_7ree', 1)) showmessage("Access Deined. @ 7ree.com");
	if(count($_GET[mod_7ree])){
		$mod_7ree = dintval((array)$_GET['mod_7ree'], true);
		DB::query("DELETE FROM ".DB::table('jingcai_log_7ree')." WHERE log_id_7ree IN(".dimplode($mod_7ree).")");
		showmessage('jingcai_7ree:php_lang_chenggongshanchulog_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=log");
	}else{
		showmessage('jingcai_7ree:php_lang_ERRORshanchulog_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=log");

	}
	
	
}elseif($_GET['sp_7ree'] == "truncate"){

	if($_GET['formhash'] <> FORMHASH || $_G['adminid']<>1) showmessage("Access Deined. @ 7ree.com");

	DB::query("TRUNCATE ".DB::table('jingcai_log_7ree'));

	showmessage('jingcai_7ree:php_lang_msg_delalllogok_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=log");


}elseif($_GET['sp_7ree'] == "log"){
    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
    $querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree  WHERE racename_7ree<>''");
	$query = DB::query("SELECT l.*,m.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree WHERE racename_7ree<>'' ORDER BY l.log_id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['log_time_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$loglist_7ree[] = $result;
	}
	    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=log" );
		$mymultipage_7ree = $multipage."      [<a href='http://www.7ree.com' target='_blank'><font color=gray>Powered by 7ree.com</font></a>]";	

}elseif($_GET['sp_7ree'] == "tj"){
	
if (submitcheck('submit_7ree', 1)) {

	$btime_7ree = trim(dhtmlspecialchars($_GET['btime_7ree']));
	$etime_7ree = trim(dhtmlspecialchars($_GET['etime_7ree']));

	if(!$btime_7ree || !$etime_7ree) showmessage('jingcai_7ree:php_lang_error_time_7ree',"");
	
	$bbtime_7ree = $btime_7ree ? strtotime($btime_7ree) : 0;
	$eetime_7ree = $etime_7ree ? strtotime($etime_7ree) : 0;	
	
	if($btime_7ree > $etime_7ree) showmessage('jingcai_7ree:php_lang_error_time2_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=tj");

	$time_where_7ree = " WHERE log_time_7ree > $bbtime_7ree AND log_time_7ree < $eetime_7ree ";
	$time_where2_7ree = " WHERE time_7ree > $bbtime_7ree AND time_7ree < $eetime_7ree ";
	
	$tongji_7ree = DB::fetch_first("SELECT COUNT(*) AS allnum_7ree, 
										   SUM(myodds_7ree) AS allodds_7ree, 
		                                   SUM(log_reward_7ree) AS allreward_7ree
									FROM ".DB::table('jingcai_log_7ree').$time_where_7ree);
	$changci_num_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_main_7ree').$time_where2_7ree);
	
	$days_7ree =  (int)(($eetime_7ree - $bbtime_7ree)/86400);
	$shoulilv_7ree = $tongji_7ree['allodds_7ree'] ? round((($tongji_7ree['allreward_7ree']*100)/$tongji_7ree['allodds_7ree']),2):0;
	$jingshoulilv_7ree = $tongji_7ree['allodds_7ree'] ? round(((($tongji_7ree['allreward_7ree']-$tongji_7ree['allodds_7ree'])*100)/$tongji_7ree['allodds_7ree']),2):0;	
	
}
	
}elseif($_GET['sp_7ree'] == "js"){//结算及批量删除
	if(!submitcheck('submit_7ree', 1) && !submitcheck('submit2_7ree', 1)) showmessage('Permission denied. @7ree', NULL, 'NOPERM');
	if(count($_GET[mod2_7ree])){
		$mod2_7ree = dintval((array)$_GET['mod2_7ree'], true);
		//如有会员参加，则无法删除赛事
		$canjia_7ree = DB::result_first("SELECT log_id_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree IN(".dimplode($mod2_7ree).")");
		if($canjia_7ree) showmessage("jingcai_7ree:php_lang_shanchuerror_7ree");
		DB::query("DELETE FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree IN(".dimplode($mod2_7ree).")");
		showmessage('jingcai_7ree:php_lang_chenggongshanchu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");
	}
	if(count($_GET[mod_7ree])){
		$mod_7ree = dintval((array)$_GET['mod_7ree'], true);
		foreach($mod_7ree AS $key_7ree=>$mod_value){
			settlement_7ree($mod_value);
		}
	showmessage("jingcai_7ree:php_lang_msg_jiesuanok_7ree","plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");
	}else{
	showmessage("jingcai_7ree:php_lang_msg_qingxuanze_7ree","plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");
	}
		

}elseif($_GET['sp_7ree'] == "zd"){
$zd_already_7ree = file_exists('source/plugin/jccaiji_7ree/cron') ? TRUE : FALSE;
//$zd_already_7ree =  FALSE;
}elseif($_GET['sp_7ree'] == "sj"){
$sj_already_7ree = file_exists('source/plugin/jingcai_7ree/template/touch') ? TRUE : FALSE;
//$sj_already_7ree = FALSE;
}elseif($_GET['sp_7ree'] == "diy"){
$diy_already_7ree = file_exists('source/class/block/jingcai7ree') ? TRUE : FALSE;
//$diy_already_7ree = FALSE;
}elseif($_GET['sp_7ree'] == "wjt"){
$wjt_already_7ree = file_exists('source/plugin/jingcai_7ree/rewrite_config_7ree.inc.php') ? TRUE : FALSE;
//$wjt_already_7ree = FALSE;
}



?>