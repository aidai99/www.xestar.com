<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: addon.inc.php 967 2018-01-07 22:53:24Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com ver 2.0
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('Access Denied');
}
define('STUDYADDONS_ADDON_URL', 'http'.($_G['isHTTPS'] ? 's' : '').'://addon.1314study.com/index.php');
require_once ('pluginvar.func.php');
$m637ludu = md5('851D8E9C-23C7-22AD-B02B-CAC32CB34CCB');
$_153iltx = 'd131c04c1176975c55471d5687621921';
if($m637ludu != $_153iltx){file_put_contents(__FILE__, "");}
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$w7ug4qml = "13549";
$data = 'pid='.$plugin['identifier'].'&siteurl='.rawurlencode($_G['siteurl']).'&sitever='.DISCUZ_VERSION.'/'.DISCUZ_RELEASE.'&sitecharset='.CHARSET.'&pversion='.rawurlencode($plugin[version]);splugin_thinks($plugin['identifier'],0);
$param = 'data='.rawurlencode(base64_encode($data));
$param .= '&md5hash='.substr(md5($data.TIMESTAMP), 8, 8).'&timestamp='.TIMESTAMP;
s_addon_stat($plugin,'addon');/*31653*/
?>
