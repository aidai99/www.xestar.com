<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/3/31 14:06
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}




$sql = <<<EOF



CREATE TABLE IF NOT EXISTS `pre_x7ree_cash` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `time_7ree` int(10) NOT NULL,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(100) NOT NULL,
  `ext_7ree` int(8) NOT NULL,
  `cash_7ree` float NOT NULL,
  `type_7ree` tinyint(1) NOT NULL,
  `account_7ree` varchar(255) NOT NULL,
  `tel_7ree` varchar(255) NOT NULL,
  `status_7ree` tinyint(1) NOT NULL COMMENT '0´ýÉóºË1³É¹¦2²µ»Ø',
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;


EOF;

runquery($sql);




$finish = TRUE;




?>