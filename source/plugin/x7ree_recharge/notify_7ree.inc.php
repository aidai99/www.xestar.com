<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/3/29 11:03
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// 柒瑞积分充值系统

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$vars_7ree = $_G['cache']['plugin']['x7ree_recharge'];


if(!$_G['uid']){
		showmessage('not_loggedin', NULL, array(), array('login' => 1));
}

echo("success");
?>