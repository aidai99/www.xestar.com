<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

DB::query("ALTER TABLE ".DB::table('reward_log')." ADD `username` varchar(25) NOT NULL ;",'SILENT');
DB::query("ALTER TABLE ".DB::table('reward_log')." ADD `message` varchar(255) NOT NULL ;",'SILENT');

$finish = TRUE;

?>