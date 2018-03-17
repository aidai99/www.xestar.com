<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$lang = lang('plugin/gctx_spider');

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

	$extra		.= '&spidername='.$spidername;
}

$pagelist = DB::fetch_all("SELECT spidername, count(1) as _count,FROM_UNIXTIME(addtime,'%H') as liketime FROM ".DB::table('gctx_spider')."  WHERE 1 $srchadd GROUP BY CONCAT(spidername,FROM_UNIXTIME(addtime,'%H')) ORDER BY _count desc");

$spidername_ary		= $lang['spidername_ary'];
$spidername_ary_t	= $lang['spidername_ary_t'];
$spiderkey			= intval($_GET['spiderkey']);

$actionurl = "action=plugins&operation=config&do=$pluginid&identifier=$plugin[identifier]&pmod=".$module['name'];

include template('gctx_spider:stat');
?>