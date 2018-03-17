<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/1 13:57
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (!submitcheck('add_submit_7ree', 1)) exit('Access Denied @ 7ree');

$scid_7ree = intval($_GET['scid_7ree']);

$scname_7ree = trim(daddslashes(dhtmlspecialchars($_GET['scname_7ree'])));
$time_7ree = trim(daddslashes(dhtmlspecialchars($_GET['time_7ree'])));
$time2_7ree = trim(daddslashes(dhtmlspecialchars($_GET['time2_7ree'])));
$aname_7ree = trim(daddslashes(dhtmlspecialchars($_GET['aname_7ree'])));
$bname_7ree = trim(daddslashes(dhtmlspecialchars($_GET['bname_7ree'])));
$alogo_7ree = trim(daddslashes(dhtmlspecialchars($_GET['alogo_7ree'])));
$blogo_7ree = trim(daddslashes(dhtmlspecialchars($_GET['blogo_7ree'])));
$fenlei1_7ree = trim(daddslashes(dhtmlspecialchars($_GET['fenlei1_7ree'])));
$fenlei2_7ree = trim(daddslashes(dhtmlspecialchars($_GET['fenlei2_7ree'])));
$arate_7ree = trim(daddslashes(dhtmlspecialchars($_GET['arate_7ree'])));
$brate_7ree = trim(daddslashes(dhtmlspecialchars($_GET['brate_7ree'])));

if(!$scname_7ree) showmessage('jingcai_7ree:php_lang_error_name_7ree');
if(!$time_7ree || !$time2_7ree) showmessage('jingcai_7ree:php_lang_error_time_7ree');
if(!$aname_7ree) showmessage('jingcai_7ree:php_lang_error_aname_7ree');
if(!$bname_7ree) showmessage('jingcai_7ree:php_lang_error_bname_7ree');


//if(!$arate_7ree || !$brate_7ree)  showmessage('jingcai_7ree:php_lang_error_odds_7ree',"");


$time_7ree = $time_7ree ? strtotime($time_7ree) : 0;
$time2_7ree = $time_7ree ? strtotime($time2_7ree) : 0;
if($scid_7ree){
DB::query("UPDATE ".DB::table('jingcai_saicheng_7ree')." SET
		scname_7ree = '$scname_7ree',
		fenlei1_7ree = '$fenlei1_7ree',
		fenlei2_7ree = '$fenlei2_7ree',
		time_7ree = '$time_7ree',
		time2_7ree = '$time2_7ree',
		aname_7ree = '$aname_7ree',
		bname_7ree = '$bname_7ree',
		alogo_7ree = '$alogo_7ree',
		blogo_7ree = '$blogo_7ree',
		arate_7ree = '$arate_7ree',
		brate_7ree = '$brate_7ree'
        WHERE scid_7ree = '$scid_7ree'");


showmessage('jingcai_7ree:php_lang_chenggongbianji_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op1");

}elseif(!$main_id_7ree){
DB::query("INSERT INTO ".DB::table('jingcai_saicheng_7ree')." (
		scname_7ree,
		fenlei1_7ree,
		fenlei2_7ree,
		time_7ree,
		time2_7ree,
		aname_7ree,
		bname_7ree,
		alogo_7ree,
		blogo_7ree,
		arate_7ree,
		brate_7ree
        ) VALUES (
		 '$scname_7ree',
		'$fenlei1_7ree',
		'$fenlei2_7ree',
		'$time_7ree',
		'$time2_7ree',
		'$aname_7ree',
		'$bname_7ree',
		'$alogo_7ree',
		'$blogo_7ree',
		'$arate_7ree',
		'$brate_7ree'
        )");

		showmessage('jingcai_7ree:php_lang_chenggongtijiao_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=2&sp_7ree=op1");
}
?>