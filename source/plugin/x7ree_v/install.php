<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/13 17:37
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}




$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_x7ree_v_main` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `name_7ree` varchar(255) NOT NULL,
  `url_7ree` varchar(500) NOT NULL,
  `pic_7ree` varchar(255) NOT NULL,
  `fenlei_7ree` varchar(100) NOT NULL,
  `detail_7ree` text NOT NULL,
  `status_7ree` tinyint(1) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `view_7ree` mediumint(8) NOT NULL,
  `zan_7ree` mediumint(8) NOT NULL,
  `fav_7ree` mediumint(8) NOT NULL,
  `discuss_7ree` mediumint(8) NOT NULL,
  `cost_7ree` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_x7ree_v_discuss` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `vid_7ree` mediumint(8) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `zan_7ree` mediumint(8) NOT NULL,
  `message_7ree` text NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_x7ree_v_buylog` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `vid_7ree` mediumint(8) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `cost_7ree` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

EOF;

runquery($sql);




$finish = TRUE;




?>