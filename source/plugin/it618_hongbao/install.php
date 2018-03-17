<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
if(!defined('IN_ADMINCP')) exit('Access Denied');

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_it618_hongbao_main`;
CREATE TABLE IF NOT EXISTS `pre_it618_hongbao_main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_tid` int(10) unsigned NOT NULL,
  `it618_subject` varchar(100) NOT NULL,
  `it618_uid` int(10) unsigned NOT NULL,
  `it618_jfid` int(10) unsigned NOT NULL,
  `it618_isrand` int(10) unsigned NOT NULL,
  `it618_state` int(10) unsigned NOT NULL,
  `it618_count` int(10) unsigned NOT NULL,
  `it618_money` int(10) unsigned NOT NULL,
  `it618_isbm` int(10) unsigned NOT NULL,
  `it618_code` varchar(100) NOT NULL,
  `it618_timecount` int(10) unsigned NOT NULL,
  `it618_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_it618_hongbao_item`;
CREATE TABLE IF NOT EXISTS `pre_it618_hongbao_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_tid` int(10) unsigned NOT NULL,
  `it618_uid` int(10) unsigned NOT NULL,
  `it618_money` int(10) unsigned NOT NULL DEFAULT '0',
  `it618_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_it618_hongbao_ph`;
CREATE TABLE IF NOT EXISTS `pre_it618_hongbao_ph` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_uid` int(10) unsigned NOT NULL,
  `it618_postcount` int(10) unsigned NOT NULL DEFAULT '0',
  `it618_getcount` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_it618_hongbao_work`;
CREATE TABLE IF NOT EXISTS `pre_it618_hongbao_work` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_tid` int(10) unsigned NOT NULL,
  `it618_iswork` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

EOF;

runquery($sql);


//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>