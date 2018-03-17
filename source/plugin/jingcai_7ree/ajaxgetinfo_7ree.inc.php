<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2013 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 13:14 2013/6/30
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/		
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];

if (!$jingcai_7ree_var['agreement_7ree']) showmessage('jingcai_7ree:php_lang_agree_7ree');

$navtitle = $jingcai_7ree_var['navtitle_7ree'];
$metakeywords  = $jingcai_7ree_var['keywords_7ree'];
$metadescription  = $jingcai_7ree_var['description_7ree'];

$_GET['target_7ree'] = daddslashes(dhtmlspecialchars(trim($_GET['target_7ree'])));
$_GET['name_7ree'] = daddslashes(dhtmlspecialchars(trim($_GET['name_7ree'])));
if(!$_GET['name_7ree'])showmessage('ERROR, Missing Required Parameter! @7ree');

if($_GET['target_7ree']=='race'){

	$racemessage_7ree = DB::result_first("SELECT racemessage_7ree FROM  ".DB::table('jingcai_main_7ree')." WHERE racename_7ree = '$_GET[name_7ree]' ORDER BY main_id_7ree DESC");


}elseif($_GET['target_7ree']=='ateam' || $_GET['target_7ree']=='bteam'){

	$teaminfo_7ree = DB::fetch_first("SELECT * FROM  ".DB::table('jingcai_main_7ree')." WHERE aname_7ree = '$_GET[name_7ree]' OR bname_7ree = '$_GET[name_7ree]' ORDER BY main_id_7ree DESC");
	$logo_7ree = $message_7ree = '';
	if($teaminfo_7ree['aname_7ree']==$_GET['name_7ree']){
		$logo_7ree = $teaminfo_7ree['alogo_7ree'];
		$message_7ree = $teaminfo_7ree['amessage_7ree'];
	}elseif($teaminfo_7ree['bname_7ree']==$_GET['name_7ree']){
		$logo_7ree = $teaminfo_7ree['blogo_7ree'];
		$message_7ree = $teaminfo_7ree['bmessage_7ree'];
	}
	
}else{
	showmessage('ERROR, Missing Required Parameter! @7ree');
}

include template('jingcai_7ree:jingcai_ajaxgetinfo_7ree');

?>