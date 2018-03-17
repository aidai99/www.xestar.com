<?php

/*
	[Cis!] (C)2005-2013 comeings.com.
	This is NOT a freeware, use is subject to license terms

	$Id: upgrade.inc.php 2013-09-24 $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$theversion=(float)$_GET['fromversion'];

if ($theversion<1.5) {

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_cis_weixin_hack` (
	`id` char(32) NOT NULL,
	`type` char(20) NOT NULL default 'plugin',
	`name` char(32) NOT NULL,
	`dir` char(32) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
EOF;
runquery($sql);	
}

if($theversion>=1.5 && $theversion<=1.6){
$sql = <<<EOF
ALTER TABLE `pre_cis_weixin_hack` ADD `type` VARCHAR( 20 ) NOT NULL DEFAULT 'plugin' AFTER `id`;
EOF;
runquery($sql);	
}

$finish = TRUE;
?>
