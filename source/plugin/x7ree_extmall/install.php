<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/7/3 14:02
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}




$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_extmall_goods_7ree` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `name_7ree` varchar(255) NOT NULL,
  `fenlei_7ree` varchar(100) NOT NULL,
  `price_7ree` float NOT NULL,
  `cost_7ree` mediumint(8) NOT NULL,
  `pic_7ree` varchar(255) NOT NULL,
  `num_7ree` varchar(8) NOT NULL,
  `detail_7ree` text NOT NULL,
  `displayorder_7ree` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_extmall_log_7ree` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `gid_7ree` mediumint(8) NOT NULL,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(100) NOT NULL,
  `cost_7ree` mediumint(8) NOT NULL,
  `ip_7ree` varchar(20) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `status_7ree` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

EOF;

runquery($sql);




$finish = TRUE;




?>