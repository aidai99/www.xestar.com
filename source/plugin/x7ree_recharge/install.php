<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/3/31 17:38
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}




$sql = <<<EOF


CREATE TABLE `pre_x7ree_recharge_log` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `ext_7ree` mediumint(8) NOT NULL,
  `cost_7ree` float NOT NULL,
  `type_7ree` tinyint(1) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `bank_7ree` varchar(255) NOT NULL,
  `account_7ree` varchar(255) NOT NULL,
  `cardnum_7ree` varchar(255) NOT NULL,
  `orderid_7ree` varchar(255) NOT NULL,
  `status_7ree` tinyint(1) NOT NULL,
  `ip_7ree` varchar(12) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


EOF;

runquery($sql);




$finish = TRUE;




?>