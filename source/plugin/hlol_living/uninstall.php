<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: uninstall.php 2018-01-02 13:00:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tablename = DB::table('hlol_living');

$sql = <<<EOF
DROP TABLE IF EXISTS `{$tablename}`;
EOF;

runquery($sql);

require_once DISCUZ_ROOT.'./source/plugin/hlol_living/function/function_common.php';
hlolrmdir(DISCUZ_ROOT.'/data/attachment/hlolliving/');

$finish = TRUE;

?>