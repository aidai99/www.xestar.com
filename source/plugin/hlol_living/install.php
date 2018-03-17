<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: install.php 2017-12-16 09:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tablename = DB::table('hlol_living');

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `{$tablename}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catname` int(11) DEFAULT '1',
  `title` varchar(255) DEFAULT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `fsort` int(11) DEFAULT '10',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
EOF;

runquery($sql);

$finish = TRUE;

?>