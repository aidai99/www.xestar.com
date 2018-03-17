<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: help.inc.php 2017-12-17 18:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

echo "<style>.hlol li{text-align:center;line-height:20px;font-size:14px;margin:5px auto;padding:5px;background:#FFFFFF;border:1px solid #CCCCCC;box-shadow:1px  1px 1px #FFFFFF}.hlol li:hover{margin:5px auto;background:#FAFAFA;border:1px solid #888888;box-shadow:1px  1px 1px #CCCCCC}.hlol a{color:#888888;}.hlol a:hover{text-decoration:none;color:#FF0000;}</style>";
echo "<div style='padding:10px;margin:10px;line-height:25px;'>".lang('plugin/hlol_living', 'help')."</div>";
$url = lang('plugin/hlol_living', 'qqurl');
echo "<ul style=\"padding:10px;margin:10px;width:200px;\" class=\"hlol\"><a target=\"_blank\" href=\"".$url."\"><li>".lang('plugin/hlol_living', 'dev')."</li></a></ul>";

?>