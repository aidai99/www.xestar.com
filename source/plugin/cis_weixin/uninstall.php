<?php

/*
	[Cis!] (C)2005-2013
	This is NOT a freeware, use is subject to license terms

	$Id: uninstall.inc.php 2013-09-24 $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE pre_cis_weixin;
DROP TABLE pre_cis_weixin_apps;
DROP TABLE pre_cis_weixin_immwalog;
DROP TABLE pre_cis_weixin_setting;
DROP TABLE pre_cis_weixin_styles;
DROP TABLE pre_cis_weixin_uc;

EOF;
runquery($sql);

$finish = TRUE;

?>