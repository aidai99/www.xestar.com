<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: config.inc.php 4003 2016-07-02 13:32:28Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('http://xestar.com/');
}

$pluginvars = array();
foreach(C::t('common_pluginvar')->fetch_all_by_pluginid($pluginid) as $var) {//1314ѧϰ��
if(!strexists($var['type'], '_')) {
C::t('common_pluginvar')->update_by_variable($pluginid, $var['variable'], array('type' => $var['type'].'_1314'));
}else{
$type = explode('_', $var['type']);/*1314ѧϰ��*/
if($type[1] == '1314'){
$var['type'] = $type[0];
}else{#1.3.1.4.ѧ.ϰ.��
continue;
}
}
$pluginvars[$var['variable']] = $var;
}
require_once 'pluginvar.func.php';
if(!submitcheck('editsubmit')) {/*�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ*/
$operation = '';
if($pluginvars) {
$_statInfo = array();$_statInfo['pluginName'] = $plugin['identifier'];$_statInfo['pluginVersion'] = $plugin['version'];$_statInfo['bbsVersion'] = DISCUZ_VERSION;$_statInfo['bbsRelease'] = DISCUZ_RELEASE;$_statInfo['timestamp'] = TIMESTAMP;$_statInfo['bbsUrl'] = $_G['siteurl'];$_statInfo['SiteUrl'] = 'http://xestar.com/';$_statInfo['ClientUrl'] = 'https://www.xestar.com/';$_statInfo['SiteID'] = '5DCADF3C-1458-2C42-6DBC-5DED736231F5';$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];$_statInfo['genuine'] = splugin_genuine($plugin['identifier']);#�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
showformheader("plugins&operation=config&do=$pluginid");
showtableheader();
echo '<div id="my_addonlist"></div>';
showtitle($lang['plugins_config']);
$extra = array();
foreach($pluginvars as $var) {
if(strexists($var['type'], '_')) {
continue;#�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
}
if($var['variable'] == 'js'){/*8012*/
$var['description'] = 'js&#x4EE3;&#x7801;&#x83B7;&#x53D6;&#x5730;&#x5740;&#xFF1A;<a href="http://zhanzhang.so.com/?m=SiteMapAuto" target="_blank" style="color:blue;">http://zhanzhang.so.com/?m=SiteMapAuto</a>';
$mddl85md = "1.3.1.4.ѧ.ϰ.��";
}
$var['variable'] = 'varsnew['.$var['variable'].']';
s_showsetting(isset($lang[$var['title']]) ? $lang[$var['title']] : dhtmlspecialchars($var['title']), $var['variable'], $var['value'], $var['type'], '', 0, isset($lang[$var['description']]) ? $lang[$var['description']] : nl2br($var['description']), dhtmlspecialchars($var['extra']), '', true);
}
showsubmit('editsubmit');
showtablefooter();#�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
showformfooter();
echo implode('', $extra);
echo '<div id="my_addonlist_temp" style="display:none;"><script id="my_addonlist_js" src="http://www.discuz.1314study.com/services.php?mod=product&ac=js&op=manage&timestamp='.$_G['timestamp'].'&info='.base64_encode(serialize($_statInfo)).'&md5check='.md5(base64_encode(serialize($_statInfo))).'"></script></div>
		<script type="text/javascript">$("my_addonlist_js").src= "";$("my_addonlist").innerHTML = $("my_addonlist_temp").innerHTML;</script>';
$_1vk2jmt = "��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ";
}
} else {
$xatb2t09 = "��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ";
if(is_array($_GET['varsnew'])) {
foreach($_GET['varsnew'] as $variable => $value) {
if(isset($pluginvars[$variable])) {/*��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ*/
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
$bcxmhbgt = "�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ";
cpmsg('plugins_setting_succeed', 'action=plugins&operation=config&do='.$pluginid.'&anchor='.$anchor, 'succeed');
}


//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: config.inc.php 4449 2016-07-02 05:32:28Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��