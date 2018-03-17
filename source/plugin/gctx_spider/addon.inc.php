<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$lang = lang('plugin/gctx_spider');

$truncate = intval($_GET['truncate']);
if($truncate == 1) {
	$query = DB::query("TRUNCATE TABLE ".DB::table('gctx_spider')."");
}

$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;


$page = max(1, intval($_GET['page']));
$i=1;

$srchadd = $extra = '';
if($_GET['spiderkey']) {
	$spidername	= intval($_GET['spiderkey']);

	switch ($spidername) {
		case 4:
		case 5:
			$srchadd	= " AND spidername in (4, 5)";
			break;
		case 6:
		case 7:
			$srchadd	= " AND spidername in (6, 7)";
			break;
		case 8:
		case 9:
			$srchadd	= " AND spidername in (8, 9)";
			break;
		case 10:
		case 11:
			$srchadd	= " AND spidername in (10, 11)";
			break;
		case 12:
		case 13:
			$srchadd	= " AND spidername in (12, 13)";
			break;
		default:
			$srchadd	= " AND spidername = $spidername";
			break;
	}

	$extra		.= '&spiderkey='.$spidername;
}

if($perpage) {
	$extra		.= '&perpage='.$perpage;
}

$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('gctx_spider')." WHERE 1 $srchadd");
$pagelist = DB::fetch_all("SELECT * FROM ".DB::table('gctx_spider')." WHERE 1 $srchadd ORDER BY id DESC LIMIT ".(($page - 1) * $perpage).",$perpage");

$spidername_ary		= $lang['spidername_ary'];
$spidername_ary_t	= $lang['spidername_ary_t'];
$spiderkey			= intval($_GET['spiderkey']);
$pagenum			= (($page - 1) * $perpage);

$actionurl = "action=plugins&operation=config&do=$pluginid&identifier=$plugin[identifier]&pmod=".$module['name'];


$pagemulti = multi($count, $perpage, $page, ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=$plugin[identifier]&pmod=$module[name]$extra");

include template('gctx_spider:addon');
?>