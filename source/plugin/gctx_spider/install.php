<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS cdb_gctx_spider;
CREATE TABLE `cdb_gctx_spider` (
  `id` int(11) NOT NULL auto_increment comment 'id',
  `spidername` tinyint(1) unsigned DEFAULT '0' comment '蜘蛛名 1、百度 2、360 3、soso 4、搜狗 ',
  `ip` varchar(16) DEFAULT '' comment 'ip',
  `addtime` int(10) unsigned DEFAULT '0' comment '添加时间',
  `title` varchar(255) DEFAULT '' comment '访问页面标题',
  `url` varchar(255) default '' comment '访问URL',
  PRIMARY KEY  (`id`),
  KEY  (`spidername`)
) ENGINE=MyISAM;
EOF;
runquery($sql);
$finish = true;
?>