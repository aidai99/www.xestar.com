<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$table_gctx_spider = DB::table('gctx_spider');

DB::query("DROP TABLE IF EXISTS ". $table_gctx_spider);
$finish = true;
?>