<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_main");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_item");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_user");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_findtid");

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>