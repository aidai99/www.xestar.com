<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `cdb_reward_log` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(9) NOT NULL,
  `touid` int(9) NOT NULL,
  `uid` int(9) NOT NULL,
  `username` varchar(25) NOT NULL,
  `money` smallint(4) NOT NULL,
  `dateline` int(10) NOT NULL,
  `message`	varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `touid` (`touid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;
EOF;
runquery($sql);

$finish = TRUE;	
?>