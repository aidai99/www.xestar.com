<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/9/26 16:47
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}




$sql = <<<EOF


CREATE TABLE IF NOT EXISTS `pre_x7ree_opengs_main` (
  `id_7ree` int(10) NOT NULL auto_increment,
  `mid_7ree` int(10) NOT NULL,
  `name_7ree` varchar(255) NOT NULL,
  `fenlei_7ree` varchar(255) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `pic_7ree` varchar(255) NOT NULL,
  `detail_7ree` text NOT NULL,
  `oid_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_x7ree_opengs_option` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `rid_7ree` int(8) NOT NULL,
  `option_7ree` varchar(255) NOT NULL,
  `odds_7ree` float NOT NULL,
  `count_7ree` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;


CREATE TABLE IF NOT EXISTS `pre_x7ree_opengs_log` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `rid_7ree` int(8) NOT NULL,
  `oid_7ree` int(8) NOT NULL,
  `uid_7ree` int(10) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `odds_7ree` float NOT NULL,
  `ext_7ree` int(10) NOT NULL,
  `reward_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


EOF;

runquery($sql);




$finish = TRUE;




?>