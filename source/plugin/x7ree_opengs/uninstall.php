<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/7/25 2:26
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$sql = <<<EOF

DROP TABLE IF EXISTS `pre_x7ree_opengs_main`;
DROP TABLE IF EXISTS `pre_x7ree_opengs_option`;
DROP TABLE IF EXISTS `pre_x7ree_opengs_log`;

EOF;

runquery($sql);

$

$finish = TRUE;



?>