<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_it618_hongbao_ph` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_uid` int(10) unsigned NOT NULL,
  `it618_postcount` int(10) unsigned NOT NULL DEFAULT '0',
  `it618_getcount` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

EOF;

runquery($sql);

$query = DB::query("SHOW COLUMNS FROM ".DB::table('it618_hongbao_main'));
while($row = DB::fetch($query)) {
	$col_field[]=$row['Field']; 
}
if(!in_array('it618_isbm', $col_field)){
	$sql = "Alter table ".DB::table('it618_hongbao_main')." add `it618_isbm` int(10) unsigned NOT NULL DEFAULT '0';"; 
	DB::query($sql);
}
if(!in_array('it618_timecount', $col_field)){
	$sql = "Alter table ".DB::table('it618_hongbao_main')." add `it618_timecount` int(10) unsigned NOT NULL"; 
	DB::query($sql);  
}
if(!in_array('it618_time', $col_field)){
	$sql = "Alter table ".DB::table('it618_hongbao_main')." add `it618_time` int(10) unsigned NOT NULL DEFAULT '0';"; 
	DB::query($sql);  
}
if(!in_array('it618_subject', $col_field)){
	$sql = "Alter table ".DB::table('it618_hongbao_main')." add `it618_subject` varchar(100) NOT NULL;"; 
	DB::query($sql);  
}

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>