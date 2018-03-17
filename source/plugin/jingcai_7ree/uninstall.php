<?php

/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2016 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 17:35 2015/4/3
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$sql = <<<EOF
DROP TABLE IF EXISTS `pre_jingcai_main_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_log_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_guanzhu_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_payment_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_member_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_fencheng_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_tmp_7ree`;
DROP TABLE IF EXISTS `pre_jingcai_tuiguang_7ree`;
EOF;

runquery($sql);

$finish = TRUE;




?>