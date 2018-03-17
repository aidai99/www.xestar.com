<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G[uid]){
	header("Location: member.php?mod=logging&action=login");
}
include_once libfile('function/member');
include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
$cacksign = $_G['cache']['plugin']['cack_app_sign'];
$navtitle = $cacksign['navtitle'];

$xinqingquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_xinqing')." order by displayorder asc");
while($cack = DB::fetch($xinqingquery)) {
	$xinqingquerysc[] = $cack;
	$xinqing[$cack[xid]][pic] = $cack[pic];
}

$qdlogquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_log')." where signtime>".strtotime(date('Ymd',time()))." order by signtime asc");
while($cack = DB::fetch($qdlogquery)) {
	$yhyji = $yhyji+1;
	$jrqdlogsc[] = $cack;
	if($cack[uid] == $_G[uid]){
		$yhyjqd = '1';
		$yhyjqdsx = $yhyji;
	}
}
include template('cack_app_sign:ajax_sign');
?>