<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: config.inc.php 4003 2016-07-02 13:32:28Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('http://xestar.com/');
}

$pluginvars = array();
foreach(C::t('common_pluginvar')->fetch_all_by_pluginid($pluginid) as $var) {//1314学习网
if(!strexists($var['type'], '_')) {
C::t('common_pluginvar')->update_by_variable($pluginid, $var['variable'], array('type' => $var['type'].'_1314'));
}else{
$type = explode('_', $var['type']);/*1314学习网*/
if($type[1] == '1314'){
$var['type'] = $type[0];
}else{#1.3.1.4.学.习.网
continue;
}
}
$pluginvars[$var['variable']] = $var;
}
require_once 'pluginvar.func.php';
if(!submitcheck('editsubmit')) {/*本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权*/
$operation = '';
if($pluginvars) {
$_statInfo = array();$_statInfo['pluginName'] = $plugin['identifier'];$_statInfo['pluginVersion'] = $plugin['version'];$_statInfo['bbsVersion'] = DISCUZ_VERSION;$_statInfo['bbsRelease'] = DISCUZ_RELEASE;$_statInfo['timestamp'] = TIMESTAMP;$_statInfo['bbsUrl'] = $_G['siteurl'];$_statInfo['SiteUrl'] = 'http://xestar.com/';$_statInfo['ClientUrl'] = 'https://www.xestar.com/';$_statInfo['SiteID'] = '5DCADF3C-1458-2C42-6DBC-5DED736231F5';$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];$_statInfo['genuine'] = splugin_genuine($plugin['identifier']);#本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权
showformheader("plugins&operation=config&do=$pluginid");
showtableheader();
echo '<div id="my_addonlist"></div>';
showtitle($lang['plugins_config']);
$extra = array();
foreach($pluginvars as $var) {
if(strexists($var['type'], '_')) {
continue;#本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权
}
if($var['variable'] == 'js'){/*8012*/
$var['description'] = 'js&#x4EE3;&#x7801;&#x83B7;&#x53D6;&#x5730;&#x5740;&#xFF1A;<a href="http://zhanzhang.so.com/?m=SiteMapAuto" target="_blank" style="color:blue;">http://zhanzhang.so.com/?m=SiteMapAuto</a>';
$mddl85md = "1.3.1.4.学.习.网";
}
$var['variable'] = 'varsnew['.$var['variable'].']';
s_showsetting(isset($lang[$var['title']]) ? $lang[$var['title']] : dhtmlspecialchars($var['title']), $var['variable'], $var['value'], $var['type'], '', 0, isset($lang[$var['description']]) ? $lang[$var['description']] : nl2br($var['description']), dhtmlspecialchars($var['extra']), '', true);
}
showsubmit('editsubmit');
showtablefooter();#本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权
showformfooter();
echo implode('', $extra);
echo '<div id="my_addonlist_temp" style="display:none;"><script id="my_addonlist_js" src="http://www.discuz.1314study.com/services.php?mod=product&ac=js&op=manage&timestamp='.$_G['timestamp'].'&info='.base64_encode(serialize($_statInfo)).'&md5check='.md5(base64_encode(serialize($_statInfo))).'"></script></div>
		<script type="text/javascript">$("my_addonlist_js").src= "";$("my_addonlist").innerHTML = $("my_addonlist_temp").innerHTML;</script>';
$_1vk2jmt = "版权：1314学习网，未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权";
}
} else {
$xatb2t09 = "版权：1314学习网，未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权";
if(is_array($_GET['varsnew'])) {
foreach($_GET['varsnew'] as $variable => $value) {
if(isset($pluginvars[$variable])) {/*版权：1314学习网，未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权*/
if($pluginvars[$variable]['type'] == 'number') {
$value = (float)$value;
} elseif(in_array($pluginvars[$variable]['type'], array('forums', 'groups', 'selects'))) {
$value = serialize($value);
}
$value = (string)$value;//www_discuz_1314study_com
C::t('common_pluginvar')->update_by_variable($pluginid, $variable, array('value' => $value));
}
}
}
updatecache(array('plugin', 'setting', 'styles'));
cleartemplatecache();
$bcxmhbgt = "本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权";
cpmsg('plugins_setting_succeed', 'action=plugins&operation=config&do='.$pluginid.'&anchor='.$anchor, 'succeed');
}


//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: config.inc.php 4449 2016-07-02 05:32:28Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。