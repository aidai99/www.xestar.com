<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/3 15:21
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (!submitcheck('add_submit_7ree', 1)) exit('Access Denied @ 7ree');

$main_id_7ree = intval($_GET['main_id_7ree']);

$racename_7ree = trim(daddslashes(dhtmlspecialchars($_GET['racename_7ree'])));
$scid_7ree = intval($_GET['scid_7ree']);
$reward_7ree = intval($_GET['reward_7ree']);
$add_7ree= trim(daddslashes(dhtmlspecialchars($_GET['add_7ree'])));
$time_7ree = trim(daddslashes(dhtmlspecialchars($_GET['time_7ree'])));
$aname_7ree = trim(daddslashes(dhtmlspecialchars($_GET['aname_7ree'])));
$bname_7ree = trim(daddslashes(dhtmlspecialchars($_GET['bname_7ree'])));
$alogo_7ree = trim(daddslashes(dhtmlspecialchars($_GET['alogo_7ree'])));
$blogo_7ree = trim(daddslashes(dhtmlspecialchars($_GET['blogo_7ree'])));
$amessage_7ree = trim(daddslashes(dhtmlspecialchars($_GET['amessage_7ree'])));
$bmessage_7ree = trim(daddslashes(dhtmlspecialchars($_GET['bmessage_7ree'])));
$racemessage_7ree = trim(daddslashes(dhtmlspecialchars($_GET['racemessage_7ree'])));
$win_7ree = trim(daddslashes(dhtmlspecialchars($_GET['win_7ree'])));
$fenlei1_7ree = trim(daddslashes(dhtmlspecialchars($_GET['fenlei1_7ree'])));
$fenlei2_7ree = trim(daddslashes(dhtmlspecialchars($_GET['fenlei2_7ree'])));
$rangqiufang_7ree = intval($_GET['rangqiufang_7ree']);
$rangqiuway_7ree = trim(daddslashes(dhtmlspecialchars($_GET['rangqiuway_7ree'])));

if(!$racename_7ree) showmessage('jingcai_7ree:php_lang_error_name_7ree',"");
if(!$reward_7ree) showmessage('jingcai_7ree:php_lang_error_reward_7ree',"");
if(!$time_7ree) showmessage('jingcai_7ree:php_lang_error_time_7ree',"");
if(!$aname_7ree) showmessage('jingcai_7ree:php_lang_error_aname_7ree',"");
if(!$bname_7ree) showmessage('jingcai_7ree:php_lang_error_bname_7ree',"");

$aodds_7ree = floatval($_GET['aodds_7ree']);
$bodds_7ree = floatval($_GET['bodds_7ree']);
$codds_7ree = floatval($_GET['codds_7ree']);
$daodds_7ree = floatval($_GET['daodds_7ree']);
$xiaoodds_7ree = floatval($_GET['xiaoodds_7ree']);

$odd_dynamic_7ree = intval($_GET['odd_dynamic_7ree']);
$odd_ratio_7ree = floatval($_GET['odd_ratio_7ree']);
$max_odd_7ree = floatval($_GET['max_odd_7ree']);
$min_odd_7ree = floatval($_GET['min_odd_7ree']);
$daxiao_7ree = trim(daddslashes(dhtmlspecialchars($_GET['daxiao_7ree'])));

$ashot_7ree =intval($_GET['ashot_7ree']);
$bshot_7ree=intval($_GET['bshot_7ree']);
$daxiaowin_7ree=trim(daddslashes(dhtmlspecialchars($_GET['daxiaowin_7ree'])));



//if(!$aodds_7ree || !$bodds_7ree || !$codds_7ree)  showmessage('jingcai_7ree:php_lang_error_odds_7ree',"");
if(!$aodds_7ree || !$bodds_7ree)  showmessage('jingcai_7ree:php_lang_error_odds_7ree',"");
if($daxiao_7ree){
	if(!$daodds_7ree || !$xiaoodds_7ree)  showmessage('jingcai_7ree:php_lang_error_odds_7ree',"");
}

