<?php
/*
 * 主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_nimba_member`;
CREATE TABLE `pre_nimba_member` (
  `uid` mediumint(8) unsigned NOT NULL, 
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` char(40) NOT NULL DEFAULT '',  
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`uid`)
)ENGINE=MyISAM;
EOF;

runquery($sql);
$finish = TRUE;

?>