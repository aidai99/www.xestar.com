<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
$cacksign = $_G['cache']['plugin']['cack_app_sign'];
$signjifen = mt_rand($cacksign['zsjljf'],$cacksign['zdjljf']);

if($_G[uid]){
	$qdlogquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_log')." where uid=".$_G[uid]." order by signtime desc LIMIT 0,1");
	while($cack = DB::fetch($qdlogquery)) {
		$qdlogsc[] = $cack;
	}
    if ($_GET['signxid'] && $_GET[formhash] == FORMHASH && date('Ymd',$qdlogsc[0][signtime]) !=  date('Ymd',time())) {
            DB::insert('cack_app_sign_log', array('uid' => $_G[uid], 'username' => $_G[member][username], 'signtime' =>time(), 'xid' => intval($_GET['signxid']), 'extcredits' => $cacksign['jfsz'], 'jifen' => $signjifen, 'content' => dhtmlspecialchars(substr($_GET['content'],'0','160'))));
			updatemembercount($_G['uid'], array($cacksign['jfsz'] => $signjifen));	
		if ($_GET['pcsign']) {
			showmessage($signlang[1], 'index.php');
		}else{
			header("Location: plugin.php?id=cack_app_sign");
		}
	}else{
		showmessage($signlang[2], 'plugin.php?id=cack_app_sign');	
    }
}else{
	header("Location: member.php?mod=logging&action=login");
}
?>