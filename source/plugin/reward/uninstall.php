<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

DB::query("DROP TABLE IF EXISTS ".DB::table('reward_log')."");

Header("Location: http://moguwang.net/apis.php?mod=uninstall&type=reward"); 

$finish = TRUE;
?>