$time_7ree = $time_7ree ? strtotime($time_7ree) : 0;
if($main_id_7ree){
DB::query("UPDATE ".DB::table('jingcai_main_7ree')." SET
		racename_7ree = '$racename_7ree',
		scid_7ree= '$scid_7ree',
		fenlei1_7ree = '$fenlei1_7ree',
		fenlei2_7ree = '$fenlei2_7ree',	
		reward_7ree = '$reward_7ree',
		add_7ree = '$add_7ree',
		time_7ree = '$time_7ree',
		aname_7ree = '$aname_7ree',
		bname_7ree = '$bname_7ree',
		alogo_7ree = '$alogo_7ree',
		blogo_7ree = '$blogo_7ree',
		amessage_7ree = '$amessage_7ree',
		bmessage_7ree = '$bmessage_7ree',
		aodds_7ree = '$aodds_7ree',
		bodds_7ree = '$bodds_7ree',
		codds_7ree = '$codds_7ree',
		daodds_7ree = '$daodds_7ree',
		xiaoodds_7ree = '$xiaoodds_7ree',
		odd_dynamic_7ree = '$odd_dynamic_7ree',
		max_odd_7ree = '$max_odd_7ree',
		min_odd_7ree = '$min_odd_7ree',
		odd_ratio_7ree = '$odd_ratio_7ree',
		rangqiufang_7ree = '$rangqiufang_7ree',
		rangqiuway_7ree = '$rangqiuway_7ree',
		racemessage_7ree = '$racemessage_7ree',
		win_7ree = '$win_7ree',
	    daxiao_7ree = '$daxiao_7ree',
	    ashot_7ree='$ashot_7ree',
		bshot_7ree='$bshot_7ree',
		daxiaowin_7ree='$daxiaowin_7ree'
        WHERE main_id_7ree = '$main_id_7ree'");

if($win_7ree && $jingcai_7ree_var['settlement_7ree']){//揭晓赛果，同步结算奖励
		settlement_7ree($main_id_7ree);
}

showmessage('jingcai_7ree:php_lang_chenggongbianji_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op");

}elseif(!$main_id_7ree){
DB::query("INSERT INTO ".DB::table('jingcai_main_7ree')." (
		 racename_7ree,
		scid_7ree,
		fenlei1_7ree,
		fenlei2_7ree,
		reward_7ree,
		add_7ree,
		time_7ree,
		aname_7ree,
		bname_7ree,
		alogo_7ree,
		blogo_7ree,
		amessage_7ree,
		bmessage_7ree,
		aodds_7ree,
		bodds_7ree,
		codds_7ree,
		daodds_7ree,
		xiaoodds_7ree,
		odd_dynamic_7ree,
		max_odd_7ree,
		min_odd_7ree,
		odd_ratio_7ree,
		rangqiufang_7ree,
		rangqiuway_7ree,
		racemessage_7ree,
		daxiao_7ree
        ) VALUES (
		'$racename_7ree',
		'$scid_7ree',
		'$fenlei1_7ree',
		'$fenlei2_7ree',
		'$reward_7ree',
		'$add_7ree',
		'$time_7ree',
		'$aname_7ree',
		'$bname_7ree',
		'$alogo_7ree',
		'$blogo_7ree',
		'$amessage_7ree',
		'$bmessage_7ree',
		'$aodds_7ree',
		'$bodds_7ree',
		'$codds_7ree',
		'$daodds_7ree',
		'$xiaoodds_7ree',
		'$odd_dynamic_7ree',
		'$max_odd_7ree',
		'$min_odd_7ree',
		'$odd_ratio_7ree',
		'$rangqiufang_7ree',
		'$rangqiuway_7ree',
		'$racemessage_7ree',
		'$daxiao_7ree'
        )");
       //同步发帖
        if($jingcai_7ree_var['fid_7ree']){
        $mid_7ree = DB::insert_id();
        $subject_7ree = $racename_7ree.': '.$aname_7ree.' VS '.$bname_7ree;
        $alogo_7ree = $alogo_7ree ? $alogo_7ree : "./source/plugin/jingcai_7ree/template/image/A_7ree.gif";
        $blogo_7ree = $blogo_7ree ? $blogo_7ree : "./source/plugin/jingcai_7ree/template/image/B_7ree.gif";
         
        $logourl_replace_7ree = array('./'=>$_G['setting']['siteurl']);
        
        $alogo_7ree = strtr($alogo_7ree,$logourl_replace_7ree);
        $blogo_7ree = strtr($blogo_7ree,$logourl_replace_7ree);   
        
        require_once DISCUZ_ROOT.'./source/function/function_forum.php'; 
		require_once DISCUZ_ROOT.'./source/function/function_post.php';
    
		$post_7ree = "[table=98%]
[tr][td=2,1][align=center][size=5][b]{$_GET['racename_7ree']}[/b][/size][/align][/td][/tr]
[tr][td][align=center]".lang('plugin/jingcai_7ree', 'php_lang_bisaishijian_7ree').": ".$_GET['time_7ree']."[/align][/td][td][align=center][url=plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=".$mid_7ree."][b][color=Red][backcolor=yellow]".lang('plugin/jingcai_7ree', 'php_lang_jinrujingcai_7ree')."[/backcolor][/color][/b][/url][/align][/td][/tr]
[tr][td][align=center]".$_GET['aname_7ree']."[/align][/td][td][align=center]".$_GET['bname_7ree']."[/align][/td][/tr]
[tr][td][align=center][img]".$alogo_7ree."[/img][/align][/td][td][align=center][img]".$blogo_7ree."[/img][/align][/td][/tr]
[tr][td]".$_GET['amessage_7ree']."[/td][td]".$_GET['bmessage_7ree']."[/td][/tr]
[tr][td=2,1]".$_GET['racemessage_7ree']."[/td][/tr]
[/table]";

        DB::query("INSERT INTO ".DB::table('forum_thread')." (fid, author, authorid, subject, dateline, lastpost, lastposter)
		VALUES ('$jingcai_7ree_var[fid_7ree]', '{$_G[username]}', '{$_G[uid]}', '{$subject_7ree}', '{$_G[timestamp]}', '{$_G[timestamp]}', '{$_G[username]}')");
		$tid = DB::insert_id();
		$pid = insertpost(array(
				'fid' => $jingcai_7ree_var['fid_7ree'],
				'tid' => $tid,
				'first' => '1',
				'author' => $_G['username'],
				'authorid' => $_G['uid'],
				'subject' => $subject_7ree,
				'dateline' => $_G['timestamp'],
				'message' => $post_7ree,
				'useip' => $_G['clientip'],
				'invisible' => $pinvisible,
				'anonymous' => $isanonymous,
				'usesig' => $usesig,
				'htmlon' => $htmlon,
				'bbcodeoff' => $bbcodeoff,
				'smileyoff' => $smileyoff,
				'parseurloff' => $parseurloff,
				'attachment' => '0',
				'tags' => $tagstr,
				'replycredit' => 0,
				'status' => (defined('IN_MOBILE') ? 8 : 0)
			));
		}	
        DB::query("UPDATE ".DB::table('jingcai_main_7ree')." SET tid_7ree = '$tid' WHERE main_id_7ree = '$mid_7ree' LIMIT 1");

		showmessage('jingcai_7ree:php_lang_chenggongtijiao_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=add");
}
?>