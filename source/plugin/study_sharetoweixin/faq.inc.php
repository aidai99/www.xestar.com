<?php
/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$identifier = CURMODULE;
$splugin_setting = $_G['cache']['plugin'][$identifier];
$splugin_lang = lang('plugin/'.$identifier);
include template($identifier.':faq');
?>